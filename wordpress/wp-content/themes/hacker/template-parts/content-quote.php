<article id="post-<?php the_ID(); ?>" <?php post_class('Article'); ?>>
	<div class="Article__content">
	<?php 
		the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'hacker' ),
			get_the_title()
		) );
	?>
	</div>
	<!-- END .Article__content -->
	<div class="Article__topMeta">
		<?php 
			hacker_posted_on();
			if( is_sticky() ) {
				echo '<span class="sticky-mark"></span>';
			}
		?>
	</div>
	<footer class="Article__footer">
		<?php hacker_entry_footer(); ?>
	</footer>
	<!-- END .Article__footer -->
</article>
<!-- END .Article -->