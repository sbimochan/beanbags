<?php
/**
 * Woot WooCommerce hooks
 *
 * @package woot
 */


/**
 * Header
 * @see  storefront_header_cart()
 */
add_action( 'woot_header_nav', 		'storefront_header_cart', 		60 );


add_action( 'woot_breadcrumb',		'woocommerce_breadcrumb', 		10 );
add_action( 'woot_shop_messages', 	'storefront_shop_messages', 	10 );