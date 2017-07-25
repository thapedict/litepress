<?php

// this would normally be in the HW plugin
// so lets not be too concerned about what's going on
$tdt_hw = array();
$tdt_hw[ 'call_to_action' ] = array( 'title' => 'Hello and Welcome',
									'image' => '',
									'image_alt' => '',
									'text' => 'This is just awesome. Took me a while, but I\'m finally here',
									'read_more_text' => 'Learn More',
									'read_more_url' => './' );

$tdt_hw[ 'featured_items' ] = array(
									'main_title' => 'Our Services',
									'sub_title' => 'We are a one stop shop for all your web and graphic design needs',
									'items' => array(
										array( 'title' => 'Website Design', 'fa_icon' => 'cog', 'text' => 'Lala land doesn\'t exist people. We need to have a meeting about this ASAP', 'read_more_text' => '', 'read_more_url' => '' ),
										array( 'title' => 'Website Design', 'fa_icon' => 'code', 'text' => 'Lala land doesn\'t exist people. We need to have a meeting about this ASAP', 'read_more_text' => '', 'read_more_url' => '' ),
										array( 'title' => 'Website Design', 'fa_icon' => 'cogs', 'text' => 'Lala land doesn\'t exist people. We need to have a meeting about this ASAP', 'read_more_text' => '', 'read_more_url' => '' )
									) );
							
$tdt_hw[ 'contact_us' ] = array(
								'main_title' => 'Contact Us'
								);
$tdt_hw[ 'latest_posts' ] = array(
								'main_title' => 'From The Blog'
								);
$tdt_hw[ 'contact_details' ] = array(
								'main_title' => 'Our Contact Details',
								'facebook' => 'thapedict',
								'twitter' => 'thapedict',
								'instagram' => 'thapedict',
								'linkedin' => '',
								'googleplus' => '',
								'email' => '', 
								'phone' => '012 345 6789',
								'fax' => '',
								'address_street' => '123 My Street',
								'address_suburb' => 'Awesome Hood', 
								'address_city' => 'One City',
								'address_province' => '',
								'address_code' => '',
								'address_country' => 'South Africa'
								);
$tdt_hw[ 'contact_form' ] = array(
								'main_title' => 'Get In Touch'
								);

define( 'TDT_HOME_WIDGETS_DEFAULTS', WP_INC . 'home-widgets/' );

function tdt_hw_get_widget( $widget, $default = array() ) {
	global $tdt_hw;
	
	if( isset( $tdt_hw[ $widget ] ) ) {
		return $tdt_hw[ $widget ];
	}
	
	return $default;
}

// quickly load 3 posts for latest_posts section
add_action( 'init', function(){
	global $tdt_hw;
	
	$args = array(
		'posts_per_page' => 3
		);
		
	$tdt_hw[ 'latest_posts' ][ 'posts'] = new WP_Query( $args );
} );


function tdt_hw_html_widget( $widget_name, $widget ) {
	// theme override
	$template = isset( $widget[ 'template' ] ) ? $widget[ 'template' ]: '';
	$file = get_template_directory() . '/home-widgets/' . $widget_name . '.php';
	
	// plugin default
	if( ! is_readable( $file ) )
		$file = TDT_HOME_WIDGETS_DEFAULTS . $widget_name . '.php';
	
	// no template to use
	// should I be doing this?
	if( ! is_readable( $file ) )
		return "<h2>Widget Template Not Found</h2>";
	
	ob_start();
	
	require $file;
	
	return ob_get_clean();
}

abstract class HW_Widget extends WP_Widget {
	
	private $_name = '';
	
	function __construct() {
		$class = get_called_class();
		
		$this->_name = str_replace( '_widget', '', $class );
		$id = str_replace( '_', '-', $this->_name );
		$name = 'HW:' . ucwords( str_replace( '_', ' ', $this->_name ) );
		
		parent::__construct( $id, $name, array( 'classname' => $id ) );
	}
	
	function widget( $args, $instance ) {
		$data = tdt_hw_get_widget( $this->_name );
		
		if( empty( $data ) ) {
			echo '<h1>Failed To Load: ', $this->_name, '</h1>'; // should we? shouldn't we?
			return;
		}
		
		echo	$args[ 'before_widget' ],
				tdt_hw_html_widget( $this->_name, $data ),
				$args[ 'after_widget' ];
	}
	
}

class featured_items_widget extends HW_Widget {}
class contact_us_widget extends HW_Widget {}
class call_to_action_widget extends HW_Widget {}
class latest_posts_widget extends HW_Widget {}

add_action( 'widgets_init', function(){
	register_widget( 'featured_items_widget' );
	register_widget( 'contact_us_widget' );
	register_widget( 'call_to_action_widget' );
	register_widget( 'latest_posts_widget' );
	
} );


function tdt_hw_shortcode( $attr, $content, $tag ) {
	$action = tdt_hw_get_action( $tag );
	
	$widget = tdt_hw_get_widget( $action, $attr );
	
	if( ! $widget )
		return '';
	
	$html = tdt_hw_html_widget( $action, $widget );
	
	return $html;
}

// i know it makes no sense, but...
// because call_to_action/calltoaction would just be to generic
// and hw_call_to_action would just be too long.
$shortcodes = array( 'hw_calltoaction', 'hw_contactus', 'hw_contactdetails', 'hw_contactform', 
					'hw_featureditems', 'hw_latestposts', 'hw_mission', 'hw_meettheteam', 'hw_testimonials' );

foreach( $shortcodes as $s ) {
	add_shortcode( $s, 'tdt_hw_shortcode' );
}

// convert shortcode to action (e.g. hw_calltoaction to call_to_action)
// can return call_to_action or call-to-action etc... depending on $sep
function tdt_hw_get_action( $shortcode, $sep = '_' ) {	
	$action = '';
	
	switch( $shortcode ) {
		case 'hw_calltoaction': $action = 'call_to_action'; break;
		case 'hw_contactdetails': $action = 'contact_details'; break;
		case 'hw_contactform': $action = 'contact_form'; break;
		case 'hw_contactus': $action = 'contact_us'; break;
		case 'hw_latestposts': $action = 'latest_posts'; break;
		case 'hw_featureditems': $action = 'featured_items'; break;
		case 'hw_mission': $action = 'mission_statement'; break;
		case 'hw_testimonials': $action = 'testimonials'; break;
		case 'hw_map': $action = 'map'; break;
	}
	
	// just in case
	$action = apply_filters( 'tdt_hw_get_action', $action, $shortcode, $sep );
	
	if( $sep == '-' )
		$action = str_replace( '_', '-', $action );
	
	return $action;
}
