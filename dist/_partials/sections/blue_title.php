<?php

use WTM\Model\Validate;
if ( ! defined( 'ABSPATH' ) ) exit; ?>

<h2 class="blue-title">
    <?php if(Validate::check_variable($section['text'])){
            echo $section['text'];
        }?>
</h2>
