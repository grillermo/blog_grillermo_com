<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Hacker
 */

if ( ! function_exists( 'hacker_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function hacker_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	hacker_entry_categories();
	
	printf('<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
		esc_url( get_permalink() ),
		$time_string
	);

	edit_post_link(
		esc_html__('- [Edit]', 'hacker'),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

if ( ! function_exists( 'hacker_entry_categories' ) ) :
/**
 * Prints HTML with category for current post.
 *
 */
function hacker_entry_categories() {
	$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'hacker' ) );
	if ( $categories_list ) {
		printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x( 'Categories', 'Used before category names.', 'hacker' ),
			$categories_list
		);
	}
}

endif;

if ( ! function_exists( 'hacker_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function hacker_entry_footer() {
	// Hide category and tag text for pages.
?>
	<div class="Article__meta pull-left">
	<?php 
		if( 'post' === get_post_type() ) {
			echo get_the_tag_list('<span class="post-tags"><i class="icon-tags"></i>', '', '</span>');
		} 
	?>
	</div>
	<!-- END .pull-left -->
	<div class="Article__meta pull-right">
	<?php
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span><i class="icon-comments"></i><span>';
			comments_popup_link( esc_html__( 'No Comment', 'hacker' ), esc_html__( '1 Comment', 'hacker' ), esc_html__( '% Comments', 'hacker' ) );
			echo '</span></span>';
		}
	?>
	</div>
	<!-- END .pull-right -->
<?php
	
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function hacker_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'hacker_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'hacker_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so hacker_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so hacker_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in hacker_categorized_blog.
 */
function hacker_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'hacker_categories' );
}
add_action( 'edit_category', 'hacker_category_transient_flusher' );
add_action( 'save_post',     'hacker_category_transient_flusher' );

/**
 * Post comments
 * @param  string $comment
 * @param  string $args
 * @param  string $depth
 * @return string
 */
function hacker_comments( $comment , $args , $depth ) {
	$GLOBALS['comment'] = $comment;
    $comment_post = get_post($comment->comment_post_ID);
?>
	<!-- commment -->
    <li id="comment-<?php echo $comment->comment_ID; ?>" <?php comment_class('Comment'); ?>>
    	<?php
          $avatar_size = 48;
        ?>
    	<div class="Comment__media">
    		<?php echo get_avatar($comment,$avatar_size); ?>
    	</div>
    	<div class="Comment__body">
      		<div class="Comment__heading">
      			<div class="Comment__meta">
      				<div class="Comment__username"><?php comment_author(); ?></div>
      				<div class="Comment__date"><?php echo get_comment_date(); ?></div>
      			</div>
      			<div class="Comment__actions">
      				<span class="Comment__reply"><?php comment_reply_link(array_merge($args,array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
      			</div>
			</div>
      		<?php if ($comment->comment_approved == '0') : ?>
            <div class="Alert Alert--warning"><?php _e('Your comment is awaiting approval.','hacker');?></div>
        	<?php endif; ?>
	        <?php comment_text(); ?>
    	</div>
    </li>
    <!-- End .Comment -->
<?php
}

function hacker_comment_form_defaults($defaults) {
	// $defaults['comment_notes_before'] = '';
    // $defaults['comment_notes_after'] = '';
    $defaults['comment_field'] = '<div class="comment-form__comment">
                                    <textarea placeholder="'. __('Say something nice!', 'hacker') .'" required name="comment" id="comment" rows="3"></textarea>
                                  </div>';
    $defaults['submit_field'] = '<div class="comment-form__submit">%1$s %2$s</div>';
    $defaults['submit_button'] = '<button type="submit" name="%1$s" id="%2$s" class="%3$s">%4$s</button>';
    $defaults['class_submit'] = 'btn btn-primary';
    // $defaults['logged_in_as'] = '';
    // $defaults['must_log_in'] = '';
    return $defaults;
}
add_filter('comment_form_defaults','hacker_comment_form_defaults');

function hacker_comment_fields( $fields ) {

	$commenter 	= wp_get_current_commenter();

	$req      	= get_option( 'require_name_email' );
   	$aria_req 	= ( $req ? " aria-required='true'" : '' );
	$html_req 	= ( $req ? " required='required'" : '' );
	$req_hint 	= ( $req ? "*": "" );

    $fields   	=  array(
		'author' => '<div class="comment-form__author">' .
		            '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="' . esc_attr__('Name', 'hacker') . $req_hint . '"' . $aria_req . $html_req . ' /></div>',
		'email'  => '<div class="comment-form__email">' .
		            '<input id="email" name="email" type="email" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" placeholder="' . esc_attr__('Email', 'hacker') . $req_hint . '" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></div>',
		'url'    => '<div class="comment-form__url">' .
		            '<input id="url" name="url" type="url" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="'. esc_attr__('Website', 'hacker') .'" /></div>'
	);
    
    return $fields;
}
add_filter('comment_form_default_fields','hacker_comment_fields');

function hacker_password_form() {
	global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline" method="post">
    <p class="help-block">' . __( "To view this protected post, enter the password below:", "hacker" ) . '</p>
    <div class="form-group"><label for="' . $label . '">' . __( "Password:", "hacker" ) . ' </label><input name="post_password" id="' . $label . '" type="password" class="form-control" size="20" maxlength="20" /></div><div class="form-group"><input type="submit" name="Submit" class="form-control" value="' . esc_attr__( "Submit", "hacker" ) . '" /></div>
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'hacker_password_form' );