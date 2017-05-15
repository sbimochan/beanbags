<?php 
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
global $wpdb, $blog_id;
if ( is_multisite() ) {
    switch_to_blog( $blog_id );
	foreach ( array(
        'giftwrap_display',
        'giftwrap_category_id',
        'giftwrap_show_thumb',
        'giftwrap_number',
        'giftwrap_header',
        'giftwrap_modal',
        'giftwrap_details',
        'giftwrap_text_label',
        'giftwrap_textarea_limit',
        'giftwrap_button',
        ) as $option) {
            delete_option( $option );
        }
	restore_current_blog();
} else {
    foreach ( array(
        'giftwrap_display',
        'giftwrap_category_id',
        'giftwrap_show_thumb',
        'giftwrap_number',
        'giftwrap_header',
        'giftwrap_modal',
        'giftwrap_details',
        'giftwrap_text_label',
        'giftwrap_textarea_limit',
        'giftwrap_button',
        ) as $option) {
            delete_option( $option );
        }  
}





