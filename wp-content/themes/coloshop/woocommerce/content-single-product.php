<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
global $woocommerce;
//free ship
$shipping_methods = $woocommerce->shipping->load_shipping_methods();
//
?>
<div class="col-md-9">
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>

		<div class="row">
			<div class="col-lg-5">
				<div class="single_product_pics">
						
					<?php woocommerce_show_product_images(); ?>

				</div>
			</div>

			<div class="col-lg-7">
				
				<div class="product_details">
					<div class="product_details_title">
						<?php woocommerce_template_single_title(); ?>
						<?php woocommerce_template_single_excerpt(); ?>
					</div>
					<!-- <?php if($shipping_methods['free_shipping']->enabled == "yes"): ?>
						<div class="free_delivery d-flex flex-row align-items-center justify-content-center">
							<span class="ti-truck"></span><span>free delivery</span>
						</div>
					<?php endif; ?> -->
					

					<?php woocommerce_template_single_price(); ?>
					
					<?php woocommerce_template_single_sharing(); ?>



					<?php woocommerce_template_single_add_to_cart(); ?>
					
					<?php shop_woocomerce_template_loop_wishlist(); ?>
					
					<?php woocommerce_template_single_rating();
						/**
						 * Hook: woocommerce_single_product_summary.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						//do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<?php
				/**
				 * Hook: woocommerce_after_single_product_summary.
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_upsell_display - 15
				 * @hooked woocommerce_output_related_products - 20
				 */
				do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>
	</div>
</div>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php do_action( 'woocommerce_after_single_product' ); ?>