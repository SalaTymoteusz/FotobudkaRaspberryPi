<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 


$product = $section['the_product'];
$data = get_field('product_type', $product->ID);
$data = $data[0];
?>

<div class="row">
    <div class="col-lg-6 col-12 d-flex flex-column justify-content-center" style="<?php if($section['swap']==1){echo 'order: 1;';}else{echo 'order: 2;';}?>">
        <h4><?php echo $product->post_title; ?></h4>
        <p><?php echo substr(strip_tags($data['description']), 0, 300);?></p>
    </div>
    <figure class="col-lg-6 col-12" style="<?php if($section['swap']==1){echo 'order: 2;';}else{echo 'order: 1;';}?>">
        <?php echo Image::get_attachment_image(get_post_thumbnail_id($product->ID),'front-cover',[
                        'class' => 'img-fluid',
                        'preload' => false
                    ]); ?>
    </figure>
</div>