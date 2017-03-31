<?php

function wp_enqueued_scripts() {
	if( isset( $GLOBALS[ 'SCRIPTS' ] ) && ! empty( $GLOBALS[ 'SCRIPTS' ] ) ) {
		$count = count( $GLOBALS[ 'SCRIPTS' ] );
		
		for( $x = 0; $x < $count; $x++ ) {
			$s = $GLOBALS[ 'SCRIPTS' ][ $x ];
			
			if( isset( $s[ 'loaded' ] ) && ! empty( $s[ 'loaded' ] ) )
				continue;
			else
				$GLOBALS[ 'SCRIPTS' ][ $x ][ 'loaded' ] = true;
			
			if( $s[ 'type'] == 'js' )
				printf( '<script src="%s" id="%s"></script>', $s[ 'href'], $s[ 'id' ] );
			else if( $s[ 'type' ] == 'css' )
				printf( '<link href="%s" id="%s" rel="stylesheet" />', $s[ 'href'], $s[ 'id' ] );
		}
	}
}
add_action( 'wp_enqueued_scripts', 'wp_enqueued_scripts' );

function wp_enqueue_script( $id, $href = "" ) {
	if( empty( $GLOBALS[ 'SCRIPTS' ] ) )
		$GLOBALS[ 'SCRIPTS' ] = array();
	
	$GLOBALS[ 'SCRIPTS' ][] = array( 'id' => $id, 'href' => $href, 'type' => 'js' );
}

function wp_register_script( $id, $href ) {
	if( empty( $GLOBALS[ 'REGISTERED_SCRIPTS' ] ) )
		$GLOBALS[ 'REGISTERED_SCRIPTS' ] = array();
	
	$GLOBALS[ 'REGISTERED_SCRIPTS' ][] = array( 'id' => $id, 'href' => $href, 'type' => 'js' );
}

function wp_deregister_script( $id ) {
	// to-do
}

function wp_enqueue_style( $id, $href = "" ) {
	if( empty( $GLOBALS[ 'SCRIPTS' ] ) )
		$GLOBALS[ 'SCRIPTS' ] = array();
	
	$GLOBALS[ 'SCRIPTS' ][] = array( 'id' => $id, 'href' => $href, 'type' => 'css' );
}

function wp_register_style( $id, $href ) {
	if( empty( $GLOBALS[ 'REGISTERED_SCRIPTS' ] ) )
		$GLOBALS[ 'REGISTERED_SCRIPTS' ] = array();
	
	$GLOBALS[ 'REGISTERED_SCRIPTS' ][] = array( 'id' => $id, 'href' => $href, 'type' => 'css' );
}

function wp_deregister_style( $id ) {
	// to-do
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



