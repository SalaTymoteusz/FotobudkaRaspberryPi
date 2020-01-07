<?php

namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Model\Validate;
use WP_Error;

class Template_Options extends Admin{

    protected $id = 'template_options';
    
    protected $title = 'Ustawienia dodatkowe';

    protected $parent = 'options-general.php';

    protected $permission = 'manage_options';
    

    protected $sections = [
        'contact' => 'Informacje kontaktowe',
        'social_media' => 'Social Media',
    ];


    protected $properties = [
        'phone' => ['label' => 'Numer Telefonu', 'required' => false, 'validate' => 'is_phone_number'],
        'email' => ['label' => 'Adres email','required' => false, 'validate' => 'is_email'],
        'youtube_link' => ['label' => 'Link do YouTuba','required' => false, 'validate' => 'is_url'],
        'facebook_link' => ['label' => 'Link do Facebooka','required' => false, 'validate' => 'is_url'],
        'instagram_link' => ['label' => 'Link do Instagrama','required' => false, 'validate' => 'is_url'],
    ];




    public function init_fields(){

        add_settings_field(
            'phone', // ID
            __($this->properties['phone']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_contact",
            array(
                'type' => 'tel',
                'value' => $this->get_property_value('phone'),
                'name' => 'phone',
                'id' => 'phone',
                'class_input' => 'regular-text',
            )
        );


        add_settings_field(
            'email', // ID
            __($this->properties['email']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_contact",
            array(
                'type' => 'email',
                'value' => $this->get_property_value('email'),
                'name' => 'email',
                'id' => 'email',
                'class_input' => 'regular-text',
            )
        );






        add_settings_field(
            'facebook_link', // ID
            __($this->properties['facebook_link']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_social_media",
            array(
                'type' => 'url',
                'value' => $this->get_property_value('facebook_link'),
                'name' => 'facebook_link',
                'id' => 'facebook_link',
                'class_input' => 'regular-text',
                'placeholder' => 'https://'
            )
        );

        add_settings_field(
            'instagram_link', // ID
            __($this->properties['instagram_link']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_social_media",
            array(
                'type' => 'url',
                'value' => $this->get_property_value('instagram_link'),
                'name' => 'instagram_link',
                'id' => 'instagram_link',
                'class_input' => 'regular-text',
                'placeholder' => 'https://'
            )
        );

        add_settings_field(
            'youtube_link', // ID
            __($this->properties['youtube_link']['label'], 'fotobudka'), // Title 
            array( $this, 'render_field' ), // Callback
            $this->get_id(),// Page
            "{$this->get_id()}_social_media",
            array(
                'type' => 'url',
                'value' => $this->get_property_value('youtube_link'),
                'name' => 'youtube_link',
                'id' => 'youtube_link',
                'class_input' => 'regular-text',
                'placeholder' => 'https://'
            )
        );

    }


}