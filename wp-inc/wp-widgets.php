<?php


function register_sidebar( array $sidebar ) {
	// to-do
}

function is_active_sidebar( $sidebar_name ) {
	return apply_filters( 'is_active_sidebar', true, $sidebar_name );
}

function dynamic_sidebar( $sidebar_name ) {
	if( is_sidebar( $sidebar_name ) ) {
		$sidebar = $GLOBALS[ 'SIDEBARS' ]->$sidebar_name;
		
		$widgets = apply_filters( 'sidebar_widgets', $sidebar->widgets, $sidebar_name );
		
		if( is_array( $widgets ) ) {
			foreach( $widgets as $s ) {
				if( is_readable( WP_INC_WIDGET . $s . '.php' ) )
					include WP_INC_WIDGET . $s . '.php';
			}
		}
	}
}

function the_widget( $name ) {
	if( is_readable( WP_INC_WIDGET . $name . '.php' ) )
		include WP_INC_WIDGET . $name . '.php';	
}

function is_sidebar( $sidebar_name ) {
	// load once
	if( ! isset( $GLOBALS[ 'SIDEBARS' ] ) ) {
		if( ! get_json( 'sidebars' ) )
			return false;
		
		$GLOBALS[ 'SIDEBARS' ] = get_json( 'sidebars' );
	}
	
	return isset( $GLOBALS[ 'SIDEBARS' ]->$sidebar_name );
}

function get_search_form( $print = true ) {
	ob_start();
	if( is_readable( HOME_DIR . '/searchform.php' ) )
		include HOME_DIR . '/searchform.php';
	else
		include WP_INC_WIDGET . 'searchform.php'; // could have written the markup in this function?
	
	$form = ob_end_clean();
	
	$form = apply_filters( 'get_search_form', $form );
	
	if( $print )
		print $form;
	else
		return $form;
}

