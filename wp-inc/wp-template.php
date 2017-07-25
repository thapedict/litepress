<?php

// wp-template

function get_template_directory_uri() {
	return home_url();
}

// suppose to return the theme directory
function get_template_directory() {
	return HOME_DIR;
}

function home_url( $append = '' ) {
	return substr( HOME_URL, 0, -1 ) . $append;
}

function get_stylesheet_uri() {
	return home_url( '/style.css' );
}

function admin_url() {
	return HOME_URL;
}

function language_attributes() {
	print 'lang="en"';
}

function body_class() {
	$classes = array();
	
	if( is_home() )
		$classes[] = 'home';
	
	if( is_archive() )
		$classes[] = 'archive';
	
	if( is_search() )
		$classes[] = 'search';
	
	if( is_404() )
		$classes[] = 'error404';
	
	if( is_page() )
		$classes[] = 'page';
	
	if( is_single() )
		$classes[] = 'single single-post';
	
	$classes = apply_filters( 'body_class', (array) $classes );
	
	$classes = implode( ' ', $classes );
	
	echo 'class="', $classes , '"';
}

// returns the path for the template to use
function get_query_template() {
	$template = '';
	
	// to prevent rewriting wordpress, lets just do the minimal basics
	if( is_home() ){
		if( is_readable( HOME_DIR . '/front-page.php' ) )
			$template = 'front-page';
		elseif( is_readable( HOME_DIR . '/home.php' ) )
			$template = 'home';
	} else if( is_page() ) {
		if( is_readable( HOME_DIR . '/page.php' ) )
			$template = 'page';
	} else if( is_single() ) {
		if( is_readable( HOME_DIR . '/single.php' ) )
			$template = 'single';
	} else if( is_date() ) {
		if( is_readable( HOME_DIR . '/date.php' ) )
			$template = 'date';
		if( is_readable( HOME_DIR . '/archive.php' ) )
			$template = 'archive';
	} else if( is_category() ) {
		if( is_readable( HOME_DIR . '/category.php' ) )
			$template = 'category';
		if( is_readable( HOME_DIR . '/archive.php' ) )
			$template = 'archive';
	} else if( is_archive() ) {
		if( is_readable( HOME_DIR . '/archive.php' ) )
			$template = 'archive';
	} else if( is_search() ) {
		if( is_readable( HOME_DIR . '/search.php' ) )
			$template = 'search';
	} else if( is_404() ) {
		if( is_readable( HOME_DIR . '/404.php' ) )
			$template = '404';
	} 
	
	if( ! $template )
		$template = 'index'; // default to index of course
	
	$template = get_template_directory() . "/{$template}.php";
	
	return apply_filters( 'template_include', $template );
}

function wp_title( $sep = '&raquo;', $print = true, $side = 'left' ) {
	$title = '';
	
	if( is_404() ):
		$title = '404 Page Not Found';
	elseif( is_page() ):
		$page = get_page();
		$title = $page->title;
	elseif( is_single() ):
		$post = get_post();
		$title = $post->title;
	endif;
	
	if( $title ) {
		if( $side == 'left' )
			$title = "$sep $title";
		else
			$title .= " $sep ";		
	}
	
	$title = apply_filters( 'wp_title', $title, $sep, $side );
	
	if( $print )
		print $title;
	else
		return $title;
}

function wp_head() {
	// to-do
	do_action( 'wp_head' );
}

function wp_footer() {
	// to-do
	do_action( 'wp_footer' );
}

function get_template_part( $template, $part = '' ) {
	if( ! empty( $part ) ) {
		$template .= '-' . $part;
	}
	
	$template .= '.php';
	
	load_template( $template );
}

function load_template( $template ) {
	global $post, $posts, $paged;
	
	require HOME_DIR . '/' . $template;	
}

function get_header( $name = '' ) {
	$template = 'header';
	
	if( $name )
		$template .= '-' . $name;
	
	load_template( $template . '.php' );
}

function get_footer( $name = '' ) {
	$template = 'footer';
	
	if( $name )
		$template .= '-' . $name;
	
	load_template( $template . '.php' );
}

function get_sidebar( $name = '' ) {	
	$template = 'sidebar';
	
	if( $name )
		$template .= '-' . $name;
	
	load_template( $template . '.php' );
}

function get_header_image() {
	// to-do
	if( is_readable( WP_INC . 'img/header.jpg' ) )
		return WP_IMG . 'header.jpg';
	elseif( is_readable( WP_INC . 'img/header.png' )  )
		return WP_IMG . 'header.png';		
	else
		return false;
}

function header_image() {
	print get_header_image();
}

function has_nav_menu( $id ) {
	if( $id == 'header' || $id == 'primary' )
		return true;
	else
		return false;
}

function wp_nav_menu() {
	// to-do
?>
<nav>
	<ul id="header-menu" class="nav-menu">
		<li class="<?php the_menu_class( '/' ); ?>"><a href="<?php print home_url('/'); ?>">Home</a></li>
			<li class="<?php the_menu_class( 'about-us' ); ?>"><a href="<?php print home_url('/about-us/'); ?>">About Us</a>
			<ul class="sub-menu">
				<li class="<?php the_menu_class( '2017/10' ); ?>"><a href="<?php print home_url('/2017/10/'); ?>">Our Work</a></li>
			</ul>
		</li>
		<li class="<?php the_menu_class( 'contact-us' ); ?>"><a href="<?php print home_url('/contact-us/'); ?>">Contact</a></li>
	</ul>
</nav>
<?php
}

function the_menu_class( $menu_item ) {
	if( is_search() )
		return;
	
	if( $menu_item == get_url() )
		print 'current_page_item current-menu-item';
}

function add_theme_support( $feature, $args = array() ) {
	global $current_theme_supports;
	
	$current_theme_supports[ $feature ] = $args;
}

function current_theme_supports( $feature ) {
	global $current_theme_supports;
	
	return isset( $current_theme_supports[ $feature ] );
}

function set_post_thumbnail_size() {
	// to-do
}

function add_image_size() {
	// to-do
}

function add_editor_style() {
	// to-do
}

function register_nav_menu() {
	// to-do
}


