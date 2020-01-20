<?php 
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

</main>

<footer id="footer" class="<?php if($post->ID == 105){echo 'sr-only';}?>">
	<svg aria-hidden="true" preserveAspectRatio="none" class="wedge" viewBox="0 0 755.84 48.75"><polygon fill="#080e0f" points="755.84 44.69 755.84 48.75 0 48.75 0 44.69 0.35 0 377.92 44.57 755.49 0 755.84 44.69"></polygon></svg>
	<div class="content-row container">
	<?php 
	$footer_logo = get_field('logo_footer','option');
	// echo '<pre>';
	// 	print_r($footer_logo);
	// echo '</pre>';
	?>
	<?php echo Image::get_attachment_image($footer_logo['ID'],'wtm-admin-thumbnail-mini',['class' => 'img-fluid', 'preload' => false]); ?>

</footer>
<?php wp_footer(); ?>

</body>

</html>