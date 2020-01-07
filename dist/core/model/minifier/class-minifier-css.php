<?php
namespace WTM\Model\Minifier;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Minifier_CSS {

    protected $input = '';
    
    public static function minify($js)
    {
        $jsmin = new self($js);
        return $jsmin->min();
    }

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function min()
    {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', ' ', $this->input);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $css);
        $css = str_replace(
             array(';}', ' {', '} ', ': ', ' !', ', ', ' >', '> '),
             array('}',  '{',  '}',  ':',  '!',  ',',  '>',  '>'), $css);
        
        // url
        // $dir = dirname($path).'/';
        $dir = '/';
        $css = preg_replace('|url\(\'?"?(([\s])*[a-zA-Z0-9=\?\&\-_\.]+[a-zA-Z0-9=\?\&\-_\s\./]*)\'?"?\)|', "url(\"$dir$1\")", $css);

        return $css;
    }
}