<?php

// some core functions

function add_action( $action_name, $callback ) {
	if( ! isset( $GLOBALS[ 'ACTIONS' ] ) )
		$GLOBALS[ 'ACTIONS' ] = array();
	
	if( ! isset( $GLOBALS[ 'ACTIONS' ][ $action_name ] ) )
		$GLOBALS[ 'ACTIONS' ][ $action_name ] = array();
	
	$GLOBALS[ 'ACTIONS' ][ $action_name ][] = $callback;
}

function do_action( $action_name ) {
	$parameters = func_get_args();
	$parameters = array_slice( $parameters, 1 );
	
	if( isset( $GLOBALS[ 'ACTIONS' ][ $action_name ] ) && ! empty( $GLOBALS[ 'ACTIONS' ][ $action_name ] ) ) {
		foreach( $GLOBALS[ 'ACTIONS' ][ $action_name ] as $callback )
			call_user_func_array( $callback, $parameters );
	}
}

function add_filter( $filter_name, $callback ) {
	if( ! isset( $GLOBALS[ 'FILTERS' ] ) )
		$GLOBALS[ 'FILTERS' ] = array();
	
	if( ! isset( $GLOBALS[ 'FILTERS' ][ $filter_name ] ) )
		$GLOBALS[ 'FILTERS' ][ $filter_name ] = array();
	
	$GLOBALS[ 'FILTERS' ][ $filter_name ][] = $callback;
}

function apply_filters( $filter_name, $return_value ) {
	$other_args = array_slice( func_get_args(), 2 );
		
	if( isset( $GLOBALS[ 'FILTERS' ][ $filter_name ] ) && ! empty( $GLOBALS[ 'FILTERS' ][ $filter_name ] ) ) {
		foreach( $GLOBALS[ 'FILTERS' ][ $filter_name ] as $callback ) {
			$parameters = array_merge( (array) $return_value, $other_args );
			$return_value = call_user_func_array( $callback, $parameters );
		}
	}
	
	return $return_value;
}

function add_shortcode( $name, $handler ) {
	if( ! isset( $GLOBALS[ 'SHORTCODES' ] ) )
		$GLOBALS[ 'SHORTCODES' ] = array();
	
	$GLOBALS[ 'SHORTCODES' ][] = array( 'name' => $name, 'handler' => $handler );
}

function has_shortcodes( $string ) {
	if( ! isset( $GLOBALS[ 'SHORTCODES' ] ) )
		return false;
	
	if( empty( $GLOBALS[ 'SHORTCODES' ] ) ) 
		return false;
	
	$matched_shortcodes = array();
	foreach( $GLOBALS[ 'SHORTCODES' ] as $s ) {
		// what we expect: 
		//		1. [shortcode]
		//		2. [shortcode attr]
		//		3. [shortcode]some content here[/shortcode]
		//		4. [shortcode attr]some content here[/shortcode]
		$preg = "(?<shortcode>\[{$s['name']}(?:\s)?(?<attr>[^\]]+)?\](?:(?<content>.*)?\[/{$s['name']}\])?)";
		
		if( preg_match_all("#{$preg}#i", $string, $preg_matches ) ) {
			$count = count( $preg_matches[ 'shortcode' ] );
			
			for( $x = 0; $x < $count; $x++ ) {
				$array = array( 'shortcode' => $preg_matches[ 'shortcode' ][ $x ], 'attr' => $preg_matches[ 'attr' ][ $x ], 'content' => $preg_matches[ 'content' ][ $x ] );
				$matched_shortcodes[] = array_merge( $s, $array );				
			}
		}
	}
	
	if( $matched_shortcodes ) {
		$matched_shortcodes = tdt_array_unique( $matched_shortcodes );
	}
	
	return $matched_shortcodes;
}

function do_shortcode( $string ) {
	$shortcodes = has_shortcodes( $string );
	
	if( $shortcodes ) {
		foreach( $shortcodes as $s ) {
			$attr = '';
			
			if( ! empty( $s[ 'attr' ] ) )
				$attr = get_attr( $s[ 'attr' ] );
			
			if( $s[ 'content' ] )
				$s[ 'content' ] = do_shortcode( $s[ 'content' ] );
				
			$parameters =  array( $attr, $s[ 'content' ], $s[ 'name' ] );
			
			$replacement = call_user_func_array( $s[ 'handler' ], $parameters );
			
			$string = str_replace( $s[ 'shortcode' ], $replacement, $string );
		}		
	}
	
	return $string;
}
add_filter( 'the_content', 'do_shortcode' );

