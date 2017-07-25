<?php

// loading posts
// this would be the main query
function init() {
	// to-do
	global $wp_query, $posts, $post, $paged;
	
	$wp_query = new WP_Query();

	$posts = array();

	$post = null;

	$paged = 0;	
	
	if( is_404() )
		return;
	
	// is_archive covers any type (category, date, search ... etc)
	// don't have time to be filtering posts here // to-do
	if( is_home() || is_archive() || is_search() ):
		$posts = get_posts();	
		$paged = 2;
	elseif( is_single() ):
		$posts[] = get_post();
	
	elseif( is_page() ):
		$posts[] = get_page();
	endif;
	
	$post = $posts[ 0 ];
}
add_action( 'init', 'init' );

function get_post_type( $id = null ) {
	// to-do
	if( is_null( $id ) )
		$id = get_url();
	
	if( is_single( $id ) )
		return 'post';
	elseif( is_page( $id ) )
		return 'page';
	else
		return '';
}

function have_posts() {
	global $posts;
	
	if ( empty( $posts ) )
		return false;
	
	return ( current( $posts ) !== false );
}

function the_post() {
	global $post, $posts;
	
	if( ! empty( $posts ) ) {
		$post = current( $posts );
		next( $posts );
	}
}

function wp_reset_postdata() {
	// to-do
	init(); // hmmm.... i think
}

function get_post( $id = null, $type = 'post' ) {
	if( ! $id )
		$id = get_url();
	
	$where = array( 'type' => $type, 'get_one' => true );
	
	if( is_numeric( $id ) )
		$where[ 'p' ] = $id;
	else
		$where[ 'name' ] = $id;
	
	$post = query_posts( $where );
	
	return $post;
}

function get_pages() {
	$pages = query_posts( array( 'post_type' => 'page' ) );
	
	return $pages;
}

function get_page( $id = null ) {
	if( ! $id )
		$id = get_url();
	
	$args = array( 'post_type' => 'page', 'get_one' => true );
	
	if( is_int( $id ) )
		$args[ 'p' ] = $id;
	else
		$args[ 'name' ] = $id;
	
	$page = query_posts( $args );
	
	return $page;
}

function get_posts( $options = '' ) {
	// to-do
	if( ! is_array( $options ) )
		$options = array();
	
	$options[ 'post_type' ] = 'post';
	
	$posts = query_posts( $options );
	
	return $posts;
}

function query_posts( $filters = null ) {
	// to-do	
		
	if( ! is_array( $filters ) ) {
		// we should process the string to array???
		// but not now
		$filters =  array();
	}
	
	$wp_query = new WP_Query();
	
	return $wp_query->query( $filters );
}

// to be used as a callback function for usort
// sort newest to oldest
function usort_date_desc( $a, $b ) {
	if( strtotime( $a->date ) == strtotime( $b->date ) )
		return 0;
	
	return $a->date < $b->date ? 1: -1; 
}

// we assume we want single
// but just incase
function get_post_meta( $post_id, $key, $single = true ) {
	// hmmm? interesting
	$post = get_post( $post_id );
	
	if( ! $post )
		return;
	
	if( isset( $post->$key ) )
		$meta = $post->$key;
	else
		$meta = '';
	
	if( $single )
		return $meta;
	else
		return array( $meta );
}

function update_post_meta( $post_id, $key, $value, $single = true ) {
	// to-do
}

function add_query_arg() {
	// to-do
	return '';
}

function wp_parse_args( $args ) {
	// to-do
	return $args;
}

// hmm, lets add funcs as we need them
class WP_Query {
	
	var $posts = array();
	
	var $post_count;
	
	var $query_vars = array();
	
	var $max_num_pages = 0; // for pagination
	
	function __construct( $args = null ) {
		if( ! empty( $args ) ) {
			$this->query( $args );
		}
	}
	
	function have_posts() {
		return ( current( $this->posts ) !== false );
	}
	
	function the_post() {
		if( ! empty( $this->posts ) ) {
			$GLOBALS[ 'post' ] = current( $this->posts );
			next( $this->posts );
		}
	}
	
	function query( array $args = array() ) {
		$defaults = array( 'post_type' => 'post', 'posts_per_page' => 10 );
		
		$args = array_merge( $defaults, $args );
		
		if( isset( $GLOBALS[ 'ALL_POSTS' ] ) ) {
			$all_posts =  $GLOBALS[ 'ALL_POSTS' ];
		} else {		
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
				
				// set category
				if( ( ! isset( $p->category ) || empty( $p->category ) ) && $p->type == 'post' ) {
					$p->category = array( 1 ); // default category ( Uncategorized )
				}
			}
		
			// sort by date descending
			usort( $all_posts, 'usort_date_desc' );
			
			// because we don't want to run this whole process again
			$GLOBALS[ 'ALL_POSTS' ] = $all_posts;
		}
		
		// some filters
		// post type
		if( isset( $args[ 'post_type' ] ) ) {
			$all_posts = tdt_array_where( $all_posts, array( 'type' => $args[ 'post_type' ] ) );	
		}
		
		// post id
		if( isset( $args[ 'p' ] ) ) {
			$all_posts = tdt_array_where( $all_posts, array( 'id' => $args[ 'p' ] ) );	
		}
		
		// name / slug ???
		if( isset( $args[ 'name' ] ) || isset( $args[ 'slug' ] ) ) {
			$slug = isset( $args[ 'name' ] ) ? $args[ 'name' ]: $args[ 'slug' ];
			
			$all_posts = tdt_array_where( $all_posts, array( 'name' => $slug ) );
		}
		
		// posts per page
		if( isset( $args[ 'posts_per_page' ] ) && is_int( $args[ 'posts_per_page' ] ) ) {
			$all_posts = array_slice( $all_posts, 0, $args[ 'posts_per_page' ] );
		}
		
		// should we do ordering?
		// ?????
		
		// not wp
		// get one (why, oh why?)
		// return first matched
		if( isset( $args[ 'get_one' ] ) && $all_posts )
			$all_posts = $all_posts[ 0 ];
		
		$this->posts = $all_posts;
		$this->post_count = count( $all_posts );
		
		if( count( $all_posts ) > 1 )
			$this->max_num_pages = 2; // fake pagination???
		
		return $all_posts;
	}
}


