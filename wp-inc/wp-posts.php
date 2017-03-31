<?php

// wp-post


function post_class() {
	// to-do
	// yeah i know
	$post_class = 'type-post status-publish format-standard category-uncategorized';
	
	print apply_filters( 'post_class', $post_class );
}

function get_post_format() {
	// to-do
	return null;
}

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
	if ( empty( $GLOBALS[ 'POSTS' ] ) )
		return false;
	
	return ( current( $GLOBALS[ 'POSTS' ] ) !== false );
}

function the_post() {
	if( ! empty( $GLOBALS[ 'POSTS' ] ) ) {
		$GLOBALS[ 'post' ] = current( $GLOBALS[ 'POSTS' ] );
		next( $GLOBALS[ 'POSTS' ] );
	}
}

function get_the_id() {
	return isset( $GLOBALS[ 'post' ]->id ) ? $GLOBALS[ 'post' ]->id: null;
}

function the_id() {
	print get_the_id();
}

function  get_the_title() {
	if( is_single() || is_page() )
		return $GLOBALS[ 'POSTS' ][0]->title;
	
	return isset( $GLOBALS[ 'post' ]->title ) ? $GLOBALS[ 'post' ]->title: null;
}

function the_title() {
	print get_the_title();
}

function get_the_date( $format = 'j F Y' ) {
	return isset( $GLOBALS[ 'post' ]->date) ? date( $format, strtotime ( $GLOBALS[ 'post' ]->date ) ): date( $format );
}

function the_date( $format = 'j F Y' ) {
	print get_the_date( $format );
}

function get_the_modified_date( $format = 'j F Y' ) {
	// to-do
	return get_the_date( $format );
}

function the_modified_date( $format = 'j F Y' ) {
	print get_the_modified_date( $format );
}

function get_the_time( $format = 'H:i:s' ) {
	return get_the_date( $format );
}

function the_time( $format = 'H:i:s' ) {
	print get_the_time( $format );
}

function get_all_categories() {	
	if( ! isset( $GLOBALS[ 'CATEGORIES' ] ) )
		$GLOBALS[ 'CATEGORIES' ] = get_json( 'terms' );
	
	return $GLOBALS[ 'CATEGORIES' ];
}

function get_the_category() {
	if( ! isset( $GLOBALS[ 'post' ]->category ) )
		return '';
	
	$p_cats = $GLOBALS[ 'post' ]->category;
	
	$categories = get_all_categories();
	
	$categories = tdt_array_match( $categories, 'term_id', $p_cats, 'in' );
	
	return apply_filters( 'get_the_category', $categories );
}

function the_category() {
	$categories = get_the_category();
	
	if( empty( $categories ) )
		return;
	
	print '<ul class="categories">';
	foreach( $categories as $c ) {
		printf( '<li class="cat-item-%s"><a href="%s" rel="category tag">%s</a></li>', $c->term_id, get_category_link( $c->term_id ), $c->name );
	}
	print '</ul>';
}

function get_category_link( $term_id ) {
	$categories = get_all_categories();
	
	$category = tdt_array_match( $categories, 'term_id', (int) $term_id );
	
	if( empty( $category ) )
		return '';
	
	return home_url() . '/category/' . $category[0]->slug . '/'; // I think this is what happens???hmmm
}

function get_the_content() {
	$content = '';
	
	if( isset( $GLOBALS[ 'post' ]->content ) ) {
		$content = $GLOBALS[ 'post' ]->content;
	}
	
	$content = apply_filters( 'the_content', $content );
	
	return $content;
}

function get_the_excerpt() {
	$content = get_the_content();
	$content = strip_tags( $content );
	$content = explode( ' ', $content );
	
	$read_more = apply_filters( 'excerpt_more', '...' );
	
	$excerpt_length = apply_filters( 'excerpt_length', 100 );
	
	if( count( $content ) <= $excerpt_length )
		$read_more = ''; // don't show read_more if content is small
	
	$content = array_slice( $content, 0, $excerpt_length );
	
	$excerpt = implode( ' ', $content );
	
	$excerpt = '<p>' . $excerpt . $read_more . '</p>';
	
	return apply_filters( 'the_excerpt', $excerpt );
}

function the_excerpt() {
	print get_the_excerpt();
}

function the_content() {
	print get_the_content();	
}

function get_permalink() {
	return isset( $GLOBALS[ 'post' ]->name ) ? HOME_URL . $GLOBALS[ 'post' ]->name . '/': null;
}

function the_permalink() {
	print get_permalink();
}

function get_the_author() {
	return 'Thapelo Moeti';
}

function the_author() {
	print get_the_author();
}

function has_post_thumbnail() {
	return false;
}

function get_post_thumbnail_id() {
	return false;
}

function wp_get_attachment_image_src() {
	return '';
}

function posts_nav_link( $glue = '', $prev_label = '', $next_label = '' ) {
	// to-do
	print previous_posts_link( $prev_label ) . $glue . next_posts_link( $next_label );
}

function previous_posts_link() {
	// to-do
	return '';
}

function next_posts_link() {
	// to-do
	return '';
}

function edit_post_link( $label = 'Edit', $before = '', $after = '' ) {
	// to-do
	// echo $before, '<a href="javascript:void(0)">', $label, '</a>', $after;
}

function the_posts_pagination() {
	// to-do
	return '';
}

function is_sticky() {
	// to-do;
	return false;
}

function is_paged() {
	if( isset( $GLOBALS[ 'POSTS' ] ) && count( $GLOBALS[ 'POSTS' ] ) > 10 )
		return true;
	else
		return false;
}

function single_cat_title( $prefix = '', $print = true ) {
	// to-do
	if( ! preg_match( '#(?:category/)(?<category>[\w-]+)(?:(?:\/)?)#i', get_url(), $match ) )
		return false;
	
	$categories = get_all_categories();
	
	$category = tdt_array_match( $categories, 'slug', $match[ 'category' ] );
	
	$cat_string = $prefix . $category[0]->name;
	
	if( $print )
		print $cat_string;
	else
		return $cat_string;
}

function single_tag_title( $prefix = '', $print = true ) {
	// to-do
}


