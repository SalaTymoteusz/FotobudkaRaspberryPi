<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 



?>
<svg class="wedge-top" preserveAspectRatio="none" aria-hidden="true" viewBox="0 0 487 83.98"><polygon fill="#fdfdfd" points="487 0 487 2.98 487 83.98 0.02 2.98 0 2.98 0 0 487 0"></polygon></svg>
<div class="row container">
    
    <div class="col-6 pt-5 slide-wrap">
        <figure>
            <?php  echo Image::get_attachment_image($section['image']['ID'],'front-large',[
                'class' => 'img-fluid',
                'preload' => false
            ]);?>
        </figure>
    </div>
    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
        <h2 class=""><?php echo $section['text'];?></h2>
    </div>
   
</div>
 <svg class="wedge-bottom" preserveAspectRatio="none" aria-hidden="true" viewBox="0 0 487 83.98"><polygon fill="#122022" points="0 0 0 2.98 0 83.98 486.98 2.98 487 2.98 487 0 0 0"></polygon></svg>