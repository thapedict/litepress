<?php

// localization
function __( $string ) {
	return $string;
}

function _e( $string ) {
	print $string;
}

function _x( $string ) {
	return $string;
}

function _n( $s, $p, $n ) {
	if( $n > 1 )
		return $p;
	else
		return $s;
}

function load_textdomain() {
	// to-do
}

function load_theme_textdomain() {
	// to-do
}

function esc_attr_e( $str ) {
	_e( htmlentities( $str ) );
}

function esc_html( $str ) {
	return $str;
}

function esc_url( $url ) {
	return $url; 
}

function esc_attr( $attr ) {
	return htmlentities( $attr ); // i think
}

function number_format_i18n( $n ) {
	return $n;
}

