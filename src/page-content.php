
<?php
/*
Template Name: Page - Content
*/
 get_header(); ?>

<?php

if(isset($post->post_content)){?>
	<div class="container">
		<?php echo $post->post_content; ?>
	</div>
<?php 
}?>

<?php get_footer(); ?>