<?php

function add_shortcode( $name, $handler ) {
	global $SHORTCODES;
	
	$SHORTCODES[] = array( 'name' => $name, 'handler' => $handler );
}

function has_shortcodes( $string ) {
	global $SHORTCODES;
	
	if( empty( $SHORTCODES ) ) 
		return false;
	
	$matched_shortcodes = array();
	foreach( $SHORTCODES as $s ) {
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

// this should be part of do_shortcode,
// but that will make it too long and unreadable
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
										} );
										
		$keys = array_merge( $matches[ 'key' ], $matches[ 'k' ] );
		$values = array_merge( $matches[ 'value' ], $matches[ 'v' ] );
		$key_value = array_combine( $keys, $values );
		
		// array_filter removes elements with '0' key and we might need that, so lets implement our own
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


// we should probably add those default WordPress shortcodes here
//	maybe some day.....



