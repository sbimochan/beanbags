<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package woot
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner">
		<div class="top-area">
			<div class="col-full">
				<?php

				do_action('woot_skip_links');
				?>
				<?php
				/**
				* woot_social_media_links hook
				*
				* @hooked woot_social_media_links - 10
				* @hooked woot_secondary_navigation - 15
				*/
				do_action( 'woot_header_top' ); ?>
			</div>
		</div> <!-- second-nav -->
		<div class="col-full">

			<?php
				do_action( 'woot_header_logo' );
			?>
			<div class="navigation-area">
				<?php
				/**
				* woot_header_nav hook
				*
				* @hooked storefront_header_cart - 60
				*/
				do_action( 'woot_header_nav' ); ?>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php if(is_page_template( 'template-homepage.php' )){ ?>
	<div id="banner-area" <?php if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . ');"'; } ?>>
		<div class="col-full">
			<?php
				/**
				* woot_slider hook
				*
				* @hooked woot_featured_slider - 60
				*/
				do_action( 'woot_slider' );
			?>
		</div>
		<div class="overlay"></div>
	</div> <!-- banner-area -->
	<?php }
	/**
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<?php if( !is_front_page() && !is_page_template( 'template-homepage.php' ) ){ ?>
	<div class="title-holder" <?php if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . ');"'; } ?>>
		<div class="col-full">
				<?php
				/**
				 * @hooked woot_inner_title - 10
				 */
				do_action( 'woot_title' ); ?>
			<div class="breadcrumbs-area">
				<?php
				/**
				 * @hooked woocommerce_breadcrumb - 10
				 */
				do_action( 'woot_breadcrumb' ); ?>
			</div>
			<span class="clearfix"></span>
		</div>
		<span class="overlay"></span>
	</div>
	<?php } ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
				<?php
				/**
				 * @hooked woot_shop_messages - 10
				 */
				do_action( 'woot_shop_messages' ); ?>