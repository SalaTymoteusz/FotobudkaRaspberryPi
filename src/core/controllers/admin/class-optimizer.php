<?php

namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Optimizer extends Admin{

    protected $id = 'optimizer';
    
    protected $title = 'Optymalizacja';

    protected $parent = 'tools.php';

    protected $permission = 'manage_options';

    protected $sections = [
        'default' => '',
    ];
    protected $properties = [
        'enable_htaccess_optimizer' => ['label' => 'Optymalizacja .htaccess', 'validate' => false,'required' => false],
        'enable_optimizer' => ['label' => 'Optymalizacja plików CSS i JS', 'validate' => false,'required' => false],
        'external_js' => ['label' => 'Zewnętrzne pliki JS', 'validate' => false, 'required' => false],
        'external_css' => ['label' => 'Zewnętrzne pliki CSS', 'validate' => false, 'required' => false],
    ];

    protected function add_actions(){
        parent::add_actions();
        add_action('update_option_enable_htaccess_optimizer', [$this,'rebuild_mod_rewrite_rules']);
    }


    protected function add_filters(){

        parent::add_filters();
        add_filter('mod_rewrite_rules', [$this,'add_htaccess_optimizetions']);
    }


    public function init_fields(){
        add_settings_field(
            'enable_htaccess_optimizer', // ID
            __($this->properties['enable_htaccess_optimizer']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'switch',
                'value' => $this->get_property_value('enable_htaccess_optimizer'),
                'name' => 'enable_htaccess_optimizer',
                'id' => 'enable_htaccess_optimizer',
                'options' => [[
                    'label' => __('Tak','fotobudka'),
                    'value' => 1
                ],[
                    'label' => __('Nie','fotobudka'),
                    'value' => -1
                ]],
                'desc' => __('Zaznacz aby włączyć optymalizacje serwera','fotobudka') 
            )
        );

        add_settings_field(
            'enable_optimizer', // ID
            __($this->properties['enable_optimizer']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'switch',
                'value' => $this->get_property_value('enable_optimizer'),
                'name' => 'enable_optimizer',
                'id' => 'enable_optimizer',
                'options' => [[
                    'label' => __('Tak','fotobudka'),
                    'value' => 1
                ],[
                    'label' => __('Nie','fotobudka'),
                    'value' => -1
                ]],
                'desc' => __('Zaznacz aby włączyć cachowanie plików','fotobudka')
            )
        );


        add_settings_field(
            'external_css', // ID
            __($this->properties['external_css']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'checkbox',
                'value' => $this->get_property_value('external_css'),
                'name' => 'external_css',
                'id' => 'external_css',
                'options' => [[
                    'label' => __('Zaznacz jeśli chcesz aby zewnętrzne pliki CSS również były cachowane','fotobudka'),
                    'value' => 1
                ]]
            )
        );


        add_settings_field(
            'external_js', // ID
            __($this->properties['external_js']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'checkbox',
                'value' => $this->get_property_value('external_js'),
                'name' => 'external_js',
                'id' => 'external_js',
                'options' => [[
                    'label' => __('Zaznacz jeśli chcesz aby zewnętrzne pliki JS również były cachowane','fotobudka'),
                    'value' => 1
                ]]
            )
        );

    }



    public function add_htaccess_optimizetions($rules){
        if($this->get_property_value('enable_htaccess_optimizer') < 1){
            return $rules;
        }

        $home_root = parse_url( home_url() );
		if ( isset( $home_root['path'] ) ) {
			$home_root = trailingslashit( $home_root['path'] );
		} else {
			$home_root = '/';
        }

        $protection_rule = <<<EOD
\n<FilesMatch "wp-config.*\.php|\.htaccess|readme\.html">
Order allow,deny
Deny from all
</FilesMatch>\n\n
EOD;


    $images_edge_rule = <<<EOD
\n<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $home_root
RewriteCond %{HTTP_USER_AGENT} Edge
RewriteRule (.+)\.(jpg|jpeg|png)$ $1.$2 [NC,L]
</IfModule>\n\n
EOD;


$images_webp_rule = <<<EOD
\n<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $home_root
RewriteCond %{HTTP_USER_AGENT} Chrome [OR]
RewriteCond %{HTTP_USER_AGENT} "Google Page Speed Insights" [OR]
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{REQUEST_FILENAME} (.*)\.(jpg|jpeg|png)$
RewriteCond %1\.webp -f
RewriteRule (.+)\.(jpg|jpeg|png)$ $1.webp [NC,L]
</IfModule>\n\n
EOD;

$optimization_rules = <<<EOD
\n<IfModule mod_headers.c>
Header append Vary Accept env=REDIRECT_accept
</IfModule>

<FilesMatch ".(ico|jpg|jpeg|png|webp|gif|js|css|swf|ttf|eot|otf|woff|woff2|svg)$">

# Add correct content-type for fonts
AddType application/vnd.ms-fontobject .eot
AddType application/x-font-ttf .ttf
AddType application/x-font-opentype .otf
AddType application/font-woff .woff
AddType application/font-woff2 woff2
AddType image/svg+xml .svg
AddType image/webp .webp

# Compress compressible fonts

ExpiresActive On
ExpiresDefault A31536000
ExpiresByType text/css A31536000
ExpiresByType application/x-javascript A31536000
ExpiresByType application/javascript A31536000
ExpiresByType text/javascript A31536000
ExpiresByType text/html A3600
ExpiresByType text/plain A3600
ExpiresByType image/gif A31536000
ExpiresByType image/ico A31536000
ExpiresByType image/jpg A31536000
ExpiresByType image/jpeg A31536000
ExpiresByType image/svg A31536000
ExpiresByType image/png A31536000
ExpiresByType image/webp A31536000
ExpiresByType application/vnd.ms-fontobject A31536000
ExpiresByType application/x-font-ttf A31536000
ExpiresByType application/x-font-opentype A31536000
ExpiresByType application/font-woff A31536000
ExpiresByType application/font-woff2 A31536000
ExpiresByType image/svg+xml A31536000
Header unset ETag
FileETag None

</FilesMatch>


<IfModule mod_deflate.c>
# Compress HTML, CSS, JavaScript, Text, XML and fonts
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
AddOutputFilterByType DEFLATE application/x-font
AddOutputFilterByType DEFLATE application/x-font-opentype
AddOutputFilterByType DEFLATE application/x-font-otf
AddOutputFilterByType DEFLATE application/x-font-truetype
AddOutputFilterByType DEFLATE application/x-font-ttf
AddOutputFilterByType DEFLATE application/x-javascript
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE font/opentype
AddOutputFilterByType DEFLATE font/otf
AddOutputFilterByType DEFLATE font/ttf
AddOutputFilterByType DEFLATE image/svg+xml
AddOutputFilterByType DEFLATE image/x-icon
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/javascript
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/xml

# Remove browser bugs (only needed for really old browsers)
BrowserMatch ^Mozilla/4 gzip-only-text/html
BrowserMatch ^Mozilla/4\.0[678] no-gzip
BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
Header append Vary User-Agent
</IfModule>
EOD;

    return $protection_rule . $rules . $images_edge_rule . $images_webp_rule . $optimization_rules;
    }


    public function rebuild_mod_rewrite_rules(){        
        flush_rewrite_rules(true);
    }

}
