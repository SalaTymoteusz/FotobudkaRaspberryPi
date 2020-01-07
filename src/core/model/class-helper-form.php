<?php
namespace WTM\Model;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Helper_Form 
{   

    protected static $input_types = array('text', 'hidden','number','email','tel','password','url');

    protected static $field_types_attributes = array(
        'file' => ['id', 'class', 'name', 'accept']
    );

    
    protected static $_instance = false;

    private $fields = array();
    
    public function __construct(array $fields = []){
        if(is_array($fields) && !empty($fields)){
            foreach($fields as $key => $field){
                $id = isset($field['id']) && !empty($field['id']) ? $field['id'] : "field_$key";
                $this->fields[$id] = $field;
            }
        }

        self::$_instance = $this;
    }


    public static function get_instance() {
        if ( !(self::$_instance instanceof self) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    
        

    
    public function render_field(array $field = []){
        if(!is_array($field) || empty($field)){
            return;
        }
        
        $type = isset($field['type']) && !empty($field['type']) ? strtolower($field['type']) : 'text';
        
        switch($type){
            case 'text':
            case 'tel':
            case 'email':
            case 'number':
            case 'password':
            case 'url':
            case 'hidden':
            $output = $this->render_text_field($field);
            break;

            case 'textarea':
            $output = $this->render_textarea_field($field);
            break; 

            case 'html':
            $output = $this->render_html($field);
            break; 
            
            case 'select':
            $output = $this->render_select_field($field);
            break;

            case 'editor':
            $output = $this->render_editor_field($field);
            break;

            case 'checkbox':
            $output = $this->render_checkbox_field($field);
            break;

            case 'radio':
            $output = $this->render_radio_field($field);
            break;

            case 'switch':
            $output = $this->render_switch_field($field);
            break;

            default:
            $output = '';
            break;
        }

        // elseif($type == 'file'){
        //     $output = $this->render_file_field($field);
        // }

        return $output;
    }



    public static function render_field_static(array $field = []){
        return self::get_instance()->render_field($field);
    }
    


    private function render_text_field(array $attr = [], $echo = false){
        return Template::get_template('_partials/helperform/input',[
            'return' => !$echo,
            'type' => isset($attr['type']) ? esc_attr($attr['type']) : 'text',
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'class' => isset($attr['class_input']) ? esc_attr($attr['class_input']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'maxlength' => isset($attr['maxlength']) ? esc_attr((int)$attr['maxlength']) : false,
            'readonly' => isset($attr['readonly']) ? esc_attr((bool)$attr['readonly']) : false,
            'disabled' => isset($attr['disabled']) ? esc_attr((bool)$attr['disabled']) : false,
            'size' => isset($attr['size']) ? esc_attr((int)$attr['size']) : false,
            'autocomplete' => isset($attr['autocomplete']) ? esc_attr($attr['autocomplete']) : false,
            'autofocus' => isset($attr['autofocus']) ? esc_attr($attr['autofocus']) : false,
            'placeholder' => isset($attr['placeholder']) ? esc_attr($attr['placeholder']) : false,
            'pattern' => isset($attr['pattern']) ? esc_attr($attr['pattern']) : false,
            'required' => isset($attr['required']) ? esc_attr((bool)$attr['required']) : false,
            'min' => isset($attr['min']) ? esc_attr((int)$attr['min']): false,
            'max' => isset($attr['max']) ? esc_attr((int)$attr['max']) : false,
            'step' => isset($attr['step']) ? esc_attr((int)$attr['step']) : false,
            'size' => isset($attr['size']) ? esc_attr((int)$attr['size']) : false,
            'height' => isset($attr['height']) ? esc_attr((int)$attr['height']) : false,
            'width' => isset($attr['width']) ? esc_attr((int)$attr['width']) : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);
            
    }



    private function render_textarea_field(array $attr = [], $echo = false){

        return Template::get_template('_partials/helperform/textarea',[
            'return' => !$echo,
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'class' => isset($attr['class_input']) ? esc_attr($attr['class_input']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'readonly' => isset($attr['readonly']) ? esc_attr((bool)$attr['readonly']) : false,
            'disabled' => isset($attr['disabled']) ? esc_attr((bool)$attr['disabled']) : false,
            'maxlength' => isset($attr['maxlength']) ? esc_attr((int)$attr['maxlength']) : false,
            'placeholder' => isset($attr['placeholder']) ? esc_attr($attr['placeholder']) : false,
            'required' => isset($attr['required']) ? esc_attr((bool)$attr['required']) : false,
            'rows' => isset($attr['rows']) ? esc_attr((int)$attr['rows']) : false,
            'wrap' => isset($attr['wrap']) ? esc_attr($attr['wrap']) : false,
            'height' => isset($attr['height']) ? esc_attr((int)$attr['height']) : false,
            'width' => isset($attr['width']) ? esc_attr((int)$attr['width']) : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);

    }



    
    private function render_editor_field(array $attr = []){
 
        $value = isset($attr['value']) ? $attr['value'] : '';

        return wp_editor( $value, $attr['id'], [
            'wpautop' => isset($attr['wpautop']) ? (bool)$attr['wpautop'] : true,
            'media_buttons' => isset($attr['media_buttons']) ? (bool)$attr['media_buttons'] : false,
            'default_editor' => isset($attr['default_editor']) ? $attr['default_editor'] : null,
            'drag_drop_upload' => isset($attr['drag_drop_upload']) ? (bool)$attr['drag_drop_upload'] : false,
            'textarea_name' => isset($attr['name']) ? $attr['name'] : false,
            'textarea_rows' => isset($attr['rows']) ? (int)$attr['rows'] : 20,
            'tabindex' => isset($attr['tabindex']) ? $attr['tabindex'] : null,
            'tabfocus_elements' => isset($attr['tabfocus_elements']) ? $attr['tabfocus_elements'] : false,
            'editor_class' => isset($attr['class_input']) ? $attr['class_input'] : false,
            'teeny' => isset($attr['teeny']) ? (bool)$attr['teeny'] : false,
            'dfw' => isset($attr['dfw']) ? (bool)$attr['dfw'] : false,
            'tinymce' => isset($attr['tinymce']) ? (bool)$attr['tinymce'] : true,
            'quicktags' => isset($attr['quicktags']) ? (bool)$attr['quicktags'] : true,
        ]);

    }



    
    private function render_select_field(array $attr = [], $echo = false){
        return Template::get_template('_partials/helperform/select',[
            'return' => !$echo,
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'class' => isset($attr['class_input']) ? esc_attr($attr['class_input']) : false,
            'disabled' => isset($attr['disabled']) ? esc_attr((bool)$attr['disabled']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'placeholder' => isset($attr['placeholder']) ? esc_attr($attr['placeholder']) : false,
            'readonly' => isset($attr['readonly']) ? esc_attr((bool)$attr['readonly']) : false,
            'required' => isset($attr['required']) ? esc_attr((bool)$attr['required']) : false,
            'autocomplete' => isset($attr['autocomplete']) ? esc_attr($attr['autocomplete']) : false,
            'autofocus' => isset($attr['autofocus']) ? esc_attr($attr['autofocus']) : false,
            'multiple' => isset($attr['multiple']) ? esc_attr((bool)$attr['multiple']) : false,
            'size' => isset($attr['size']) ? esc_attr((int)$attr['size']) : false,
            'options' => isset($attr['options']) ? (array)$attr['options'] : false,
            'height' => isset($attr['height']) ? esc_attr((int)$attr['height']) : false,
            'width' => isset($attr['width']) ? esc_attr((int)$attr['width']) : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);
    }




    private function render_checkbox_field(array $attr = [], $echo = false){
        return Template::get_template('_partials/helperform/checkbox',[
            'return' => !$echo,
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'options' => isset($attr['options']) ? (array)$attr['options'] : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);
    }


    private function render_radio_field(array $attr = [], $echo = false){
        return Template::get_template('_partials/helperform/radio',[
            'return' => !$echo,
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'options' => isset($attr['options']) ? (array)$attr['options'] : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);
    }


    private function render_switch_field(array $attr = [], $echo = false){
        return Template::get_template('_partials/helperform/switch',[
            'return' => !$echo,
            'id' => isset($attr['id']) ? esc_attr($attr['id']) : false,
            'name' => isset($attr['name']) ? esc_attr($attr['name']) : false,
            'value' => isset($attr['value']) ? esc_attr($attr['value']) : false,
            'options' => isset($attr['options']) ? (array)$attr['options'] : false,
            'desc' => isset($attr['desc']) ? esc_html($attr['desc']) : false,
        ]);
    }

    public function render_file_field(array $attr = array()){
        

        if(!isset($attr['name']) || empty($attr['name'])){
            $attr['name'] = $attr['id'];
        }

        $type = isset($attr['type']) && !empty($attr['type']) ? $attr['type'] : 'file';


        $output = '<label class="button-primary" type="button" for="'.$attr['id'].'">'.__('Wybierz plik','fotobudka').'</label>';
        
        $output .= '<div class="file-wrap">';
        $output .= '<input type="'.$type.'"';
        foreach ( $attr as $name => $value ) {

            if($name == 'class')
            continue;

            if($name == 'class_input')
            $name = 'class';

            if(!in_array($name, self::$field_types_attributes[$type]))
            continue;            

            $output .= " $name=" . '"' . $value . '"';
        }
        $output .= ' />';
        $output .= '<label class="file-label" for="'.$attr['id'].'"></label>';
        $output .= '</div>';
        $output .= '<button type="button" class="file-remove button" role="presentation" tabindex="-1" style="display:none">&times;</button>';
        $output .= '<script>';
        $output .= "jQuery('body').on('change', 'input[type=\"file\"][id=\"".$attr['id']."\"]', function() {
            var \$this = jQuery(this);

            var name = '';

            if(\$this[0].files.length > 1){
                name = 'Wybrano '+\$this[0].files.length+' pliki';
            }
            else if(\$this[0].files.length == 1){
                name = \$this[0].files.item(0).name;
            }
            \$this.siblings('.file-label').text(name);

            if(name != ''){
                \$this.parent('.file-wrap').siblings('.file-remove').show();
            }
            else{
                \$this.parent('.file-wrap').siblings('.file-remove').hide();
            }

          });      
          
          ";
        $output .= '</script>';

        
        if(isset($attr['desc']) && !empty($attr['desc'])){
            $output .= sprintf('<p class="description">%s</p>', $attr['desc']);
        }

        return $output;
       
    }


    public function render_html(array $attr = [], $echo = false){
        if(empty($attr['html']) || !isset($attr['html']))
        return;

        if($echo) echo $attr['html'];
        else return $attr['html'];
    }

}