// so do_shortcode can be readable
function get_attr( $string ) {
	if( empty( $string ) )
		return '';
	
	// what we expect:
	//		1. key="some value"
	//		2. key=somevalue
	$preg = '(?:(?<key>[\w-]+)(?:=(?<value>(?:")[^"]*(?:"))))|(?:(?<k>[\w-]+)(?:=(?<v>[\w-]+)))';
	
	$total = preg_match_all( "#{$preg}#i", $string, $matches );
	
	if( $total ) {
		// $matches['value'] includes open and closing quotes so lets remove them
		array_walk( $matches[ 'value' ], function( &$v ){
											$v = substr( $v, 1, strlen( $v ) - 2 );
										}
			);
		$keys = array_merge( $matches[ 'key' ], $matches[ 'k' ] );
		$values = array_merge( $matches[ 'value' ], $matches[ 'v' ] );
		$key_value = array_combine( $keys, $values );
		
		// array_filter removes elements with '0' and we might need that, so lets implement our own
		$filtered = array();
		foreach($key_value as $k => $v) {
			if( $k && $v !== '' )
				$filtered[ $k ] = $v;
		}
		
		return $filtered;
	} else {
		return '';
	}
}

function current_user_can( $capability ) {
	// to-do
	// lets assume the user is not logged in
	return false;
}

function get_url() {
	if( isset( $_GET[ 'url' ] ) && ! empty( $_GET[ 'url' ] ) ) {
		$url = $_GET[ 'url' ];
		
		// remove trailing slash
		if( substr( $url, -1 ) == '/' )
			$url = substr( $url, 0, -1 );
	} else {
		$url = '/';
	}
	
	return $url;
}

// static content images
function wp_img( $name, $alt = '' ) {
	printf( '<img src="%s%s" alt="%s" />', WP_IMG, $name, $alt );
}

// I don't know what wordpress gets up to here...??
function init() {
	// to-do
	$GLOBALS[ 'POSTS' ] =  array();
	$GLOBALS[ 'paged' ] = 0;
	
	// is_archive covers any type (category, date, search ... etc)
	// don't have time to be filtering posts here // to-do
	if( is_home() || is_archive() || is_search() )
		$GLOBALS[ 'POSTS' ] = get_posts();	
	
	if( is_single() )
		$GLOBALS[ 'POSTS' ][] = get_post();
	
	if( is_page() ) {
		$GLOBALS[ 'POSTS' ][] = get_page();
	}
}
add_action( 'init', 'init' );

// mysql is nice and all, but lets json things for now
function get_json( $file ) {
	if( strpos( $file, HOME_DIR ) !== 0 ) {
		if( ! file_exists( $file ) ) {
			if( substr( $file, -5 ) !== '.json' )
				$file .= '.json';
			
			$file = WP_INC_DATA . $file;
		}
	}
	
	if( is_readable( $file ) ) {
		$json = json_decode( file_get_contents( $file ) );
		
		if( json_last_error() )
			trigger_error( "function get_json -- JSON Error: " . json_last_error() , E_USER_WARNING );
		else
			return $json;
		
	} else {
		trigger_error( "function get_json -- File Does Not Exist: {$file}", E_USER_WARNING );
	}
}

function get_option( $name, $default = null ) {
	$options = get_json( 'options' );
	
	if( isset( $options->$name ) ) {
		return $options->$name;		
	} else {		
		return $default;
	}
}

// bloginfo stuff
function get_bloginfo( $property ) {
	// to-do
	if( $property == 'url' )
		return home_url();
	
	if( $property == 'charset' )
		return 'utf-8';
	
	// what else?
	
	return get_option( $property );
}

function bloginfo( $property ) {
	print get_bloginfo( $property );
}

// error prevention
// why bother?
function load_theme_textdomain() {}

function add_editor_style() {}

function add_theme_support() {}

function register_nav_menu() {}



