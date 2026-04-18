<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Hacker
 */

get_header(); ?>
<main id="main" class="site-main page-404" role="main">
	<h1 class="page-404-title"><?php _e('404', 'hacker'); ?></h1>
	<div class="page-404-divide"><span></span></div>
	<div class="page-404-desc"><?php esc_html_e('It looks like nothing was found at this location.', 'hacker'); ?></div>
</main>
<!-- END #main -->
<?php
get_footer();