<?php

namespace WTM\Model;

if ( ! defined( 'ABSPATH' ) ) exit; 


class Validate {

    public static function is_email($email)
    {
        return !empty($email) && preg_match(Tools::clean_non_unicode_support('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui'), $email);
    }


    public static function is_phone_number($number)
    {
        return preg_match('/^[+0-9. ()-]*$/', $number);
    }

    public static function check_variable($string){
        return isset($string) && !empty($string);
    }

    public static function is_url($url){
        return wp_http_validate_url( $url ) ;
    }
}