<?php

// static content images
function wp_img( $filename, $args = array() ) {
	$default_args = array( 'alt' => '', 'class' => '' );
	
	if( ! is_array( $args ) )
		$args = $default_args;
	
	$args = array_merge( $default_args, $args );
	
	$alt = empty( $args[ 'alt' ] ) ? '': sprintf( ' alt="%s"', htmlentities( $args[ 'alt' ] ) );
	$class = empty( $args[ 'class' ] ) ? '': sprintf( ' class="%s"', htmlentities( $args[ 'class' ] ) );
	
	printf( '<img src="%s%s"%s%s />', WP_IMG, $filename, $alt, $class );
}

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

function current_user_can( $capability ) {
	// to-do
	// lets assume the user is not logged in
	return false;
}

function trailingslashit( $url ) {
	if( substr( $url, -1 ) != '/' )
		$url .= '/';
	
	return $url;
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

