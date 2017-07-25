<?php

// should basically register a sidebar
function register_sidebar( array $sidebar_args ) {
	// to-do
	if( ! isset( $GLOBALS[ 'SIDEBARS' ] ) )
		$GLOBALS[ 'SIDEBARS' ] = array();
	
	$sidebar_id = empty( $sidebar_args[ 'id' ] ) ? sprintf( 'sidebar-%d', count($GLOBALS[ 'SIDEBARS' ] ) ): $sidebar_args[ 'id' ];
	
	$defaults = array( 'id' => $sidebar_id,
						'name' => '',
						'description' => '',
						'before_widget' => '<li class="widget">',
						'after_widget' => '</li>',
						'before_title' => '',
						'after_title' => '',
						'widgets' => array() );
						
	$sidebar_args = array_merge( $defaults, $sidebar_args );
	
	$GLOBALS[ 'SIDEBARS' ][ $sidebar_id ] = $sidebar_args;
}

function is_active_sidebar( $id ) {
	global $registered_widgets;
	
	$active = $registered_widgets->is_active_sidebar( $id );
	
	return apply_filters( 'is_active_sidebar', $active, $id );
}

function load_sidebar( $id ) {
	// to-do
	// hmmm? what goes on here
	if( empty( $GLOBALS[ 'SIDEBARS' ] ) )
		return false;
	
	if( isset( $GLOBALS[ 'SIDEBARS' ][ $id ] ) )
		return $GLOBALS[ 'SIDEBARS' ][ $id ];
	
	return false;
}

function dynamic_sidebar( $id ) {
	// to-do
	global $registered_widgets;
	
	if( $sidebar = load_sidebar( $id ) ) {
		$widgets = $registered_widgets->get_sidebar( $id );
		
		$widgets = (array) apply_filters( 'sidebar_widgets', $widgets, $id );
		
		$args = array_intersect_key( $sidebar, array( 'before_widget' => '', 'after_widget' => '',
														'before_title' => '', 'after_title' => '' ) );
														
		foreach( $widgets as $w ) {			
			$_args = $args; // DO NOT REMOVE: i have no idea how this line works. but using args directly just gives me the duplicates
			
			// I think it's done like this
			$_args[ 'before_widget' ] = sprintf( $args[ 'before_widget' ], $w->id, $w->classname );
			
			// then
			$w->widget( $_args, $w->instance );
		}
	}
}

function the_widget( $name, array $args = array(), array $instance = array() ) {
	// to-do
	if( ! is_widget( $name ) ) // check if registered widget
		return;
	
	$widget = new $name();
	$widget->widget( $args, $instance );
}

function is_sidebar( $id ) {
	// to-do
	if( empty( $GLOBALS[ 'SIDEBARS' ] ) )
		return false;
	
	if( isset( $GLOBALS[ 'SIDEBARS' ][ $id ] ) )
		return true;
	
	return false;
}

// is this part of WordPress?
function is_widget( $name ){
	if( class_exists( $name ) && in_array( $name, $GLOBALS[ 'WIDGETS' ] ) ) {
		$parents = class_parents( $name );
		
		if( is_array( $parents ) && in_array( 'WP_Widget' , $parents ) )
			return true;
	}
	
	return false;
}

function get_search_form( $print = true ) {
	ob_start();
	if( is_readable( HOME_DIR . '/searchform.php' ) ) {
		include HOME_DIR . '/searchform.php';		
	}
	else {
		?>	
		<form role="search" method="get" class="search-form" action="<?php echo home_url(); ?>">
			<label>
				<span class="screen-reader-text">Search for:</span>
				<input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s" title="Search for:" />
			</label>
			<input type="submit" class="search-submit" value="Search" />
		</form>
		<?php
	}
	
	$form = ob_get_clean();
	
	$form = apply_filters( 'get_search_form', $form );
	
	if( $print )
		print $form;
	else
		return $form;
}

function register_widget( $name ) {
	if( ! isset( $GLOBALS[ 'WIDGETS' ] ) )
		$GLOBALS[ 'WIDGETS' ] = array();
	
	if( class_exists( $name ) && ! in_array( $name, $GLOBALS[ 'WIDGETS' ] ) ) {
		$parents = class_parents( $name );
		
		if( is_array( $parents ) && in_array( 'WP_Widget' , $parents ) )
			$GLOBALS[ 'WIDGETS' ][] = $name;
	}
}

// not exactly WordPress but it works
// register an instance of a widget
function __register_sidebar_widget( $widget_name, $sidebar_id, $instance = array() ) {
	// to-do
	global $registered_widgets;
	return $registered_widgets->register( $widget_name, $sidebar_id, $instance );
}

