<?php
/**
 * Woot Class
 *
 * @author   WooThemes
 * @since    2.0.0
 * @package  storefront
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woot' ) ) :

	/**
	 * The main Woot class
	 */
	class Woot {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
        add_action( 'after_setup_theme',      array( $this, 'setup' ) );
        add_action( 'wp_enqueue_scripts',     array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts',    	array( $this, 'child_scripts' ) ); // After WooCommerce.
        add_action( 'wp_enqueue_scripts',	    array( $this, 'woot_scripts' ) );
        add_action(	'wp_enqueue_scripts', 	  array( $this, 'woot_google_fonts') );
				add_action( 'tgmpa_register', 				array( $this, 'woot_register_required_plugins') );
				add_action( 'widgets_init', 					array( $this, 'woot_remove_widgets' ), 11 );
		}

    public function setup() {
      add_image_size( 'woot-thumb-400', 400, 400, true );
    }

    public function enqueue_styles() {
      global $storefront_version;
      wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
    }

    public function child_scripts() {
			if ( is_child_theme() ) {
				wp_enqueue_style( 'storefront-child-style', get_stylesheet_uri(), '' );
			}
		}

    public function woot_scripts() {
    	wp_enqueue_style( 'woot-flexslider-css', get_stylesheet_directory_uri() . '/assets/css/flexslider.min.css', array(), '' );

    	//wp_deregister_script( 'storefront-navigation' );
    	wp_enqueue_script( 'woot-nav', get_stylesheet_directory_uri() . '/assets/js/navigation.min.js', array( 'jquery' ), '', true );

    	wp_enqueue_script( 'woot-main-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array(), '', true );

    	if ( class_exists( 'WooCommerce' ) ) {
    		wp_enqueue_script( 'woot-flexslider-js', get_stylesheet_directory_uri() . '/assets/js/jquery.flexslider-min.js', array(), '', true );

    		wp_enqueue_script( 'woot-slider-js', get_stylesheet_directory_uri() . '/assets/js/slider.js', array(), '', true );
    	}
    }

    public function woot_google_fonts() {
    	wp_enqueue_style( 'woot-googleFonts', '//fonts.googleapis.com/css?family=Roboto:400,300,400italic,500,700,900' );
    }

		function woot_remove_widgets(){
			// Unregister some of the TwentyTen sidebars
			unregister_sidebar( 'header-1' );

		}

		/**
		 * Register the required plugins for this theme.
		 *
		 * In this example, we register five plugins:
		 * - one included with the TGMPA library
		 * - two from an external source, one from an arbitrary source, one from a GitHub repository
		 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
		 *
		 * The variable passed to tgmpa_register_plugins() should be an array of plugin
		 * arrays.
		 *
		 * This function is hooked into tgmpa_init, which is fired within the
		 * TGM_Plugin_Activation class constructor.
		 */
		public function woot_register_required_plugins() {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$plugins = array(

				array(
					'name'      => 'Homepage Control',
					'slug'      => 'homepage-control',
					'required'  => false,
				),

			);

			/*
			 * Array of configuration settings. Amend each line as needed.
			 *
			 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
			 * strings available, please help us make TGMPA even better by giving us access to these translations or by
			 * sending in a pull-request with .po file(s) with the translations.
			 *
			 * Only uncomment the strings in the config array if you want to customize the strings.
			 */
			$config = array(
				'id'           => 'woot',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
			);

			tgmpa( $plugins, $config );
		}


	}

endif;

return new Woot();