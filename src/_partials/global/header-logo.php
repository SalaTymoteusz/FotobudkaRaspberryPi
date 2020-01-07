<?php use WTM\Model\Validate; ?>

<?php global $site_name; ?>

<?php $custom_logo_id = get_theme_mod( 'custom_logo' ); ?>

<?php if(Validate::check_variable($custom_logo_id)): ?>
<?php $image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
<a href="<?php home_url(); ?>" title="<?php echo $site_name; ?>" class="navbar-brand">
    <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php echo $site_name; ?>">
</a>
<?php endif; ?>