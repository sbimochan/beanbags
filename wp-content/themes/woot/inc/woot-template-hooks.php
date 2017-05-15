<?php
/**
 * Woot hooks
 *
 * @package woot
 */


/**
 * General
 * @see  storefront_scripts()
 */

/*add_action( 'after_setup_theme',	'woot_theme_setup' );
add_action( 'wp_enqueue_scripts',	'woot_scripts');
add_action(	'wp_enqueue_scripts', 	'woot_google_fonts');*/

/**
 * Header
 * @see  storefront_skip_links()
 * @see  storefront_secondary_navigation()
 * @see  storefront_site_branding()
 * @see  storefront_primary_navigation()
 */

add_action( 'woot_header_top',		'woot_social_media_links',		10 );
add_action( 'woot_header_top', 		'woot_secondary_navigation',	15 );

add_action( 'woot_skip_links', 		'storefront_skip_links', 		0 );
add_action( 'woot_header_logo', 	'woot_site_branding',			20 );

add_action( 'woot_header_nav', 		'woot_primary_navigation',		50 );

add_action( 'woot_slider', 			'woot_featured_slider',			60 );

add_action( 'woot_title', 			'woot_inner_title',				10 );

/**
 * Homepage
 * @see  storefront_homepage_content()
 * @see  storefront_product_categories()
 * @see  storefront_recent_products()
 * @see  storefront_featured_products()
 * @see  storefront_popular_products()
 * @see  storefront_on_sale_products()
 */
add_action( 'woot_homepage', 'storefront_homepage_content',		10 );
add_action( 'woot_homepage', 'storefront_product_categories',	20 );
add_action( 'woot_homepage', 'storefront_recent_products',		30 );
add_action( 'woot_homepage', 'storefront_featured_products',	40 );
add_action( 'woot_homepage', 'storefront_popular_products',		50 );
add_action( 'woot_homepage', 'storefront_on_sale_products',		60 );

/**
 * Posts
 * @see  storefront_post_meta()
 * @see  storefront_post_content()
 */
add_action( 'woot_single_post',		'storefront_post_meta',			10 );
add_action( 'woot_single_post',		'storefront_post_content',		20 );

add_action( 'woot_blog_index_thumb',	'woot_post_thumb',				10 );
add_action( 'woot_blog_index_header',	'woot_post_header',				10 );
add_action( 'woot_blog_index_content',	'woot_post_content',			10 );

/**
 * Pages
 * @see  storefront_page_content()
 */
add_action( 'woot_page', 			'storefront_page_content',		10 );

/**
 * Footer
 * @see  storefront_footer_widgets()
 * @see  storefront_credit()
 */
add_action( 'woot_footer_widgets', 'storefront_footer_widgets',	10 );
add_action( 'woot_credit_area', 'woot_credit',			20 );

?>