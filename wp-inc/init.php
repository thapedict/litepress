<?php

// wp-inc/init.php

// IT_ALL_STARTS_HERE

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
define( 'HOME_URL','http://' . $_SERVER['HTTP_HOST'] . HOME_PARTIAL );

// to save static 'the_content' files
define( 'WP_INC_CONTENT', WP_INC . 'content/' );

// to save static widgets
define( 'WP_INC_WIDGET', WP_INC . 'widget/' );

// json (data) files
define( 'WP_INC_DATA', WP_INC . 'data/' );

// images generally used in static the_content files
define( 'WP_IMG', HOME_URL . "wp-inc/img/" );

// just incase we need the version
$wp_version = "4.5.1";
define( 'WP_VERSION', $wp_version );

// load core functions
require_once 'functions.php';

// some misc functions
require_once 'tdt.php';
require_once 'tdt_debug.php';

do_action( 'setup_theme' );

//-----------------------------------//
//-----------------------------------//
// load some shortcodes
// I should really delete the following
	require_once 'shortcodes.php';
	require_once 'hijacking.php';
//-----------------------------------//
//-----------------------------------//

// load theme's functions file
if( is_readable( HOME_DIR . '/functions.php' ) )
	require_once HOME_DIR . '/functions.php';

do_action( 'after_theme_setup' );

// template functions
require_once 'wp-template.php';
require_once 'wp-query.php';
require_once 'wp-localization.php';
require_once 'wp-comments.php';
require_once 'wp-posts.php';
require_once 'wp-conditional-tags.php';
require_once 'wp-widgets.php';
require_once 'wp-enqueue-scripts.php';


// let everyone know we are ready
do_action( 'init' );

do_action( 'template_redirect' );

// then load template
require_once get_query_template();

