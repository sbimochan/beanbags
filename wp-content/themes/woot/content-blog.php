<?php
/**
 * @package woot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="" itemtype="http://schema.org/BlogPosting">

	<?php
		/**
		* woot_blog_index_thumb hook
		*
		* @hooked woot_post_thumb - 10
		*/	
		do_action( 'woot_blog_index_thumb' );
	?>
	<div class="post-content-area">
	<?php
		/**
		* woot_blog_index_header hook
		*
		* @hooked woot_post_header - 10
		*/	
		do_action( 'woot_blog_index_header' );
		/**
		* woot_blog_index_content hook
		*
		* @hooked woot_post_content - 10
		*/	
		do_action( 'woot_blog_index_content' );
	?>
	</div>
	<div class="clearfix"></div>
</article><!-- #post-## -->