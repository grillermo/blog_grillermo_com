<?php
/**
 * Change default 'srcset' images width.
 * 
 * @param  [type] $max_width [description]
 * @return int Max image width.
 */
function hacker_max_srcset_image_width( $max_width ) {
	return 1500; // Retina 750x2
}
add_filter( 'max_srcset_image_width', 'hacker_max_srcset_image_width' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function hacker_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' == $size ) {
		$attr['sizes'] = '(max-width: 770px) calc(100vw - 20px), 750px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'hacker_post_thumbnail_sizes_attr', 10 , 3 );