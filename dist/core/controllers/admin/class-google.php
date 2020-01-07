<?php

namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Google extends Admin{
    protected $id = 'google_maps';
    
    protected $title = 'Google';

    protected $parent = 'options-general.php';

    protected $permission = 'manage_options';

    protected $sections = [
        'default' => '',
    ];
    protected $properties = [
        'map_key' => ['label' => 'Klucz Google Maps', 'validate' => false,'required' => false],
        'analitics_id' => ['label' => 'ID Google Analitics', 'validate' => false, 'required' => false],
    ];

    protected function add_actions(){
        parent::add_actions();

        add_action('acf/init', [$this,'set_map_key_to_acf']);
    }


    public function set_map_key_to_acf(){
        if($key = $this->get_property_value('map_key')){
            acf_update_setting('google_api_key', $key);
        }
    }


    public function init_fields(){

        add_settings_field(
            'map_key', // ID
            __($this->properties['map_key']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'text',
                'value' => $this->get_property_value('map_key'),
                'name' => 'map_key',
                'id' => 'map_key',
                'class_input' => 'regular-text',
            )
        );


        add_settings_field(
            'analitics_id', // ID
            __($this->properties['analitics_id']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_default",
            array(
                'type' => 'text',
                'value' => $this->get_property_value('analitics_id'),
                'name' => 'analitics_id',
                'id' => 'analitics_id',
            )
        );

    }
}