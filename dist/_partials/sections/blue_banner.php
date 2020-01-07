<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
?>

<div class="banner blue-banner">
    <?php echo $section['text'];?>
    <?php $link = $section['optional_link'];?>
    <a href="<?php echo $link['url'];?>" target="<?php echo $link['target'];?>" rel="<?php if(isset($link['target'])){echo 'noopenner noreferrer';} ?>"><?php echo $link['title'] ?></a>
</div>