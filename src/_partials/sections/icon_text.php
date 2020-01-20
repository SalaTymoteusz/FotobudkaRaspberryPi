<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 

?>




<div class="row">
<?php foreach($section['icons'] as $icon){?>
 
   <div class="col-lg-3 col-md-4 col-sm-6 col-12 icon-column">
    <figure class="w-100">
        <?php
                echo Image::get_attachment_image($icon['icon']['ID'],'wtm-admin-thumbnail-mini',[
                    'class' => 'img-fluid',
                    'preload' => false
                ]); ?>
        </figure>
        <p class="w-100">
                <?php echo $icon['text']; ?>
        </p>
   </div>

    <?php } ?>
    </div> 

        
