<?php

// hijacking tdt-theme
// lets declare before tdt-theme

function contact_us_sidebar( $template ) {
	if( get_url() == 'contact-us' )
		return HOME_DIR . '/no-page-sidebar.php';
	else
		return $template;
}
add_filter( 'template_include', 'contact_us_sidebar' );


function tdt_previous_next_post_link() {
?>
<div class="previous-next-post ts-2">
	<div><a href="javascript:void(0);">&laquo; Previous Post</a></div>
	<div class="align-right"><a href="javascript:void(0);">Next Post &raquo;</a></div>
</div>
<?php
}

function tdt_previous_next_posts_link() {
?>
<div class="previous-next-posts ts-2">
	<div><a href="javascript:void(0);">&larr; Previous Page</a></div>
	<div class="align-right"><a href="javascript:void(0);">Next Page &rarr;</a></div>
</div>
<?php
}


