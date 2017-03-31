<?php

function is_page( $url = null ) {
	if( is_null( $url ) )
		$url = get_url();
	
	$pages = get_pages();
	
	$is_page = false;
	
	$is_page = tdt_array_where( $pages, array( 'name' => $url ) ) ? true: false;
	
	return $is_page;
}

function is_single( $url = null ) {
	if( is_null( $url ) )
		$url = get_url();
	
	$posts = get_posts();
	
	$is_post = tdt_array_where( $posts, array( 'name' => $url ) );
	
	return ! empty( $is_post );
}

function is_singular( $url = null ) {
	return is_single( $url );
}

function is_page_template( $template = null ) {
	// to-do
}

function is_archive( $url = null ) {
	// to-do
	if( is_null( $url ) )
		$url = get_url();
	
	// $archives = array( 'archive', 'blog' ); // page for posts
	
	if( is_date( $url ) || is_category( $url ) )
		return true;
	else
		return false;
}

function is_author( $author = null ) {
	// to-do
	return false;
}

function is_category( $url = null ) {
	// to-do
	if( is_null( $url ) )
		$url = get_url();
	
	if( ! preg_match( '#(?:category/)(?<category>[\w-]+)(?:(?:\/)?)#i', $url, $match ) )
		return false;
	
	$categories = get_all_categories();
	
	if( tdt_array_match( $categories, 'slug', $match[ 'category' ] ) )
		return true;
	else
		return false;
}

function is_tag( $tag = null ) {
	// to-do
	return false;
}

function is_date( $date = null ) {
	// to-do
	if( ! $date )
		$date = get_url();
	
	if( is_day( $date ) || is_month( $date ) || is_year( $date ) )
		return true;
	else
		return false;
}

function is_day( $day = null ) {
	// to-do
	if( ! $day )
		$day = get_url();
	
	if( preg_match( '#^(?<year_month_day>[\d]{4}/[\d]{2}/[\d]{2}[/]?)$#', $day ) )
		return true;
	else
		return false;	
}

function is_month( $month = null ) {
	// to-do
	if( ! $month )
		$month = get_url();
	
	if( preg_match( '#^(?<year_month>[\d]{4}/[\d]{2}[/]?)$#', $month ) )
		return true;
	else
		return false;
}

function is_year( $year = null ) {
	// to-do
	if( ! $year )
		$year = get_url();
	
	if( preg_match( '#^(?<year>[\d]{4}[/]?)$#', $year ) )
		return true;
	else
		return false;
}

function is_404( $page = null ) {
	// to-do
	if( ! is_home() && ! is_search() && ! is_page() && ! is_single() && ! is_archive() )
		return true;
	else
		return false;
}

function is_home() {
	// to-do
	if( is_search() )
		return false;
	else
		return get_url() == '/';
}

function is_front_page() {
	// to-do
	return is_home();
}

function is_admin() {
	// to-do
	return false;
}

function is_feed() {
	// to-do
	return false;
}

function is_search() {
	if( isset( $_GET[ 's' ] ) )
		return true;
	else
		return false;
}



