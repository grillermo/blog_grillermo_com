<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hacker
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="discussion" class="Discussion">
	<header class="Discussion__header">
		<h3 class="Discussion__heading"><?php comments_number(__('No Comments','hacker'),__('1 Comment','hacker'),__('% Comments','hacker')); ?></h3>
	</header>
	<!-- END .Comments-area__header -->
	<?php if ( have_comments() ) : ?>
		<ul class="CommentList">
			
			<?php  wp_list_comments('callback=hacker_comments');?>
			
		</ul>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'hacker' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'hacker' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'hacker' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
<!-- END #comments -->