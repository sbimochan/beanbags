<?php
// Woo Product Slider Pro Widget

function woo_product_slider_free_widget() {
	register_widget( 'woo_product_slider_free_widget_content' );
}
add_action( 'widgets_init', 'woo_product_slider_free_widget' );


class woo_product_slider_free_widget_content extends WP_Widget {


	public function __construct() {
		parent::__construct( 'woo_product_slider_free_widget_content', __( 'Woo Product Slider', 'woo_product_slider' ),
			array(
				'description' => __( 'This widget is to display latest / recent products.', 'woo_product_slider' )
			)
		);
	}


	/*-------------------------------------------------------
	 *				Front-end display of widget
	 *-------------------------------------------------------*/

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$id            = $instance['id'];
		$auto_play     = $instance['auto_play'];
		$nav           = $instance['nav'];
		$pagination    = $instance['pagination'];
		$stop_on_hover = $instance['stop_on_hover'];
		$color         = $instance['color'];

		echo $before_widget;

		$output = '';

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		$output .= '<div class="sp-wps-widget">';
		$output .= do_shortcode( "[woo-product-slider items='1' title='' id='" . $id . "' color='" . $color . "' pagination='" . $pagination . "' nav='" . $nav . "' auto_play='" . $auto_play . "' stop_on_hover='" . $stop_on_hover . "' ]" );
		$output .= '</div>';

		echo $output;

		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['id']            = strip_tags( $new_instance['id'] );
		$instance['auto_play']     = strip_tags( $new_instance['auto_play'] );
		$instance['nav']           = strip_tags( $new_instance['nav'] );
		$instance['pagination']    = strip_tags( $new_instance['pagination'] );
		$instance['stop_on_hover'] = strip_tags( $new_instance['stop_on_hover'] );
		$instance['color']         = strip_tags( $new_instance['color'] );

		return $instance;
	}


	function form( $instance ) {
		$defaults = array(
			'title'         => 'Latest Product',
			'id'            => '123',
			'auto_play'     => 'true',
			'nav'           => 'true',
			'pagination'    => 'true',
			'stop_on_hover' => 'true',
			'color'         => '#e74c3c'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title:', 'woo_product_slider' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo $instance['title']; ?>" style="width:100%;"/>
		</p>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php _e( 'Unique ID:', 'woo_product_slider' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>"
			       value="<?php echo $instance['id']; ?>" type="number" style="width: 60px;margin-left: 8px;"/>
		</p>

		<p style="line-height: 40px;">
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"><?php _e( 'Brand Color:', 'woo_product_slider' ); ?></label>
			<input type="text" class="sp-wps-brand-color<?php echo $instance['id']; ?>"
			       id="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'color' ) ); ?>"
			       value="<?php echo esc_attr( $instance['color'] ); ?>" style="margin-left: 8px;"/>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'auto_play' ); ?>"><?php _e( 'Auto Play:', 'woo_product_slider' ); ?></label>
			<?php
			$options = array(
				'true'  => __( 'Yes', 'woo_product_slider' ),
				'false' => __( 'No', 'woo_product_slider' )
			);
			if ( isset( $instance['auto_play'] ) ) {
				$order_by = $instance['auto_play'];
			} ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'auto_play' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'auto_play' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ( $options as $key => $value ) {

					if ( $order_by === $key ) {
						printf( $op, $key, ' selected="selected"', $value );
					} else {
						printf( $op, $key, '', $value );
					}
				}
				?>
			</select>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'nav' ); ?>"><?php _e( 'Navigation:', 'woo_product_slider' ); ?></label>
			<?php
			$options = array(
				'true'  => __( 'Show', 'woo_product_slider' ),
				'false' => __( 'Hide', 'woo_product_slider' )
			);
			if ( isset( $instance['nav'] ) ) {
				$order_by = $instance['nav'];
			} ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nav' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'nav' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ( $options as $key => $value ) {

					if ( $order_by === $key ) {
						printf( $op, $key, ' selected="selected"', $value );
					} else {
						printf( $op, $key, '', $value );
					}
				}
				?>
			</select>
		</p>

		<p class="sp_wps_pagination_type<?php echo $instance['id']; ?>">
			<label
				for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e( 'Pagination:', 'woo_product_slider' ); ?></label>
			<?php
			$options = array(
				'true'  => __( 'Show', 'woo_product_slider' ),
				'false' => __( 'Hide', 'woo_product_slider' )
			);
			if ( isset( $instance['pagination'] ) ) {
				$order_by = $instance['pagination'];
			} ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pagination' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'pagination' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ( $options as $key => $value ) {

					if ( $order_by === $key ) {
						printf( $op, $key, ' selected="selected"', $value );
					} else {
						printf( $op, $key, '', $value );
					}
				}
				?>
			</select>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'stop_on_hover' ); ?>"><?php _e( 'Stop On Hover:', 'woo_product_slider' ); ?></label>
			<?php
			$options = array(
				'true'  => __( 'Yes', 'woo_product_slider' ),
				'false' => __( 'No', 'woo_product_slider' )
			);
			if ( isset( $instance['stop_on_hover'] ) ) {
				$order_by = $instance['stop_on_hover'];
			} ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'stop_on_hover' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'stop_on_hover' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ( $options as $key => $value ) {

					if ( $order_by === $key ) {
						printf( $op, $key, ' selected="selected"', $value );
					} else {
						printf( $op, $key, '', $value );
					}
				}
				?>
			</select>
		</p>


		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('.sp-wps-brand-color<?php echo $instance['id']; ?>').wpColorPicker();
			})
		</script>


		<?php
	}
}