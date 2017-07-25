<?php

/*
 *	consistency is key, and I don't have time to be second guessing what the directory separator is.
 *	so we set all paths to use forward slashes.....sorry windows
 */
 
// the wp-inc folder
define( 'WP_INC', str_replace( '\\', '/', __DIR__ . '/' ) );
 
// path to the theme folder
define( 'HOME_DIR', str_replace( '\\', '/', dirname( __DIR__ ) ) );

// document root with forward slashes
define( 'DOCUMENT_ROOT', str_replace( '\\', '/', $_SERVER[ 'DOCUMENT_ROOT' ] ) );

//home path without document root
define( 'HOME_PARTIAL',  '/' . str_replace( DOCUMENT_ROOT, '', HOME_DIR . '/' ) );

// the home url ()
$scheme = isset( $_SERVER[ 'HTTPS' ] ) ? 'https': 'http';
define( 'HOME_URL', $scheme . '://' . $_SERVER['HTTP_HOST'] . HOME_PARTIAL );

// to save static 'the_content' files
define( 'WP_INC_CONTENT', WP_INC . 'content/' );

// json (data) files
define( 'WP_INC_DATA', WP_INC . 'data/' );

// images generally used in static the_content files
define( 'WP_IMG', HOME_URL . "wp-inc/img/" );

// just incase we need the version
$wp_version = "4.5.1";
define( 'WP_VERSION', $wp_version );

// to save all those action hooks
$ACTIONS = array();

// to save all filter hooks
$FILTERS = array();

// and then our shortcodes
$SHORTCODES = array();




