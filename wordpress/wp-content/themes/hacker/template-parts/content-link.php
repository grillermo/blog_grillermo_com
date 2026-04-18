<article id="post-<?php the_ID(); ?>" <?php post_class('Article'); ?>>
	<h3 class="Article__title">
		<a href="<?php echo esc_url( hacker_url_grabber() ); ?>" target="_blank" rel="bookmark">
			<span><?php the_title(); ?></span>
			<div class="link-url"><?php echo esc_html(hacker_url_grabber()); ?></div>
		</a>
		<?php
			if( is_sticky() ) {
				echo '<span class="sticky-mark"></span>';
			}
		?>
	</h3>
	<div class="Article__topMeta">
		<?php hacker_posted_on(); ?>
	</div>
	<footer class="Article__footer">
		<?php hacker_entry_footer(); ?>
	</footer>
	<!-- END .Article__footer -->
</article>
<!-- END .Article -->