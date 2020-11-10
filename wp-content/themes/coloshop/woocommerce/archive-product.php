<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<?php if(is_shop() || is_product_category() || is_product_tag() ): ?>
<style type="text/css" media="screen">
	.product-loop-filters li{
   	 /*	width: 25% !important;*/
   	 	background: #f5f5f5 none repeat scroll 0 0;
   	 	padding: 10px 10px !important;
		text-align: center;
	}
</style>
<?php endif; ?>
<div id="shop-primary" >
	<div class="container">
		<div class="row">
			<div id="site-main" class="site-main <?php echo $cols; ?>">
				<!-- <div class="col-md-3">
					<?php echo do_shortcode('[wpf-filters id=7]'); ?>
				</div> 
				<div class="col-md-9"> -->
				<div class="col-md-12">
					<?php
						if ( woocommerce_product_loop() ) {

							/**
							 * Hook: woocommerce_before_shop_loop.
							 *
							 * @hooked woocommerce_output_all_notices - 10
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */
							do_action( 'woocommerce_before_shop_loop' );

							woocommerce_product_loop_start();

							$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
							$wp_query = new WP_Query( array(
										'post_type' => 'product',
										'post_status'         => 'publish',
										'posts_per_page'   	  => 6,
										'paged' => $paged
									) );
									//var_dump($r);


							if ($wp_query->have_posts()) :
								
								while ( $wp_query->have_posts() ) : $wp_query->the_post();
									do_action( 'woocommerce_shop_loop' );

									wc_get_template_part( 'content', 'product' );
									
								endwhile; 

								
								wp_reset_postdata(); 
								numbered_pagination();
							endif;

							

							woocommerce_product_loop_end();

							/**
							 * Hook: woocommerce_after_shop_loop.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
						} else {
							/**
							 * Hook: woocommerce_no_products_found.
							 *
							 * @hooked wc_no_products_found - 10
							 */
							do_action( 'woocommerce_no_products_found' );
						}?>
				</div>
				
			</div>
		</div>
	</div>
</div>


<?php

get_footer( 'shop' );




