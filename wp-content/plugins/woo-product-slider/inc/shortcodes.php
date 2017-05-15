<?php

// WooCommerce product slider shortcode
function woo_product_slider_free_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id'            => '01',
		'title'         => '',
		'color'         => '#e74c3c',
		'pagination'    => 'true',
		'nav'           => 'true',
		'auto_play'     => 'true',
		'items'         => '4',
		'stop_on_hover' => 'true',
	), $atts, 'woo-product-slider' ) );


	$que = new WP_Query(
		array( 'posts_per_page' => '-1', 'post_type' => 'product' )
	);


	$outline = '';

	$outline .= '<style>
div#sp-woo-product-slider-free' . $id . '.wpsf-product-section .owl-controls .owl-buttons .owl-prev:hover,
div#sp-woo-product-slider-free' . $id . '.wpsf-product-section .owl-controls .owl-buttons .owl-next:hover,
div.wpsf-slider-section .wpsf-cart-button a:hover,
div#sp-woo-product-slider-free' . $id . '.wpsf-product-section .owl-controls .owl-page span{background-color: ' . $color . '; border-color: ' . $color . '; color: #fff; }

div.wpsf-slider-section .wpsf-product-title a:hover{
	color: ' . $color . ';
}
</style>';

	$outline .= '
    <script type="text/javascript">
    jQuery(document).ready(function() {
		jQuery("#sp-woo-product-slider-free' . $id . '").owlCarousel({
			items: '. $items .',
			navigation: ' . $nav . ',
			navigationText: ["<",">"],
			autoHeight: false,
			autoPlay: ' . $auto_play . ',
			pagination: ' . $pagination . ',
			stopOnHover: ' . $stop_on_hover . ',
		});

    });
    </script>';

	$outline .= '<div class="wpsf-slider-section">';
	$outline .= '<h2 class="wpsf-section-title">' . $title . '</h2>';
	$outline .= '<div id="sp-woo-product-slider-free' . $id . '" class="wpsf-product-section">';

	if ( $que->have_posts() ) {
		while ( $que->have_posts() ) : $que->the_post();
			global $product;

			$outline .= '<div class="wpsf-product">';
			$outline .= '<a href="' . esc_url( get_the_permalink() ) . '">';
			if ( has_post_thumbnail( $que->post->ID ) ) {
				$outline .= get_the_post_thumbnail( $que->post->ID, 'shop_catalog_image_size', array( 'class' => "wpsf-product-img" ) );
			} else {
				$outline .= '<img id="place_holder_thm" src="' . woocommerce_placeholder_img_src() . '" alt="Placeholder" />';
			}
			$outline .= '</a>';
			$outline .= '<div class="wpsf-product-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></div>';

			if ( class_exists( 'WooCommerce' ) && $price_html = $product->get_price_html() ) {
				$outline .= '<div class="wpsf-product-price">' . $price_html . '</div>';
			};

			$outline .= '<div class="wpsf-cart-button">' . do_shortcode( '[add_to_cart id="' . get_the_ID() . '"]' ) . '</div>';
			$outline .= '</div>';

		endwhile;
	} else {
		$outline .= '<h2 class="sp-not-found-any-product-f">' . __( 'Not found any product', 'woo_product_slider' ) . '</h2>';
	}
	$outline .= '</div>';
	$outline .= '</div>';


	wp_reset_query();

	return $outline;

}

add_shortcode( 'woo-product-slider', 'woo_product_slider_free_shortcode' );