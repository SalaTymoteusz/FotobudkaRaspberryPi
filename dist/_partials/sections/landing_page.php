<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 



?>

<div class="row">
    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
        <h2 class=""><?php echo $section['text'];?></h2>
    </div>
    <div class="col-6 pt-5 slide-wrap">
        <div class="background-slide" style="background-image: url(<?php echo $section['image']['sizes']['large']; ?>);">
        
        </div>
    </div>
</div>