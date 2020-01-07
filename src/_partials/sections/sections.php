<?php

use WTM\Model\Template;
use WTM\Model\Validate;
if ( ! defined( 'ABSPATH' ) ) exit;

if(!isset($section) || empty($section)) return;

$type = $section['acf_fc_layout'];
?>

<section class="section section-<?php echo $type; ?>  position-relative">
        <?php 
      
        switch($type):
            case 'landing_page':
                echo '<div class="position-relative">';
                Template::get_template('_partials/sections/landing_page',['section' => $section]);
                echo '</div>';
                break;
            case 'text_image':
               
                $product = get_field('product_type');
                Template::get_template('_partials/sections/text_image',['section' => $section]);
                break;
            case 'text_gallery':
                echo '<div class="position-relative">';
                Template::get_template('_partials/sections/text_gallery',['section' => $section]);
                echo '</div>';
                break;
            case 'text_quote':
                echo '<div class="position-relative container ">';
                Template::get_template('_partials/sections/text_quote',['section' => $section]);
                echo '</div>';
                break;
        endswitch;
        ?>

</section>