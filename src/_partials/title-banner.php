<?php 
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php if(is_front_page()){?>
    <?php 
    $image = get_field('front_page_banner_image','option'); 
    $title = get_field('front_page_banner_title');
    $links = get_field('front_page_banner_buttons');

    ?>
    
    <?php if(isset($image)) {?>
    <div class="banner banner-<?php echo 'front-page';?>" style="background-image: url('<?php echo wp_get_attachment_image_url($image['ID'], 'full', $icon)?>')">
        <?php if(isset($title)){echo $title;} ?>
        <?php 
        if(isset($links)){?>
            <div class="d-flex flex-row flex-nowrap">
            <?php foreach($links as $link){
                ?>
                <a href="<?php echo $link['button_url']['url'];?>" target="<?php echo $link['button_url']['target'];?>" rel="<?php if(isset($link['button_url']['target'])){echo 'noopenner noreferrer';} ?>"><?php echo $link['button_url']['title'] ?></a>
            <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php }?>
<?php }else if(is_archive()){?>



<?php }else{ ?>



<?php } ?>