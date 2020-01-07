<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$facebook = get_option('facebook_link');
$instagram = get_option('instagram_link');
$youtube = get_option('youtube_link');

if( !$facebook && !$instagram && !$youtube )
return;
?>
<div class="navbar-social-media">

    <?php if($facebook): ?>
    <a id="navbar-facebook" href="<?php echo $facebook; ?>" target="_blank" rel="noopener"
        title="<?php _e('Link do Facebooka','fotobudka');?>" aria-label="<?php _e('Link do Facebooka','fotobudka');?>">
        <i class="fab fa-fw fa-facebook-square"></i>
    </a>
    <?php endif; ?>

    <?php if($instagram): ?>
    <a id="navbar-instagram" href="<?php echo $instagram; ?>" target="_blank" rel="noopener"
        title="<?php _e('Link do Instagrama','fotobudka');?>" aria-label="<?php _e('Link do Instagrama','fotobudka');?>">
        <i class="fab fa-fw fa-instagram"></i>
    </a>
    <?php endif; ?>

    <?php if($youtube): ?>
    <a id="navbar-youtube" href="<?php echo $youtube; ?>" target="_blank" rel="noopener"
        title="<?php _e('Link do YouTuba','fotobudka');?>" aria-label="<?php _e('Link do YouTuba','fotobudka');?>">
        <i class="fab fa-fw fa-youtube"></i>
    </a>
    <?php endif; ?>


</div>