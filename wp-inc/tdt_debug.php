<?php
/**
 *	debugging/testing script
 *
 *	@version 0.1
 *	license MIT
 */

 define( 'TDT_DEBUG', 1 );
 
class tdt_debug {
	
	function __construct() {
		add_action( 'wp_footer', array( $this, 'footer' ) );
		
		if( ! isset( $GLOBALS[ 'tdt_debug_footer' ] ) )
			$GLOBALS[ 'tdt_debug_footer' ] = array();
	}
	
	function footer() {		
		if( empty( $GLOBALS[ 'tdt_debug_footer' ] ) )
			return;
		
		foreach( $GLOBALS[ 'tdt_debug_footer' ] as $v ) {
			var_dump( $v );
			echo "*********---------*********---------**********<br/>\n";
		}
		
		$GLOBALS[ 'tdt_debug_footer' ] = array();
	}
	
	function add( $var ) {
		foreach( func_get_args() as $arg)
			$GLOBALS[ 'tdt_debug_footer' ][] = $arg;
	}
	
	function log( $string ) {		
		if( ! defined( 'TBT_LOG_PATH' ) )
			define( 'TBT_LOG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'tdt_debug.log' );
		
		$file = @fopen( TBT_LOG_PATH, 'a+' );
		
		if(  $file === false ) {
			trigger_error( "Can't open TBT_LOG_PATH log file for writing" );
			return false;
		}
		
		$date = date( 'Y-m-d H:i:s' ); // log entry date
		$debug_backtrace = debug_backtrace();
		// function which called this function
		$func = $debug_backtrace[ 1 ][ 'function' ];
		if( isset( $debug_backtrace[ 1 ][ 'class' ] ) )
			$func = $debug_backtrace[ 1 ][ 'class' ] . $debug_backtrace[ 1 ][ 'type' ]  . $func;
		$line = $debug_backtrace[ 1 ][ 'line' ]; // line number
		
		fwrite( $file, sprintf( '%s %s [%s]', $date, $func, $line ) . "\r\n" );
		fwrite( $file, $string . "\r\n" );
		
		fclose( $file );
		
		return true;
	}
	
}

function tdt_debug( $var = '--null--' ) {
	if( ! isset( $GLOBALS[ 'tdt_debug' ] ) )
		$GLOBALS[ 'tdt_debug' ] = new tdt_debug();
	
	if( $var !== '--null--' )
		$GLOBALS[ 'tdt_debug' ]->add( $var );
		
	return $GLOBALS[ 'tdt_debug' ];
}

if( ! function_exists( 'bool2str' ) ):
	function bool2str( $bool ) {
		return $bool ? 'true': 'false';
	}
endif;



