<?php

function comments_template() {
	// to-do
	// ehmm? i think this is what happens
	if( is_readable( get_template_directory() . '/comments.php' ) ) {
		// get comments from db, then
		require get_template_directory() . '/comments.php';
	}
}

function post_password_required() {
	// to-do
	return false;
}

function have_comments() {
	// to-do
	if( is_single() )
		return true;
	else
		return false;
}

function get_comments_number() {
	// to-do
	if( is_single() )
		return 4;
	else
		return 0;
}

function wp_list_comments( array $args = array() ) {
	// to-do
	// this is like the default output
	?>
	<div id="comment-x" class="comment even alt thread-even thread-alt depth-1 parent">
		<article id="div-comment-x" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php wp_img( 'user32.png', array( 'class' => 'avatar avatar-32' ) ); // avatar ?>
					<b class="fn">
						<a class="url" href="javascript:void(0)">Jane Doe</a>
						<span class="says"> says: </span>
					</b>
				</div>
				<div class="comment-metadata">
					<a href="javascript:void(0)">
						<time datetime="">May 25th, at 18:26 am</time>
					</a>
				</div>
			</footer><!-- .comment-meta -->
			<div class="comment-content">
				<p>Comment about something. Depth 1</p>
			</div><!-- .comment-content -->
			<div class="reply">
				<a class="comment-reply-link" href="javascript:void(0)">Reply</a>
			</div>
		</article>
		
		<div id="comment-y" class="comment odd alt depth-2">
			<article id="div-comment-y" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php wp_img( 'user32.png', array( 'class' => 'avatar avatar-32' ) ); // avatar ?>
						<b class="fn">
							<a class="url" href="javascript:void(0)">Amy</a>
							<span class="says"> says: </span>
						</b>
					</div>
					<div class="comment-metadata">
						<a href="javascript:void(0)">
							<time datetime="">May 25th, at 18:26 am</time>
						</a>
					</div>
				</footer><!-- .comment-meta -->
				<div class="comment-content">
					<p>Comment about something. Depth 1</p>
				</div><!-- .comment-content -->
				<div class="reply">
					<a class="comment-reply-link" href="javascript:void(0)">Reply</a>
				</div>
			</article>	
		</div><!-- #comment-y -->
	</div><!-- #comment-x -->
	<div id="comment-1" class="comment even comment-author-anotheruser thread-even depth-1 parent">
		<article id="div-comment-1" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php wp_img( 'user32.png', array( 'class' => 'avatar avatar-32' ) ); // avatar ?>
					<b class="fn">
						<a class="url" href="javascript:void(0)">John Smith</a>
						<span class="says"> says: </span>
					</b>
				</div>
				<div class="comment-metadata">
					<a href="javascript:void(0)">
						<time datetime="">May 25th, at 18:26 am</time>
					</a>
				</div>
			</footer><!-- .comment-meta -->
			<div class="comment-content">
				<p>Comment about something. Depth 1</p>
				<p>Comment by some user</p>
			</div><!-- .comment-content -->
			<div class="reply">
				<a class="comment-reply-link" href="javascript:void(0)">Reply</a>
			</div>
		</article>
		
		<div id="comment-2" class="comment odd comment-author-headwriter bypostauthor depth-2">
			<article id="div-comment-2" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php wp_img( 'user32.png', array( 'class' => 'avatar avatar-32' ) ); // avatar ?>
						<b class="fn">
							<a class="url" href="javascript:void(0)">Head Writer</a>
							<span class="says"> says: </span>
						</b>
					</div>
					<div class="comment-metadata">
						<a href="javascript:void(0)">
							<time datetime="">May 25th, at 18:26 am</time>
						</a>
					</div>
				</footer><!-- .comment-meta -->
				<div class="comment-content">
					<p>Comment about something. Depth 2</p>
					<p>Comment by post author (also a user)</p>
				</div><!-- .comment-content -->
				<div class="reply">
					<a class="comment-reply-link" href="javascript:void(0)">Reply</a>
				</div>
			</article>	
		</div><!-- #comment-2 -->
	</div><!-- #comment-1 -->
	<?php
}

function get_comment_pages_count() {
	// to-do
}

function previous_comments_link() {
	// to-do
}

function next_comments_link() {
	// to-do
}

function comment_form() {
	// to-do
	ob_start();
	?>
	<div id="respond" class="comment-respond">
	<h3 id="reply-title" class="comment-reply-title">Leave a Reply <small><a rel="nofollow" id="cancel-comment-reply-link" href="/WP/wptest2/2013/01/10/image-alignment/#respond" style="display:none;">Cancel reply</a></small></h3>
	<form action="" method="post" id="commentform" class="comment-form" novalidate onsubmit="return false">
	<p class="comment-notes">
	<span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span></p>
	<p class="comment-form-author"><label for="author">Name <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="30" aria-required='true' required='required' /></p>
	<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label> <input id="email" name="email" type="email" value="" size="30" aria-describedby="email-notes" aria-required='true' required='required' /></p>
	<p class="comment-form-url"><label for="url">Website</label> <input id="url" name="url" type="url" value="" size="30" /></p>
	<p class="comment-form-comment"><label for="comment">Comment</label> <textarea id="comment" name="comment" cols="45" rows="8"  aria-required="true" required="required"></textarea></p>						
	<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Post Comment" /> <input type='hidden' name='comment_post_ID' value='903' id='comment_post_ID' />
	<input type='hidden' name='comment_parent' id='comment_parent' value='0' />
	</p></form>
	</div><!-- #respond -->
	<?php
	$form = apply_filters( 'comment_form', ob_get_clean() );
	
	print $form;
}

function comments_open() {
	// to-do
	global $post;
	
	if( empty( $post ) )
		$id = '';
	else
		$id = $post->id;
	
	if( is_single( $id ) )
		return true;
	else
		return false;
}

function pings_open() {
	// to-do
	return false;
}

function comments_popup_link( $zero = 'No Comments' ) {
	// to-do
	global $post;
	
	if( empty( $post ) )
		return;
	
	printf( '<a href="%s">%s</a>', home_url( '/' . $post->name ), $zero );
}



