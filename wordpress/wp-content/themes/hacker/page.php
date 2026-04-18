<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hacker
 */

get_header(); ?>
<main id="main" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
	<article id="page-<?php the_ID(); ?>" <?php post_class('Article Article--single'); ?>>
		<h1 class="Article__title"><?php the_title(); ?></h1>
		<div class="Article__content Content">
		<?php 
            the_content();
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
		<!-- END .Article__excerpt -->
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