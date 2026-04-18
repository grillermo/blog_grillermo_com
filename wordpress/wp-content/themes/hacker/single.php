<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Hacker
 */

get_header(); ?>
<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('Article Article--single'); ?>>
		<h1 class="Article__title"><?php the_title(); ?></h1>
		<div class="Article__topMeta">
			<?php hacker_posted_on(); ?>
		</div>
		<?php if (has_post_thumbnail()) : ?>
		<div class="Article__featured">
			<?php the_post_thumbnail(); ?>
		</div>
		<!-- END .featured-media -->
		<?php endif; ?>
		
		<div class="Article__content Content">
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
		<footer class="Article__footer">
			<?php hacker_entry_footer(); ?>
		</footer>
		<!-- END .Article__footer -->
		<?php if(function_exists('wp_clap')) { wp_clap(); } ?>
		<?php get_wp_claps(' ', 'Un aplauso', '% aplausos'); // total claps ?>
	</article>


	<!-- END .Article -->
	<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() )  {
			comments_template();
		}
	endwhile;	
	?>
</main>
<!-- END #main -->
<?php
get_footer();