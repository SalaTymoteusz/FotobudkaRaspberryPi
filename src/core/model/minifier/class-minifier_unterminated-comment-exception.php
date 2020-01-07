<?php
namespace WTM\Model\Minifier;

if ( ! defined( 'ABSPATH' ) ) exit; 

use \Exception;

class Minifier_Unterminated_Comment_Exception extends Exception {}