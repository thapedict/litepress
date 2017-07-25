<?php

// this is why we are here. Making the theme show something to test
// i.e. not part of the theme, and not part of wordpress

// we want the contact-us page to have no-sidebar
// and the homepage to use the home-widgets template
function template_redirects( $template ) {
	if( is_home() && current_theme_supports( 'home-widgets' ) && is_readable( HOME_DIR. '/home-widgets.php' ) ):
		return HOME_DIR . '/home-widgets.php';
	elseif( get_url() == 'contact-us' && is_readable( HOME_DIR. '/page-no-sidebar.php' ) ):
		return HOME_DIR . '/page-no-sidebar.php';
	else:
		return $template;
	endif;
}
add_filter( 'template_include', 'template_redirects' );

add_action( 'widgets_init', function(){
	// page_sidebar
	__register_sidebar_widget( 'search_widget', 'page_sidebar' );
	__register_sidebar_widget( 'recentposts_widget', 'page_sidebar' );
	
	// footer_sidebar
	__register_sidebar_widget( 'recentposts_widget', 'footer_sub_1_sidebar' );
	__register_sidebar_widget( 'search_widget', 'footer_sub_2_sidebar' );
	__register_sidebar_widget( 'meta_widget', 'footer_sub_3_sidebar' );
	
	// home-widgets
	__register_sidebar_widget( 'call_to_action_widget', 'home_widgets_sidebar' );
	__register_sidebar_widget( 'featured_items_widget', 'home_widgets_sidebar' );
	__register_sidebar_widget( 'latest_posts_widget', 'home_widgets_sidebar' );
	__register_sidebar_widget( 'contact_us_widget', 'home_widgets_sidebar' );
	__register_sidebar_widget( 'featured_items_widget', 'home_widgets_sidebar' );
} );


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



