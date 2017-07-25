<?php

add_action( 'wp_head', function() {
	do_action( 'wp_enqueue_scripts' );
	do_action( 'print_enqueued_scripts' );
} );
add_action( 'wp_footer', function() {
	do_action( 'wp_enqueue_scripts' );
	do_action( 'print_enqueued_scripts' );
} );
add_action( 'print_enqueued_scripts', 'print_enqueued_scripts' );

function print_enqueued_scripts() {
	if( isset( $GLOBALS[ 'SCRIPTS' ] ) && ! empty( $GLOBALS[ 'SCRIPTS' ] ) ) {
		$count = count( $GLOBALS[ 'SCRIPTS' ] );
		
		foreach( $GLOBALS[ 'SCRIPTS' ] as $k => $s ) {			
			if( isset( $s[ 'loaded' ] ) && $s[ 'loaded' ] ):
				continue;
			else:
				$GLOBALS[ 'SCRIPTS' ][ $k ][ 'loaded' ] = true;
			endif;
			
			if( $s[ 'type'] == 'js' )
				printf( '<script src="%s" id="%s"></script>', $s[ 'href'], $s[ 'id' ] );
			else if( $s[ 'type' ] == 'css' )
				printf( '<link href="%s" id="%s" rel="stylesheet" />', $s[ 'href'], $s[ 'id' ] );
		}
	}
}

function wp_enqueue_script( $id, $href = "" ) {
	if( empty( $GLOBALS[ 'SCRIPTS' ] ) )
		$GLOBALS[ 'SCRIPTS' ] = array();
	
	if( isset( $GLOBALS[ 'SCRIPTS' ][ $id ] ) ) // wp throws an error here
		return false;
	
	$GLOBALS[ 'SCRIPTS' ][ $id ] = array( 'id' => $id, 'href' => $href, 'type' => 'js' );
}

function wp_register_script( $id, $href ) {
	if( empty( $GLOBALS[ 'REGISTERED_SCRIPTS' ] ) )
		$GLOBALS[ 'REGISTERED_SCRIPTS' ] = array();
	
	$GLOBALS[ 'REGISTERED_SCRIPTS' ][ $id ] = array( 'id' => $id, 'href' => $href, 'type' => 'js' );
}

function wp_deregister_script( $id ) {
	// to-do
	if( isset( $GLOBALS[ 'SCRIPTS' ][ $id ] ) ) {
		unset( $GLOBALS[ 'SCRIPTS' ][ $id ] );
		return true;
	} else {
		return false;
	}
}

function wp_enqueue_style( $id, $href = "" ) {
	if( empty( $GLOBALS[ 'SCRIPTS' ] ) )
		$GLOBALS[ 'SCRIPTS' ] = array();
	
	if( isset( $GLOBALS[ 'SCRIPTS' ][ $id ] ) ) // wp throws an error here
		return false;
	
	$GLOBALS[ 'SCRIPTS' ][ $id ] = array( 'id' => $id, 'href' => $href, 'type' => 'css' );
}

function wp_register_style( $id, $href ) {
	if( empty( $GLOBALS[ 'REGISTERED_SCRIPTS' ] ) )
		$GLOBALS[ 'REGISTERED_SCRIPTS' ] = array();
	
	$GLOBALS[ 'REGISTERED_SCRIPTS' ][ $id ] = array( 'id' => $id, 'href' => $href, 'type' => 'css' );
}

function wp_deregister_style( $id ) {
	// to-do
	return wp_deregister_script( $id );
}

function wp_style_add_data() {
	// to-do
}

function wp_localize_script() {
	// to-do
}

function wp_add_inline_style() {
	// to-do
}



