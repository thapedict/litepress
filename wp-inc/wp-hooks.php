<?php

// hooks

function add_action( $action_name, $callback ) {
	global $ACTIONS;
	
	if( ! isset( $ACTIONS[ $action_name ] ) )
		$ACTIONS[ $action_name ] = array();
	
	$ACTIONS[ $action_name ][] = $callback;
}

function do_action( $action_name ) {
	global $ACTIONS;
	$args = array_slice( func_get_args(), 1 );
	
	if( isset( $ACTIONS[ $action_name ] ) && ! empty( $ACTIONS[ $action_name ] ) ) {
		foreach( $ACTIONS[ $action_name ] as $callback )
			call_user_func_array( $callback, $args );
	}
}

function add_filter( $filter_name, $callback ) {
	global $FILTERS;
	
	if( ! isset( $FILTERS[ $filter_name ] ) )
		$FILTERS[ $filter_name ] = array();
	
	$FILTERS[ $filter_name ][] = $callback;
}

function apply_filters( $filter_name, $return_value ) {
	global $FILTERS;
	$args = array_slice( func_get_args(), 1 );
		
	if( isset( $FILTERS[ $filter_name ] ) && ! empty( $FILTERS[ $filter_name ] ) ) {
		foreach( $FILTERS[ $filter_name ] as $callback ) {
			$return_value = call_user_func_array( $callback, $args );
		}
	}
	
	return $return_value;
}



