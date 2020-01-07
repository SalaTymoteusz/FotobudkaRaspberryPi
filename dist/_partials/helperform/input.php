<?php

use WTM\Model\Validate;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset($type) || empty($type) ) return;

?>

<input type="<?php echo $type; ?>" <?php if(Validate::check_variable($id)): ?> id="<?php echo $id;?>" <?php endif; ?>
    <?php if(Validate::check_variable($class)): ?> class="<?php echo $class;?>" <?php endif; ?>
    <?php if(Validate::check_variable($name)): ?> name="<?php echo $name;?>" <?php endif; ?>
    <?php if(Validate::check_variable($value)): ?> value="<?php echo $value;?>" <?php endif; ?>
    <?php if(Validate::check_variable($maxlength)): ?> maxlength="<?php echo $maxlength;?>" <?php endif; ?>
    <?php if(Validate::check_variable($readonly)): ?> readonly<?php endif; ?>
    <?php if(Validate::check_variable($disabled)): ?> disabled<?php endif; ?>
    <?php if(Validate::check_variable($size)): ?> size="<?php echo $size;?>" <?php endif; ?>
    <?php if(Validate::check_variable($autocomplete)): ?> autocomplete="<?php echo $autocomplete;?>" <?php endif; ?>
    <?php if(Validate::check_variable($autofocus)): ?> autofocus="<?php echo $autofocus;?>" <?php endif; ?>
    <?php if(Validate::check_variable($placeholder)): ?> placeholder="<?php echo $placeholder;?>" <?php endif; ?>
    <?php if(Validate::check_variable($pattern)): ?> pattern="<?php echo $pattern;?>" <?php endif; ?>
    <?php if(Validate::check_variable($required)): ?> required <?php endif; ?>
    <?php if(Validate::check_variable($min)): ?> min="<?php echo $min;?>" <?php endif; ?>
    <?php if(Validate::check_variable($max)): ?> max="<?php echo $max;?>" <?php endif; ?>
    <?php if(Validate::check_variable($step)): ?> step="<?php echo $step;?>" <?php endif; ?>
    <?php if(Validate::check_variable($size)): ?> size="<?php echo $size;?>" <?php endif; ?>
    <?php if(Validate::check_variable($height)): ?> height="<?php echo $height;?>" <?php endif; ?>
    <?php if(Validate::check_variable($width)): ?> width="<?php echo $width;?>" <?php endif; ?> />


<?php if(Validate::check_variable($desc)): ?>
<p class="description"><?php echo $desc; ?></p>
<?php endif; ?>