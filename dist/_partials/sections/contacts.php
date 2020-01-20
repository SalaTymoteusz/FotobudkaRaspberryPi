<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 

?>


<pre><?php print_r($section); ?></pre>

<div class="row">
<?php foreach($section['contact'] as $contact){ ?>

<div class="contact-column col-lg-3 col-md-4 col-sm-6 col-12">
    <?php  if(Validate::check_variable($contact['image'])){?>
        <figure class="">
            <?php  echo Image::get_attachment_image($contact['image']['ID'],'wtm-admin-thumbnail-mini',[
                'class' => 'img-fluid',
                'preload' => false
            ]);?>
        </figure>
    <?php }  ?>
    <?php if(Validate::check_variable($contact['person'])){ ?>
        <p class="">
            <?php echo $contact['person'];?>
        </p>
    <?php } ?>
    <?php if(Validate::check_variable($contact['information'])){ ?>
        <p class="">
            <?php echo $contact['information'];?>
        </p>
    <?php } ?>
    <?php if(Validate::check_variable($contact['email'])){ ?>
        <p class="">
            <?php echo $contact['email'];?>
        </p>
    <?php } ?>
    <?php if(Validate::check_variable($contact['phone'])){ ?>
        <p class="">
            <?php echo $contact['phone'];?>
        </p>
    <?php } ?>
    <?php if(Validate::check_variable($contact['address'])){ ?>
        <p class="">
            <?php echo $contact['address'];?>
        </p>
    <?php } ?>
   
</div>

<?php } ?>
</div> 

        
