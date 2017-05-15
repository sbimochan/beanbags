<?php
/*
Plugin Name: WooCommerce Product Slider
Description: This plugin will enable WooCommerce Product Slider in your WordPress site.
Plugin URI: http://shapedplugin.com/plugin/woocommerce-product-slider-pro
Author: ShapedPlugin
Author URI: http://shapedplugin.com
Version: 1.3
*/


/* Define */
define( 'SP_WPS_URL', plugins_url('/') . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'SP_WPS_PATH', plugin_dir_path( __FILE__ ) );


/* Including files */
require_once( "inc/scripts.php" );
require_once( "inc/shortcodes.php" );
require_once( "inc/widget.php" );

/* Plugin Action Links */
function woo_product_slider_action_links( $links ) {
	$links[] = '<a href="http://goo.gl/Q2Uznp" style="color: red; font-weight: bold;">Go Pro!</a>';

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_product_slider_action_links' );


// Redirect after active
function shaped_plugin_wpsf_active_redirect( $plugin ) {
	if ( $plugin == plugin_basename( __FILE__ ) ) {
		exit( wp_redirect( admin_url( 'options-general.php' ) ) );
	}
}
add_action( 'activated_plugin', 'shaped_plugin_wpsf_active_redirect' );


// admin menu
function add_shaped_plugin_wpsf_options_framwrork() {
	add_options_page( 'Woo Product Slider Help', '', 'manage_options', 'wpsf-settings', 'wpsf_options_framwrork' );
}
add_action( 'admin_menu', 'add_shaped_plugin_wpsf_options_framwrork' );


if ( is_admin() ) : // Load only if we are viewing an admin page

	function shaped_plugin_wpsf_register_settings() {
		// Register settings and call sanitation functions
		register_setting( 'wpsf_p_options', 'wpsf_options', 'wpsf_validate_options' );
	}
	add_action( 'admin_init', 'shaped_plugin_wpsf_register_settings' );


// Function to generate options page
	function wpsf_options_framwrork() {

		if ( ! isset( $_REQUEST['updated'] ) ) {
			$_REQUEST['updated'] = false;
		} // This checks whether the form has just been submitted. ?>


		<div class="wrap about-wrap">
			<style type="text/css">
				.wpsf-badge {
					position: absolute;
					top: 0;
					right: 0;
					background: url(<?php echo SP_WPS_URL ?>assets/images/icon.png) no-repeat #7C438A;
					-webkit-background-size: 120px 120px;
					background-size: 120px 120px;
					color: #fff;
					font-size: 14px;
					text-indent: -99999px;
					text-align: center;
					font-weight: 600;
					padding-top: 120px;
					height: 0px;
					display: inline-block;
					width: 120px;
					text-rendering: optimizeLegibility;
					-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
					box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
				}
			</style>
			<h1>Welcome to Woo Product Slider 1.2</h1>

			<div class="about-text">Thank you for using our WooCommerce Product Slider free plugin.</div>
			<div class="wpsf-badge">Version 1.2</div>

			<hr>

			<h3>Want some cool features of this plugin?</h3>

			<p>We've added many extra features in our <a href="http://goo.gl/Q2Uznp">premium version</a> of this
				plugin. Let see some amazing features. <a href="http://goo.gl/Q2Uznp">Buy Premium Version Now.</a></p>

			<div class="feature-section two-col">
				<div class="col">
					<img src="<?php echo SP_WPS_URL ?>assets/images/01.png" alt="">

					<h3>Advanced easy shortcode Generator</h3>

					<p>With the shortcode generator you can create a multi-optioned slider shortcode easily with lots of
						option fields. This wonderful generator helps you to handle the shortcode attributes with many
						dynamic fields.</p>
				</div>
				<div class="col">
					<img src="<?php echo SP_WPS_URL ?>assets/images/02.png" alt="">

					<h3>Visual Composer add-ons supported</h3>

					<p>If you are user of Visual Composer page builder, this Addon will extend your visual composer
						usability. With this powerful Addon you can create a slider by drag and drop and handle all the
						options of the slider smoothly.</p>
				</div>
			</div>

			<hr>

			<div class="feature-section two-col">
				<h2>Pro Version Advanced Features & Benefits</h2>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> Advanced easy shortcode Generator</li>
						<li><span class="dashicons dashicons-yes"></span> Unlimited slider anywhere</li>
						<li><span class="dashicons dashicons-yes"></span> Displaying Latest/Recent Products Slider</li>
						<li><span class="dashicons dashicons-yes"></span> Featured products slider</li>
						<li><span class="dashicons dashicons-yes"></span> Custom widget supported</li>
						<li><span class="dashicons dashicons-yes"></span> Display unlimited product slider in your
							widget area
						</li>
						<li><span class="dashicons dashicons-yes"></span> Product slider from specific product
							categories
						</li>
						<li><span class="dashicons dashicons-yes"></span> 2 (Two) Different theme styles to jump
							start your design
						</li>
						<li><span class="dashicons dashicons-yes"></span> Custom number of slider items(Products)</li>
						<li><span class="dashicons dashicons-yes"></span> Set Number of Columns you want to show</li>
						<li><span class="dashicons dashicons-yes"></span> Unlimited Custom color (Brand Color)</li>
						<li><span class="dashicons dashicons-yes"></span> Slider AutoPlay on/off</li>
						<li><span class="dashicons dashicons-yes"></span> Slider Stop on Hover</li>
						<li><span class="dashicons dashicons-yes"></span> Display product order by(date, title,
							modified,author, random)
						</li>
					</ul>
				</div>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> Special Sale ribbon for On Sale products</li>
						<li><span class="dashicons dashicons-yes"></span> Stylish navigation arrows</li>
						<li><span class="dashicons dashicons-yes"></span> Navigation show/hide options</li>
						<li><span class="dashicons dashicons-yes"></span> Pagination show/hide options</li>
						<li><span class="dashicons dashicons-yes"></span> Show product order by (ASC/DESC)</li>
						<li><span class="dashicons dashicons-yes"></span> 2 (Two) different styles for pagination
							(number, bullet)
						</li>
						<li><span class="dashicons dashicons-yes"></span> Enable/Disable star ratings</li>
						<li><span class="dashicons dashicons-yes"></span> Enable/Disable Sale Text</li>
						<li><span class="dashicons dashicons-yes"></span> Default Empty Thumbnail</li>
						<li><span class="dashicons dashicons-yes"></span> Product Slider Section Title change
							option
						</li>
						<li><span class="dashicons dashicons-yes"></span> Visual Composer add-ons supported</li>
						<li><span class="dashicons dashicons-yes"></span> Premium Priority support (24/7)</li>
						<li><span class="dashicons dashicons-yes"></span> Extensive Documentation</li>
						<li><span class="dashicons dashicons-yes"></span> Video Documentation</li>
					</ul>
				</div>
			</div>

			<h2><a href="http://goo.gl/Q2Uznp" class="button button-primary button-hero">Buy Premium Version Now.</a>
			</h2>
			<br>
			<br>
			<br>
			<br>

		</div>

		<?php
	}


endif;  // EndIf is_admin()


register_activation_hook( __FILE__, 'shaped_plugin_wpsf_activate' );
add_action( 'admin_init', 'shaped_plugin_wpsf_redirect' );

function shaped_plugin_wpsf_activate() {
	add_option( 'shaped_plugin_wpsf_activation_redirect', true );
}

function shaped_plugin_wpsf_redirect() {
	if ( get_option( 'shaped_plugin_wpsf_activation_redirect', false ) ) {
		delete_option( 'shaped_plugin_wpsf_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( "options-general.php?page=wpsf-settings" );
		}
	}
}