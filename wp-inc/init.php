<?php

// wp-inc/init.php

// some misc functions
require_once 'tdt.php';
// require_once 'tdt_debug.php';

// load some constant and global vars
require_once 'globals.php';

// wp functions
require_once 'wp-hooks.php'; // load action and filter functions
require_once 'wp-query.php';
require_once 'wp-conditional-tags.php';
require_once 'wp-enqueue-scripts.php';
require_once 'wp-template.php';
require_once 'wp-localization.php';
require_once 'wp-customize.php';
require_once 'wp-posts.php';
require_once 'wp-shortcodes.php';
require_once 'wp-comments.php';
require_once 'wp-widgets.php';
require_once 'other-funcs.php';

do_action( 'setup_theme' );

// load theme's functions file
if( is_readable( HOME_DIR . '/functions.php' ) )
	require_once HOME_DIR . '/functions.php';

do_action( 'after_setup_theme' );


//-----------------------------------//
//-----------------------------------//
// alternative to home-widgets plugin
	require_once 'tdt-home-widgets.php';
// load some posts, shortcodes and widgets
	require_once 'hijacking.php';
//-----------------------------------//
//-----------------------------------//

// let everyone know we are ready
do_action( 'init' );
// other hooks
do_action( 'widgets_init' );
do_action( 'template_redirect' );

// then load template
require_once get_query_template();

