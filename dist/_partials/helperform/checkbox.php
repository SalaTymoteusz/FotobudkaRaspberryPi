<?php

use WTM\Model\Validate;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<fieldset>
    <?php if(Validate::check_variable($options)):?>
    <?php $multiple = count($options) > 1 ? true : false; ?>
    <?php foreach($options as $option): ?>
    <?php $input_id = sanitize_title("{$name}_{$option['value']}"); ?>
    <?php $input_name = $multiple ? "{$name}[]" : $name; ?>
    <label for="<?php echo $input_id; ?>">
        <input type="checkbox" id="<?php echo esc_attr($input_id); ?>" value="<?php echo esc_attr($option['value']); ?>"
            name="<?php echo esc_attr($input_name); ?>" <?php checked($value,$option['value']); ?>
            <?php if(Validate::check_variable($option['required'])): ?> required <?php endif; ?>
            <?php if(Validate::check_variable($option['disabled'])): ?> disabled <?php endif; ?>
            <?php if(Validate::check_variable($option['readonly'])): ?> readonly <?php endif; ?>
            <?php if(Validate::check_variable($option['autocomplete'])): ?>
            autocomplete="<?php echo esc_attr($option['autocomplete']); ?>" <?php endif; ?> />
        <?php if(Validate::check_variable($option['label'])):?>
        <?php echo esc_html($option['label']); ?>
        <?php endif; ?>
    </label>
    <?php if(Validate::check_variable($option['desc'])): ?>
    <p class="description"><?php echo esc_html($option['desc']); ?></p>
    <?php endif; ?>
    <br>
    <?php endforeach; ?>
    <?php endif; ?>
</fieldset>

<?php if(Validate::check_variable($desc)): ?>
<p class="description"><?php echo esc_html($desc); ?></p>
<?php endif; ?>