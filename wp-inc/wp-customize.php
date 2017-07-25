<?php


function get_theme_mod( $id, $default = '' ) {
	// to-do
	return $default; // since we are not storing anything
}

function get_background_color() {
	// to-do
	return apply_filters( 'get_background_color', get_theme_mod( 'background_color', '#FFF' ) );
}

function get_background_image() {
	// to-do
	return apply_filters( 'get_background_image', get_theme_mod( 'background_image', '' ) );
}

function get_custom_header() {
	$data = array( 'url' => '', 'thumbnail_url' => '', 'width' => '', 'height' => '' );
	
	return (object) $data;
}

// the customize_register hook is never called, so we shouldn't really
//  be concerned about the WP_Customize_Manager class and all its associates

// class WP_Customize_Manager {}
