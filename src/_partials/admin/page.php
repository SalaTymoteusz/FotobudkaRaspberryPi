<?php
use \WTM\Model\Tools;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!is_admin()){
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <br class="clear" />

    <?php do_action('wtm_do_settings_sections_nav'); ?>

    <?php
    $form_attr = apply_filters('wtm_admin_form_attributes', [
        'method' => 'post',
        'action' => 'admin-post.php',
        'novalidate' => 'novalidate',
    ]);
    $form_attr = implode(' ', array_map(
        function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
        $form_attr,
        array_keys($form_attr)
    ));
    ?>
    <form <?php echo $form_attr; ?>>

        <?php do_settings_sections( Tools::get_value('page') ); ?>

        <?php submit_button(); ?>

        <?php wp_nonce_field('wtm_save_admin'); ?>

        <input type="hidden" name="page" value="<?php echo Tools::get_value('page');?>">
        <input type="hidden" name="action" value="wtm_save_admin">
    </form>
</div>