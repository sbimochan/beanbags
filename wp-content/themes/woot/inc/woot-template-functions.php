<?php
/**
 * Woot template functions.
 *
 * @package woot
 */

if ( ! function_exists( 'woot_credit' ) ) {
	/**
	 * Display the theme credit
	 * @since  1.0.0
	 * @return void
	 */
	function woot_credit() {
		?>
		<div class="site-info">
			<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; '.date('Y') .' | '. get_bloginfo( 'name' )  ." | SBimochan" ) ); ?>
			<?php if ( apply_filters( 'storefront_credit_link', false ) && is_home() || is_front_page()) { ?>
			<?php //printf( __( '| %1$s design by %2$s.', 'woot' ), 'Theme', '<a href="http://deucethemes.com/" title="Responsive WordPress Themes by Deuce Themes">Deuce Themes</a>' ); ?>
			<?php } ?>
		</div><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'woot_social_media_links' ) ) {
	/**
	 * Display Social Media
	 * @since  1.0.0
	 * @return void
	 */
	function woot_social_media_links() {
		?>
		<div class="social-media">
			<?php woot_social_icons(); ?>
		</div>
		<?php
	}
}


if ( ! function_exists( 'woot_secondary_navigation' ) && ! function_exists( 'storefront_header_widget_region' )) {
	/**
	 * Display Secondary Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function woot_secondary_navigation() {
		?>
		<nav class="second-nav" role="navigation" aria-label="<?php _e( 'Secondary Navigation', 'storefront' ); ?>">
			<?php
				wp_nav_menu(
					array(
						'theme_location'	=> 'secondary',
						'fallback_cb'		=> '',
					)
				);
			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'woot_site_branding' ) ) {
	/**
	 * Display Site Branding
	 * @since  1.0.0
	 * @return void
	 */
	function woot_site_branding() {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		} elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			jetpack_the_site_logo();
		} else { ?>
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php if ( '' != get_bloginfo( 'description' ) ) { ?>
					<p class="site-description"><?php echo bloginfo( 'description' ); ?></p>
				<?php } ?>
			</div>
		<?php }
	}
}

function woot_featured_slider() {
	/**
	 * Display Slider
	 * @since  1.0.0
	 * @return void
	 */
?>
	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
	<div class="flexslider">

	    <ul class="slides">
	        <?php

			$meta_key = get_theme_mod( 'woot_slider_area' );
			if(get_theme_mod( 'woot_slider_num_show' )) {
				$num_prod = get_theme_mod( 'woot_slider_num_show' );
			} else {
				$num_prod = "5";
			}

			if( $meta_key == "top_rated" ) {
				add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
				$args = array( 'posts_per_page' => $num_prod, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
				$args['meta_query'] = WC()->query->get_meta_query();
			} elseif( $meta_key == "featured" ) {
				$args = array( 'post_type' => 'product', 'posts_per_page' => $num_prod ,'meta_key' => '_featured', 'meta_value' => 'yes' );
			} elseif( $meta_key == "sale" ) {
				$args = array( 'post_type' => 'product', 'posts_per_page' => $num_prod,
				    'meta_query' => array(
				        'relation' => 'OR',
				        array(
				        'key'           => '_sale_price',
				        'value'         => 0,
				        'compare'       => '>',
				        'type'          => 'numeric'
				        ),
				        array( // Variable products type
				        'key'           => '_min_variation_sale_price',
				        'value'         => 0,
				        'compare'       => '>',
				        'type'          => 'numeric'
				        )
				    )
				);
			} elseif( $meta_key == "total_sales" ) {
				$args = array(
					'post_type' => 'product',
					'meta_key' => 'total_sales',
					'orderby' => 'meta_value_num',
					'posts_per_page' => $num_prod
				);
			} elseif( $meta_key == "recent" ) {
				$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => $num_prod, 'orderby' =>'date','order' => 'DESC' );
			} else {
				$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => $num_prod, 'orderby' =>'date','order' => 'DESC' );
			}
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post(); global $product, $post;	 ?>

				<li class="product-slider">
					<div class="banner-product-image">
						<?php if ( has_post_thumbnail( $loop->post->ID ) ) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="300px" height="300px" />'; ?>
					</div>
					<div class="banner-product-details">
						<h3><?php the_title(); ?></h3>
						<?php woocommerce_show_product_sale_flash( $post, $product ); ?>
						<p class="price"><?php echo $product->get_price_html(); ?></p>
						<p><?php echo $loop->post->post_excerpt; ?></p>
						<a href="<?php echo get_permalink( $loop->post->ID ) ?>" class="button" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>"><?php _e('View Product','woot'); ?></a>
					</div>
					<div class="clearfix"></div>
				</li>
			<?php endwhile; ?>
			<?php
				if( $meta_key == "top_rated" ) {
					remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
				}
			?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>

