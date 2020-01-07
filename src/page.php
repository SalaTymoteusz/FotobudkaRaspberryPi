<?php 

use WTM\Model\Template;
get_header(); ?>

<?php //echo Template::get_template('_partials/title-banner.php'); ?>

<?php
$sections = get_field('section_element');

if(!empty($sections)){
	foreach($sections as $section){
		Template::get_template("_partials/sections/sections.php",['section' => $section]);
	}
}
?>

<?php get_footer(); ?>