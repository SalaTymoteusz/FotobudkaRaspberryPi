<?php

use WTM\Model\Validate;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<select <?php if(Validate::check_variable($id)): ?> id="<?php echo $id;?>" <?php endif; ?>
    <?php if(Validate::check_variable($class)): ?> class="<?php echo $class;?>" <?php endif; ?>
    <?php if(Validate::check_variable($name)): ?> name="<?php echo $name;?>" <?php endif; ?>
    <?php if(Validate::check_variable($disabled)): ?> disabled<?php endif; ?>
    <?php if(Validate::check_variable($placeholder)): ?> placeholder="<?php echo $placeholder;?>" <?php endif; ?>
    <?php if(Validate::check_variable($readonly)): ?> readonly<?php endif; ?>
    <?php if(Validate::check_variable($required)): ?> required <?php endif; ?>
    <?php if(Validate::check_variable($autocomplete)): ?> autocomplete="<?php echo $autocomplete;?>" <?php endif; ?>
    <?php if(Validate::check_variable($autofocus)): ?> autofocus="<?php echo $autofocus;?>" <?php endif; ?>
    <?php if(Validate::check_variable($multiple)): ?> multiple <?php endif; ?>
    <?php if(Validate::check_variable($size)): ?> size="<?php echo $size;?>" <?php endif; ?>
    <?php if(Validate::check_variable($height)): ?> height="<?php echo $height;?>" <?php endif; ?>
    <?php if(Validate::check_variable($width)): ?> width="<?php echo $width;?>" <?php endif; ?>>

    <?php if(Validate::check_variable($options)):?>

    <?php foreach($options as $val => $label): ?>
    <option value="<?php echo esc_attr($val); ?>" <?php selected($value,$val); ?>><?php echo esc_html($label); ?>
    </option>
    <?php endforeach; ?>
    <?php endif; ?>
</select>


<?php if(Validate::check_variable($desc)): ?>
<p class="description"><?php echo esc_html($desc); ?></p>
<?php endif; ?>