<?php } }

function woot_inner_title() {
?>
	<div class="inner-title">
			<h1>
				<?php
					if( is_post_type_archive('product') ) {
						woocommerce_page_title();
					} elseif( is_category() ) {
						single_cat_title();
					} elseif ( is_tag() ) {
						single_tag_title();
					} elseif( is_author() ) {
						global $post;
						$author_id = $post->post_author;
						the_author_meta('display_name', $author_id);
					} elseif ( is_day() || is_month() || is_year() ) {
						the_time('F Y');
					} elseif( is_archive() ) {
						single_cat_title();
					} elseif( is_home() ) {
						$blog_page_id = get_option('page_for_posts');
						echo get_post($blog_page_id)->post_title;
					} else {
						the_title();
					}
				?>
			</h1>
	</div>
<?php
}

if ( ! function_exists( 'woot_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function woot_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
		<button class="menu-toggle" aria-controls="primary-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location'	=> 'primary',
					'container_class'	=> 'primary-navigation',
					)
			);

			wp_nav_menu(
				array(
					'theme_location'	=> 'handheld',
					'container_class'	=> 'handheld-navigation',
					)
			);
			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}

function woot_post_thumb() {
?>
	<div class="blog-thumb">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'woot-thumb-400', array( 'itemprop' => 'image' ) );
		}
		?>
	</div>
<?php
}

function woot_post_header() {
?>
	<header class="entry-header">
		<?php

			the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );

		?>
		<div class="post-meta">
			<?php storefront_posted_on(); ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'woot' ) );

			if ( $categories_list && storefront_categorized_blog() ) : ?>
				<span class="cat-links">
					<?php
					echo '<span class="screen-reader-text">' . esc_attr( __( 'Categories: ', 'woot' ) ) . '</span>';
					echo wp_kses_post( $categories_list );
					?>
				</span>
			<?php endif; // End if categories ?>
			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'woot' ) );

			if ( $tags_list ) : ?>
				<span class="tags-links">
					<?php
					echo '<span class="screen-reader-text">' . esc_attr( __( 'Tags: ', 'woot' ) ) . '</span>';
					echo wp_kses_post( $tags_list );
					?>
				</span>
			<?php endif; // End if $tags_list ?>

			<?php endif; // End if 'post' == get_post_type() ?>
		</div>

	</header><!-- .entry-header -->
<?php
}

function woot_post_content() {
	?>

	<?php

	the_content(
		sprintf(
			__( 'Continue reading %s', 'woot' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		)
	);

	wp_link_pages( array(
		'before' => '<div class="page-links">' . __( 'Pages:', 'woot' ),
		'after'  => '</div>',
	) );
	?>

	<?php
}

if ( ! function_exists( 'storefront_product_categories' ) ) {
	/**
	 * Display Product Categories
	 * Hooked into the `homepage` action in the homepage template
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_product_categories( $args ) {

		if ( is_woocommerce_activated() ) {

			?>
			<section class="storefront-product-section storefront-product-categories">
				<div class="woocommerce columns-3">
				<?php
					do_action( 'storefront_homepage_before_product_categories' );

					$args = array(
					'number'			=> 3,
					'child_categories' 	=> 0,
					'orderby' 			=> 'name',
					);

					$product_categories = get_terms( 'product_cat', $args );
					$count = count($product_categories);
					if ( $count > 0 ){ ?>

					<ul class='products'>

					<?php foreach ( $product_categories as $product_category ) {

				    // get the thumbnail id user the term_id
				    $thumbnail_id = get_woocommerce_term_meta( $product_category->term_id, 'thumbnail_id', true );
				    // get the image URL
				    $image = wp_get_attachment_url( $thumbnail_id );


					?>
					<?php if($image): ?>
					<li class="product-category product" style="background-image:url(<?php echo $image; ?>);">
					<?php else: ?>
					<li class="product-category product" style="background-image:url(<?php echo woocommerce_placeholder_img_src(); ?>);">
					<?php endif; ?>
						<a href="<?php echo get_term_link( $product_category ); ?>" class="cat-details">
							<h3><?php echo  $product_category->name; ?></h3>
							<?php echo $product_category->description; ?>
						</a>
						<a href="<?php echo get_term_link( $product_category ); ?>"><span class="overlay"></span></a>
					</li>

					<?php
					}
					echo "</ul>";
					}

					do_action( 'storefront_homepage_after_product_categories' );
				?>
				</div>
			</section>
			<?php
		}
	}
}

?>
