<?php
/**
* Plugin Name: Woo Elastic Slideshow
* Description: Woo Elastic Slideshow Plugin is for woocommerce Products.
* Version: 1.0.0
* Author: omikant, wptutorialspoint
* Author URI: https://profiles.wordpress.org/omikant
* License: GPL2
*/


add_shortcode( 'elastic-slideshow', 'wpels_slider_query' );
function wpels_slider_query( $atts ) {
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    ) );
    if ( $query->have_posts() ) { ?>
			<div id="slideshow" class="slideshow">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php
							$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
							//echo $feat_image;
							?>
				<div class="slide">
					<h2 class="slide__title slide__title--preview"><?php the_title(); ?> 
					<span class="slide__price">
						<?php 
							$price = get_post_meta( get_the_ID(), '_regular_price', true);
							echo get_woocommerce_currency_symbol();
							echo $price;
						?>
					</span></h2>
					<div class="slide__item">
						<div class="slide__inner">
							<img class="slide__img slide__img--small" src="<?php echo $feat_image; ?>" alt="Some image" />
							<?php woocommerce_template_loop_add_to_cart(); ?>
						</div>
					</div>
					<div class="slide__content">
						<div class="slide__content-scroller">
							<img class="slide__img slide__img--large" src="<?php echo $feat_image; ?>" alt="Some image" />
							<div class="slide__details">
								<h2 class="slide__title slide__title--main"><?php the_title(); ?></h2>
								<p class="slide__description"></p>
								<div>
									<span class="slide__price slide__price--large">
										<?php 
										$price = get_post_meta( get_the_ID(), '_regular_price', true);
										echo get_woocommerce_currency_symbol(); 
										echo $price;
										?>
									</span>
									<?php woocommerce_template_loop_add_to_cart();?>
									<button class="button button--buy">Add to cart</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile;
				wp_reset_postdata(); ?>
				
				<button class="action action--close" aria-label="Close"><i class="fa fa-close"></i></button>
			</div>
        
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}


add_action('wp_footer', 'wpels_inline_register_scripts', 30, 1);
function wpels_inline_register_scripts() {?>
    <script>
		(function() {
				document.documentElement.className = 'js';
				var slideshow = new CircleSlideshow(
					document.getElementById('slideshow')
				);
			})();
    </script>
    <?php
    
}

add_action('wp_footer', 'wpels_register_scripts');
function wpels_register_scripts() {
    if (!is_admin()) {
        // register
        wp_register_script('wpels_slider_classie_script', plugins_url('js/classie.js', __FILE__));
        wp_register_script('wpels_slider_dynamics_script', plugins_url('js/dynamics.min.js', __FILE__));
        wp_register_script('wpels_slider_main_script', plugins_url('js/main.js', __FILE__));
		// enqueue
        wp_enqueue_script('wpels_slider_classie_script');
        wp_enqueue_script('wpels_slider_dynamics_script');
        wp_enqueue_script('wpels_slider_main_script');
        wp_enqueue_script( 'jquery' );
    }
}





add_action('wp_footer', 'wpels_slider_style');
function wpels_slider_style() {
	// register
    wp_register_style('wpels_slider_normalize_styles', plugins_url('css/normalize.css', __FILE__));
    wp_register_style('wpels_slider_demo_styles', plugins_url('css/demo.css', __FILE__));
    wp_register_style('wpels_slider_component_styles', plugins_url('css/component.css', __FILE__));
    wp_register_style('wpels_slider_fontawesome_styles', plugins_url('css/font-awesome.css', __FILE__));
    // enqueue
    wp_enqueue_style('wpels_slider_normalize_styles');
    wp_enqueue_style('wpels_slider_demo_styles');
    wp_enqueue_style('wpels_slider_component_styles');
    wp_enqueue_style('wpels_slider_fontawesome_styles');
    }

?>