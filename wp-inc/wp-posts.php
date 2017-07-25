<?php

// wp-post


function post_class( $append = '' ) {
	// to-do
	$classes = array( 'type-post', 'status-publish', 'format-standard', 'category-uncategorized' );
	
	$classes =  (array) apply_filters( 'post_class', $classes );
	
	$classes = implode( ' ', $classes );
	
	if( is_array( $append ) )
		$append = implode( ' ', $append );
	
	$post_class = $classes . ' ' . $append;
	
	printf( 'class="%s"', esc_attr( $post_class ) );
}

function get_post_format() {
	// to-do
	return null;
}

function get_the_ID() {
	global $post;
	
	if( empty( $post ) )
		return;
	
	return $post->id;
}

function the_id() {
	print get_the_id();
}

function  get_the_title() {
	global $post;
	
	if( empty( $post ) )
		return;
	
	return apply_filters( 'the_title', $post->title );
}

function the_title() {
	print get_the_title();
}

function get_the_date( $format = 'j F Y' ) {
	global $post;
	
	if( empty( $post ) )
		return;
	
	$date = isset( $post->date ) ? date( $format, strtotime( $post->date ) ): date( $format );
	
	return apply_filters( 'get_the_date', $date, $format );
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
	global $post;
	
	if( ! isset( $post->category ) )
		return '';
	
	$p_cats = $post->category;
	
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

function get_the_category_list( $sep = '', $parents = '', $postid = null ) {
	$categories = get_the_category( $postid );
	
	if( empty( $categories ) )
		return '';
	else
		$cat_links = array();
	
	foreach( $categories as $c ) {
		$cat_links[] = sprintf( '<a href="%s" rel="category tag">%s</a>', get_category_link( $c->term_id ), $c->name );
	}
	
	return implode( $sep, $cat_links );
}

function get_the_tags( $post_id = null ) {
	// to-do
	return array();
}

function the_tags( $before = '', $between = '', $after = '' ) {
	// to-do
	return '';
}

function get_the_tag_list() {
	// to-do
	return '';
}

function get_the_content() {
	global $post;
	
	if( empty( $post ) )
		return;
	
	return $post->content;
}

function the_content() {
	print apply_filters( 'the_content', get_the_content() );	
}

function get_the_excerpt() {
	$content = get_the_content();
	$content = strip_tags( $content );
	$content = explode( ' ', $content );
	
	$read_more = apply_filters( 'excerpt_more', '[...]' );
	
	$excerpt_length = apply_filters( 'excerpt_length', 100 );
	
	if( count( $content ) <= $excerpt_length )
		$read_more = ''; // don't show read_more if content is small
	
	$content = array_slice( $content, 0, $excerpt_length );
	
	$excerpt = implode( ' ', $content );
	
	$excerpt = '<p>' . $excerpt . $read_more . '</p>';
	
	return apply_filters( 'get_the_excerpt', $excerpt );
}

function the_excerpt() {
	print get_the_excerpt();
}

function get_permalink() {
	global $post;
	
	if( empty( $post ) )
		return;
	
	$link = home_url( '/' ) . $post->name . '/';
	
	return apply_filters( 'the_permalink', $link );
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

function get_the_author_meta( $field ) {
	switch( $field ) {
		case 'description':
			return '';
	}
	return '';
}

function the_author_meta( $field ) {
	print get_the_author_meta( $field );
}

function get_author_posts_url() {
	return home_url( '/author/thapelo-moeti' );
}

function the_author_posts_url() {
	print get_author_posts_url();
}

function has_post_thumbnail() {
	// to-do
	return false;
}

function get_post_thumbnail_id() {
	return false;
}

function the_post_thumbnail() {
	// to-do
}

function wp_get_attachment_image_src() {
	return '';
}

function posts_nav_link( $glue = '', $prev_label = '', $next_label = '' ) {
	// to-do
	previous_posts_link( $prev_label );
	print  $glue;
	next_posts_link( $next_label );
}

function previous_posts_link() {
	// to-do
	$html = apply_filters( 'previous_posts_link', '<a href="javascript:void(0);">&laquo; Previous Page</a>' );
	
	print $html;
}

function next_posts_link() {
	// to-do
	$html = apply_filters( 'next_posts_link', '<a href="javascript:void(0);">Next Page &raquo;</a>' );
	
	print $html;
}

function previous_post_link() {
	// to-do
	$html = apply_filters( 'previous_post_link', '<a href="javascript:void(0);">&laquo; Previous Post</a>' );
	
	print $html;
}

function next_post_link() {
	// to-do
	$html = apply_filters( 'next_post_link', '<a href="javascript:void(0);">Next Post &raquo;</a>' );
	
	print $html;
}

function edit_post_link( $label = 'Edit', $before = '', $after = '' ) {
	// to-do
	$html = apply_filters( 'edit_post_link', '' );
	
	print $html;
}

function wp_link_pages() {
	// to-do
	$html = apply_filters( 'wp_link_pages', '' );
	
	print $html;
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
	// should run the same as single_cat_title
}


