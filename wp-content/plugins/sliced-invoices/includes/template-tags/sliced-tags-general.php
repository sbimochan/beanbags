<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }


if ( ! function_exists( 'sliced_get_the_id' ) ) :

	function sliced_get_the_id() {
		$output = Sliced_Shared::get_item_id();
		return apply_filters( 'sliced_get_the_id', $output );
	}

endif;


if ( ! function_exists( 'sliced_get_the_link' ) ) :

	function sliced_get_the_link( $id = 0) {
		$output = get_the_permalink( $id );
		return apply_filters( 'sliced_get_the_link', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_label' ) ) :

	function sliced_get_label( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_label( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_label( $id );
		}
		return apply_filters( 'sliced_get_label', $output, $id );
	}

endif;

if ( ! function_exists( 'sliced_get_label_plural' ) ) :

	function sliced_get_label_plural( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_label_plural( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_label_plural( $id );
		}
		return apply_filters( 'sliced_get_label_plural', $output, $id );
	}

endif;

if ( ! function_exists( 'sliced_get_number' ) ) :

	function sliced_get_number( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_number( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_number( $id );
		}
		return apply_filters( 'sliced_get_number', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_prefix' ) ) :

	function sliced_get_prefix( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_prefix( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_prefix( $id );
		}
		return apply_filters( 'sliced_get_prefix', $output, $id );
	}

endif;

if ( ! function_exists( 'sliced_get_type' ) ) :

	function sliced_get_the_type( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		return apply_filters( 'sliced_get_type', $type, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_filename' ) ) :

	function sliced_get_filename( $id = 0 ) {
		$output = Sliced_Shared::get_filename( $id );
		return apply_filters( 'sliced_get_filename', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_created' ) ) :

	function sliced_get_created( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_created( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_created( $id );
		}
		return apply_filters( 'sliced_get_created', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_terms_conditions' ) ) :

	function sliced_get_terms_conditions( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		if ( $type == 'invoice' ) {
			$output = sliced_get_invoice_terms( $id );
		} else if ( $type == 'quote' ) {
			$output = sliced_get_quote_terms( $id );
		}
		return apply_filters( 'sliced_get_terms_conditions', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_status' ) ) :

	function sliced_get_status( $id = 0 ) {
		if ( ! $id ) {
			$id = Sliced_Shared::get_item_id();
		}
		$type = Sliced_Shared::get_type( $id );
		$statuses = wp_get_post_terms( $id, $type . '_status', array( "fields" => "names" ) );
		$output = $statuses[0];
		return apply_filters( 'sliced_get_status', $output, $id );
	}

endif;


if ( ! function_exists( 'sliced_get_pre_defined_items' ) ) :

	function sliced_get_pre_defined_items() {
		$output = Sliced_Admin::get_pre_defined_items();
		return apply_filters( 'sliced_get_pre_defined_items', $output );
	}

endif;


if ( ! function_exists( 'sliced_display_the_line_totals' ) ) :

	function sliced_display_the_line_totals() {

		$type = Sliced_Shared::get_type();
		if ( ! $type )
			return;

		$output = '<div class="alignright sliced_totals">';
		$output .= '<h3>' . sprintf( __( '%s Totals', 'sliced-invoices' ), esc_html( sliced_get_label() ) ) .'</h3>';
		$output .= '<div class="sub">' . __( 'Sub Total', 'sliced-invoices' ) . ' <span class="alignright"><span id="sliced_sub_total">0.00</span></span></div>';
		$output .= '<div class="tax">' . sliced_get_tax_name() . ' <span class="alignright"><span id="sliced_tax">0.00</span></span></div>';
		$output .= '<div class="total">' . __( 'Total', 'sliced-invoices' ) . ' <span class="alignright"><span id="sliced_total">0.00</span></span></div>
			</div>';

		return apply_filters( 'sliced_get_line_item_totals', $output );
	}

endif;



if ( ! function_exists( 'sliced_print_message' ) ) :

	function sliced_print_message( $id = null, $message, $type = 'success', $die = false ) {

		if ( $message ) {
			$icon = $type == 'success' ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
			?>

			<div class="sliced-message <?php esc_attr_e( $type, 'sliced-invoices' ) ?>">
				<?php   echo $icon;
						echo wp_kses_post( $message );  ?>
			</div>

		<?php
			if ( $die ) { return; }
		}
	}

endif;


if ( ! function_exists( 'sliced_hide_adjust_field' ) ) :

	function sliced_hide_adjust_field() {
		$type = Sliced_Shared::get_type();
		if ( $type == 'invoice' ) {
			$output = sliced_invoice_hide_adjust_field();
		} else if ( $type == 'quote' ) {
			$output = sliced_quote_hide_adjust_field();
		}
		return apply_filters( 'sliced_hide_adjust_field', $output);
	}

endif;
