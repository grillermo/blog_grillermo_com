<?php
/**
 * Template Name: Tags
 *
 * This is the template that displays all tags of post.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hacker
 */

get_header(); ?>
<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
	<article id="page-<?php the_ID(); ?>" <?php post_class('Article'); ?>>
		<div class="Article__content">
		<?php
			$tags = get_tags( array(
				'hide_empty' => false
			) );
			$html = '<div class="tags-list">';

			foreach ($tags as $tag) {
				$tag_link = get_tag_link( $tag->term_id );
				$html .= "<a href='{$tag_link}' title='{$tag->name}'>";
				$html .= "{$tag->name}</a>";
			}

			$html .= '</div>';
			echo $html;
		?>
		</div>
		<!-- END .Article__content -->
	</article>
	<!-- END .Article -->
	<?php endwhile; ?>
</main>
<!-- END #main -->
<?php
get_footer();