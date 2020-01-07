<?php

use WTM\Model\Validate;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<fieldset class="switch-field-wrap">
    <?php if(Validate::check_variable($options)):?>
    <?php foreach($options as $option): ?>
    <?php $input_id = sanitize_title("{$name}_{$option['value']}"); ?>
    <label for="<?php echo $input_id; ?>" class="button button-large<?php echo checked($value,$option['value']) ? ' button-primary' : '';?>" style="margin: .25em -.175em .5em!important; min-width: 80px; text-align: center;">
        <input type="radio" id="<?php echo esc_attr($input_id); ?>" value="<?php echo esc_attr($option['value']); ?>" name="<?php echo esc_attr($name); ?>" <?php checked($value,$option['value']); ?>
            <?php if(Validate::check_variable($option['required'])): ?> required <?php endif; ?> <?php if(Validate::check_variable($option['disabled'])): ?> disabled <?php endif; ?> <?php if(Validate::check_variable($option['readonly'])): ?> readonly
            <?php endif; ?> <?php if(Validate::check_variable($option['autocomplete'])): ?> autocomplete="<?php echo esc_attr($option['autocomplete']); ?>" <?php endif; ?> style="position: absolute; opacity: 0; visibility: hidden;" />
        <?php if(Validate::check_variable($option['label'])):?>
        <?php echo esc_html($option['label']); ?>
        <?php endif; ?>
    </label>
    <?php endforeach; ?>
    <?php endif; ?>
</fieldset>

<?php if(Validate::check_variable($desc)): ?>
<p class="description"><?php echo esc_html($desc); ?></p>
<?php endif; ?>
<script>
    jQuery(document).on('change', 'input[name="<?php echo $name; ?>"]', function () {
        var $input = jQuery(this);
        $input.closest('.switch-field-wrap').find('.button-primary').removeClass('button-primary');
        $input.parent().addClass('button-primary');
    })

</script>
