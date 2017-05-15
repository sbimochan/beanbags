<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package woot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * @hooked storefront_page_content - 10
	 */
	do_action( 'woot_page' );
	?>
</article><!-- #post-## -->