<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!is_admin()){
    exit;
}

if ( ! isset($alerts) || empty($alerts) ) return;
?>


<?php foreach($alerts as $alert)?>
<?php if(is_wp_error($alert)): ?>
<div class="error notice is-dismissible">
    <p><?php echo wp_kses_post( $alert->get_error_message() ); ?></p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php _e('Ukryj tę informację.','fotobudka'); ?></span>
    </button>
</div>
<?php else: ?>
<div class="updated notice notice-success is-dismissible">
    <p><?php echo $alert ;?></p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php _e('Ukryj tę informację.','fotobudka'); ?></span>
    </button>
</div>
<?php endif; ?>