// my version of registered widget instances
// not WordPress of course (WordPress has its own(more complicated) implementation)
class Registered_Widgets {
	private $widgets = array();
	private $instances = array();
	private $sidebars = array();
	// register new instance widget to a sidebar
	function register( $widget_name, $sidebar_id, $instance = array() ) {
		if( is_widget( $widget_name ) && is_sidebar( $sidebar_id )  ) {
			if( isset( $this->instances[ $widget_name ] ) )
				$i = ++$this->instances[ $widget_name ];
			else
				$i = $this->instances[ $widget_name ] = 1;

			$w = new $widget_name();
			$w->id = "{$w->id_base}-$i";
			$w->instance = $instance;
			
			$this->sidebars[ $sidebar_id ][] = $this->widgets[ $w->id ] = $w;
			
			
			return $w->id;
		}
		else {
			// probably bacause we called too early. widget or sidebar not registered yet
			// trigger_error( 'Cant register widget to sidebar' );
		}
	}
	
	// return widgets that belong to a particular sidebar_id
	function get_sidebar( $sidebar_id ) {
		if( empty( $this->sidebars[ $sidebar_id ] ) )
			return false;
		else
			return $this->sidebars[ $sidebar_id ];
	}
	
	// check if sidebar has widgets
	function is_active_sidebar( $sidebar_id ) {
		return ! empty( $this->sidebars[ $sidebar_id ] );
	}
}
$registered_widgets = new Registered_Widgets;


abstract class WP_Widget {
	
	private $id_base, $name, $display_name, $classname, $description; // are they private?
	
	function __get( $prop ) {
		if( property_exists( $this, $prop ) )
			return $this->$prop;
	}

	// for the purpose of this project, we only need to force implementation of the widget method
	abstract function widget( $args, $instance );
	// abstract function form( $instance );
	// abstract function update( $new, $old );
	
	function __construct( $id_base , $display_name, $options = array() ) {
		// lets see
		$this->id_base = $id_base;
		$this->name = $display_name;
		$this->classname = isset( $options[ 'classname' ] ) ? $options[ 'classname' ]: '';
		$this->description = isset( $options[ 'description' ] ) ? $options[ 'description' ]: '';
		// I think that's it
	}
}

class search_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'search', 'Search Form', array( 'classname' => 'widget_search' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		if( isset( $instance[ 'title' ] ) )
			echo $args[ 'before_title' ], $instance[ 'title' ], $args[ 'after_title' ];
		
		get_search_form();
		
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'search_widget' );

class archives_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'archives', 'Archives', array( 'classname' => 'widget_archive' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		echo $args[ 'before_title' ], "Archives", $args[ 'after_title' ];
		
		?>
		<ul>
			<li>
				<a href="javascript:void(0)">April 2015 (2)</a>
			</li>
			<li>
				<a href="javascript:void(0)">June 2015 (5)</a>
			</li>
			<li>
				<a href="javascript:void(0)">July 2015 (2)</a>
			</li>
			<li>
				<a href="javascript:void(0)">October 2015 (7)</a>
			</li>
		</ul>
		<?php
				
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'archives_widget' );

class categories_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'categories', 'Categories', array( 'classname' => 'widget_categories' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		echo $args[ 'before_title' ], "Categories", $args[ 'after_title' ];
		
		?>
		<ul>
			<li>
				<a href="javascript:void(0)">Uncategorized</a>
			</li>
			<li>
				<a href="javascript:void(0)">Awesome Discoveries</a>
			</li>
			<li>
				<a href="javascript:void(0)">PHP Projects</a>
			</li>
			<li>
				<a href="javascript:void(0)">WordPress Theme</a>
			</li>
			<li>
				<a href="javascript:void(0)">NodeJS Stuff</a>
			</li>
		</ul>
		<?php
		
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'categories_widget' );

class recentposts_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'recent-posts', 'Recent Posts', array( 'classname' => 'widget_recent_posts' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		echo $args[ 'before_title' ], "Recent Posts", $args[ 'after_title' ];
		?>
		<ul>
			<li>
				<a href="javascript:void(0)">The Rebirth of MyDB</a>
			</li>
			<li>
				<a href="javascript:void(0)">2016: The Year That Was</a>
			</li>
			<li>
				<a href="javascript:void(0)">Flexing The Grid</a>
			</li>
			<li>
				<a href="javascript:void(0)">Colors &#8211; The HTML5 Template</a>
			</li>
			<li>
				<a href="javascript:void(0)">Applying for an entry level job</a>
			</li>
		</ul>
		<?php
		
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'recentposts_widget' );

class meta_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'meta', 'Meta', array( 'classname' => 'widget_meta' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		echo $args[ 'before_title' ], "Meta", $args[ 'after_title' ];
		?>
		<ul>
			<li>
				<a href="javascript:void(0)">Login</a>
			</li>
			<li>
				<a href="javascript:void(0)">Entries RSS</a>
			</li>
			<li>
				<a href="javascript:void(0)">Comments RSS</a>
			</li>
			<li>
				<a href="javascript:void(0)">WordPress.org</a>
			</li>
		</ul>
		<?php
		
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'meta_widget' );

class text_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'text', 'Text', array( 'classname' => 'widget_text' ) );
	}
	
	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		
		if( isset( $instance[ 'title' ] ) )
			echo $args[ 'before_title' ], $instance[ 'title' ], $args[ 'after_title' ];
		
		if( isset( $instance[ 'text' ] ) )
			echo $instance[ 'text' ];
		
		echo $args[ 'after_widget' ];
	}
}
register_widget( 'text_widget' );


