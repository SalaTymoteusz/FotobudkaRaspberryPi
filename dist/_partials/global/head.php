<?php
global $site_name;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<meta name="robots" content="<?php echo $index; ?>" />
<meta name="googlebot" content="<?php echo $index; ?>" />

<meta name="keywords" content='<?php echo $keywords; ?>' />
<meta name="description" content="<?php echo $description; ?>" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:locale" content="<?php echo $locale; ?>" />
<meta property="og:type" content='website' />

<?php if(isset($image) && !empty($image)):?>
<meta property="og:image" content="<?php echo $image[0]; ?>" />
<meta property='og:image:width' content="<?php echo $image[1]; ?>" />
<meta property='og:image:height' content="<?php echo $image[2]; ?>" />
<?php endif; ?>

<meta property="og:site_name" content="<?php echo $site_name; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />

<meta name='twitter:title' content="<?php echo $title; ?>" />
<meta name="twitter:description" content="<?php echo $description; ?>" />

<link rel="preconnect" href="//use.fontawesome.com">
<link rel="preconnect" href="//cdnjs.cloudflare.com">