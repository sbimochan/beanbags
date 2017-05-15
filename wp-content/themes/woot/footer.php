<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package woot
 */
?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="col-full">

			<?php
			/**
			 * @hooked storefront_footer_widgets - 10
			 *
			 */
			do_action( 'woot_footer_widgets' ); ?>

		</div><!-- .col-full -->
	
		<div class="credits-area">
			<div class="col-full">
			<?php
			/**
			 * @hooked woot_credit - 20
			 *
			 */
			do_action( 'woot_credit_area' ); ?>

			</div><!-- .col-full -->

		</div>
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>