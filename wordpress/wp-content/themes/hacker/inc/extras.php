<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Hacker
 */

/**
 * Change default excerpt more text
 */
function hacker_custom_excerpt_more( $more ) {
    return '...';
}
add_filter('excerpt_more', 'hacker_custom_excerpt_more');

/**
 * Site branding
 */
function hacker_site_branding() {
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
        the_custom_logo();
    } else {
        if ( is_front_page() && is_home() ) : ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <?php else : ?>
            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
        <?php
        endif;
    }

    $description = get_bloginfo( 'description', 'display' );
    if ( $description || is_customize_preview() ) {
        printf('<p class="site-description">%s</p>', $description);
    }
    
}

/**
 * Get first link in post content
 * @return string url
 */
function hacker_url_grabber() {
    if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) ) {
        return false;
    }

    return esc_url_raw( $matches[1] );
}

