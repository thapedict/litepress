<?php

// hmm, lets add funcs as we need them
class WP_Query {
	
	var $posts = array();
	
	var $post_count;
	
	function __construct( array $options ) {
		$this->posts = get_posts();
		$this->post_count = count( $this->posts );
	}
	
	function the_post() {
		if( ! empty( $this->posts ) ) {
			$GLOBALS[ 'post' ] = current( $this->posts );
			next( $this->posts );
		}
	}
}

function wp_reset_postdata() {
	// to-do
	init(); // hmmm....
}

function get_pages() {
	$pages = query_posts();
	
	$pages = array_filter( $pages, function( $p ){
										if( $p->type == 'page' )
											return true;
									});
	
	return $pages;
}

function get_post( $id = null, $type = 'post' ) {
	if( ! $id )
		$id = get_url();
	
	$posts = query_posts();
	$post = null;
	
	$where = array( 'type' => $type );
	
	if( is_numeric( $id ) )
		$where[ 'id' ] = $id;
	else
		$where[ 'name' ] = $id;
	
	$post = tdt_array_where( $posts, $where );
	
	if( $post )
		$post = $post[ 0 ];
	
	return $post;
}

function get_page( $id = null ) {
	if( ! $id )
		$id = get_url();
	
	return get_post( $id, 'page' );
}

function get_posts() {
	$posts = query_posts();
	
	$posts = array_filter( $posts, function( $p ){ // filter for type=post
										if( $p->type == 'post' )
											return true;
									});
	
	$posts = array_map( function( $p ){
		if( ! isset( $p->category ) || empty( $p->category ) ) {
			$p->category = array( 1 ); // default category ( Uncategorized )
		}
		return $p;
	}, $posts );
	
	return $posts;
}

function query_posts() {
	if( isset( $GLOBALS[ 'ALL_POSTS' ] ) )
		return $GLOBALS[ 'ALL_POSTS' ];
	
	$all_posts = get_json( 'posts' );
	
	foreach( $all_posts as $k => $p ) {
		// set slug (name) if not already set
		if( ! isset( $p->name ) )
			$all_posts[ $k ]->name =  preg_replace( '#[^\w-]#', '-', strtolower( $p->title ) );
		
		// set post type if not already set
		if( ! isset( $p->type ) )
			$all_posts[ $k ]->type = 'post';
		
		// set post date if not already set
		if( ! isset( $p->date ) )
			$all_posts[ $k ]->date = '2016-11-12 20:44:12';
	}
	
	// sort by date descending
	usort( $all_posts, 'usort_date_desc' );
	
	// because we don't want to run this whole process again
	$GLOBALS[ 'ALL_POSTS' ] = $all_posts;
	
	return $all_posts;
}

// to be used as a callback function for usort
// sort newest to oldest
function usort_date_desc( $a, $b ) {
	if( $a->date == $b->date )
		return 0;
	
	return $a->date < $b->date ? 1: -1; 
}

function add_query_arg() {
	// to-do
	return '';
}

function wp_parse_args( $args ) {
	// to-do
	return $args;
}

// need more info on this
function get_theme_mod( $key = null ) {
	// to-do
	if( $key )
		return '#ccc';
	else
		return array( 1, 2, 3 );
}


