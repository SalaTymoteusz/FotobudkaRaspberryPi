<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset($map_key) || empty($map_key) ) return;

?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key; ?>"></script>