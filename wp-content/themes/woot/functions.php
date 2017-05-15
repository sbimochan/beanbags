<?php
/**
 * woot engine room
 *
 * @package woot
 */

 /**
  * Assign the Storefront version to a var
  */
 $theme              = wp_get_theme( 'woot' );
 $storefront_version = $theme['Version'];

 /**
  * Initialize all the things.
  */
require 'inc/class-woot.php';

require 'inc/woot-functions.php';
require 'inc/woot-template-functions.php';
require 'inc/woot-template-hooks.php';
require 'inc/customizer/class-woot-customizer.php';
require 'inc/tgm/class-tgm-plugin-activation.php';


if ( woot_is_woocommerce_activated() ) {
 require 'inc/woocommerce/woot-woocommerce-template-hooks.php';
}


 /**
  * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
  * https://github.com/woothemes/theme-customisations
  */