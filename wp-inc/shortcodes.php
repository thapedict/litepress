<?php

// some test shortcodes

// always start with a hello
function do_hello( $attr ) {
	return '<h1>Hello, World</h1><p>My name is Thapelo Moeti. I am a programmer. I love programming. It takes a lot of my time but I do it anyway</p>';
}
add_shortcode( 'hello', 'do_hello' );

// get file content in the folder 'wp-inc/content' folder
function wp_tdt_fgc( $attr ) {
	$content = '';
	
	if( isset( $attr[ 'file' ] ) ) {
		$file = WP_INC_CONTENT . $attr[ 'file' ];
		
		if( is_readable( $file ) ) {
			$content = tdt_fgc( $file );
		} else {
			trigger_error( 'wp_tdt_fgc file not found: '. $attr[ 'file' ] );
		}
	}
	
	return $content;
}
add_shortcode( 'tdt_fgc', 'wp_tdt_fgc' );



