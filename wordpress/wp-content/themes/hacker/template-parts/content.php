<article id="post-<?php the_ID(); ?>" <?php post_class('Article'); ?>>
	<h2 class="Article__title">
		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<span><?php the_title(); ?></span>
		</a>
		<?php
			if( is_sticky() ) {
				echo '<span class="sticky-mark"></span>';
			}
		?>
	</h2>
	<div class="Article__topMeta">
		<?php hacker_posted_on(); ?>
	</div>
	<?php if (has_post_thumbnail()) : ?>
	<div class="Article__featured">
		<?php the_post_thumbnail(); ?>
	</div>
	<!-- END .featured-media -->
	<?php endif; ?>

	<?php if( has_excerpt() ): ?>
	<div class="Article__excerpt">
	<?php the_excerpt(); ?>
	</div>
	<!-- END .Article__excerpt -->
	<?php else: ?>
	<div class="Article__content">
	<?php 
		the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'hacker' ),
			get_the_title()
		) );
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'hacker' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'hacker' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
	?>
	</div>
	<!-- END .Article__content -->
	<?php endif; ?>
	<footer class="Article__footer">
		<?php hacker_entry_footer(); ?>
	</footer>
	<!-- END .Article__footer -->
	<?php if(function_exists('wp_clap')) { wp_clap(); } ?>
	<?php get_wp_claps(' ', 'Un aplauso', '% aplausos'); // total claps ?>
</article>
<!-- END .Article -->