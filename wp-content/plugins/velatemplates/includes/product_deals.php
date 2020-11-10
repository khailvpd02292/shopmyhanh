<?php
add_action('widgets_init', 'vela_product_deals_load_widgets');

function vela_product_deals_load_widgets()
{
	register_widget('Vela_Product_Deals_Widget');
}

if( !class_exists('Vela_Product_Deals_Widget') ){
	class Vela_Product_Deals_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'vela-product-deals-widget', 'description' => esc_html__('Display your product deals', 'velatemplates'));
			parent::__construct('vela_product_deals', esc_html__('Vela - Product Deals', 'velatemplates'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			if( !in_array("woocommerce/woocommerce.php", apply_filters('active_plugins', get_option('active_plugins'))) ){
				return;
			}
			
			global $post, $product;
			
			extract($args);
			$title 				= apply_filters('widget_title', $instance['title']);
			$limit 				= ($instance['limit'] != 0)?absint($instance['limit']):5;
			$product_type 		= isset($instance['product_type'])?$instance['product_type']:'recent';
			$product_cats 		= $instance['product_cats'];
			$show_thumbnail 	= empty($instance['show_thumbnail'])?0:$instance['show_thumbnail'];
			$show_counter 		= empty($instance['show_counter'])?0:$instance['show_counter'];
			$show_categories 	= empty($instance['show_categories'])?0:$instance['show_categories'];
			$show_product_title = empty($instance['show_product_title'])?0:$instance['show_product_title'];
			$show_price 		= empty($instance['show_price'])?0:$instance['show_price'];
			$show_rating 		= empty($instance['show_rating'])?0:$instance['show_rating'];
			$show_add_to_cart 	= empty($instance['show_add_to_cart'])?0:$instance['show_add_to_cart'];
			$is_slider 			= $instance['is_slider'];
			$show_nav 			= empty($instance['show_nav'])?0:$instance['show_nav'];
			$auto_play 			= empty($instance['auto_play'])?0:$instance['auto_play'];
			
			/* Remove hook */
			$options = array(
					'show_image'		=> $show_thumbnail
					,'show_label'		=> 0
					,'show_title'		=> $show_product_title
					,'show_sku'			=> 0
					,'show_price'		=> $show_price
					,'show_short_desc'	=> 0
					,'show_categories'	=> $show_categories
					,'show_rating'		=> $show_rating
					,'show_add_to_cart'	=> $show_add_to_cart
				);
			vela_remove_product_hooks_shortcode( $options );
			if( $show_counter && function_exists('vela_template_loop_time_deals') ){
				add_action('woocommerce_after_shop_loop_item', 'vela_template_loop_time_deals', 100);			
			}
			
			$args = array(
				'post_type'				=> array('product', 'product_variation')
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> -1
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' => array(
					array(
						'key'		=> '_sale_price_dates_to'
						,'value'	=> current_time( 'timestamp' )
						,'compare'	=> '>'
						,'type'		=> 'numeric'
					)
					,array(
						'key'		=> '_sale_price_dates_to'
						,'value'	=> ''
						,'compare'	=> '!='
					)
				)
			);

			vela_filter_product_by_product_type($args, $product_type);
			
			$array_product_cats = (is_array($product_cats) && count($product_cats) > 0)? $product_cats: array();		
			
			$product_ids_on_sale = array();
			
			if( $product_type == 'top_rated' ){
				add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses') );	
			}
			
			$products = new WP_Query( $args );

			if( $product_type == 'top_rated' ){
				remove_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses') );	
			}
			
			if( $products->have_posts() ){
				while( $products->have_posts() ){
					$products->the_post();
					if( $post->post_type == 'product' ){
						if( in_array( get_post_meta($post->ID, '_visibility', true), array('catalog', 'visible') ) ){
							if( !empty($array_product_cats) ){
								$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
								$post_terms = wp_get_post_terms($post->ID, 'product_cat', array('fields' => $field_name));
								if( is_array($post_terms) ){
									$array_intersect = array_intersect($post_terms, $array_product_cats);
									if( !empty($array_intersect) ){
										$product_ids_on_sale[] = $post->ID;
									}
								}
							}
							else{
								$product_ids_on_sale[] = $post->ID;
							}
						}
					}
					else{ /* Variation product */
						$post_parent_id = $post->post_parent;
						if( in_array( get_post_meta($post_parent_id, '_visibility', true), array('catalog', 'visible') ) ){
							if( !empty($array_product_cats) ){
								$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
								$post_terms = wp_get_post_terms($post_parent_id, 'product_cat', array('fields' => $field_name));
								if( is_array($post_terms) ){
									$array_intersect = array_intersect($post_terms, $array_product_cats);
									if( !empty($array_intersect) ){
										$product_ids_on_sale[] = $post_parent_id;
									}
								}
							}
							else{
								$product_ids_on_sale[] = $post_parent_id;
							}
						}
					}
				}
			}
			
			$product_ids_on_sale = array_unique($product_ids_on_sale);
			$product_ids_on_sale = array_slice($product_ids_on_sale, 0, $limit);
			
			if( count($product_ids_on_sale) == 0 ){
				$product_ids_on_sale = array(0);
			}
			
			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> -1
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'post__in'				=> $product_ids_on_sale
			);
			
			vela_filter_product_by_product_type($args, $product_type);
			
			if( $product_type == 'top_rated' ){
				add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses') );	
			}
			
			$products = new WP_Query($args);
			
			if( $product_type == 'top_rated' ){
				remove_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses') );	
			}
			
			echo $before_widget;
			
			if( $products->have_posts() ){
			
				$num_posts = $products->post_count;
				if( $num_posts <= 1 ){
					$is_slider = false;
				}
			
				if( $title ){
					echo $before_title . $title . $after_title;
				}

				$rand_id = 'vela-product-deals-widget-wrapper-'.rand(0, 1000);
				$extra_class = '';
				$extra_class .= ($is_slider)?'vela-slider loading':'';
				$extra_class .= ($is_slider && $show_nav)?' has-navi':'';
				
				?>
				
				<div class="vela-product-deals-widget-wrapper vela-product-deals-slider-wrapper woocommerce columns-1 <?php echo esc_attr($extra_class); ?>" id="<?php echo esc_attr($rand_id); ?>">
					<?php woocommerce_product_loop_start(); ?>				

					<?php while( $products->have_posts() ): $products->the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>							
					<?php endwhile; ?>			

					<?php woocommerce_product_loop_end(); ?>
				</div>
				<?php if( $is_slider ): ?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						"use strict";
						var show_nav = <?php echo esc_js($show_nav); ?> == 1;
						var auto_play = <?php echo esc_js($auto_play); ?> == 1;
						var _this = jQuery('#<?php echo esc_js($rand_id); ?>');
						
						var owl = _this.find('.products').owlCarousel({
									loop : true
									,items : 1
									,nav : show_nav
									,navText: [,]
									,dots : false
									,navSpeed : 1000
									,slideBy: 1
									,margin: 10
									,rtl: jQuery('body').hasClass('rtl')
									,navRewind: false
									,autoplay: auto_play
									,autoplayTimeout: 5000
									,autoplayHoverPause: true
									,autoplaySpeed: false
									,mouseDrag: true
									,touchDrag: true
									,responsiveRefreshRate: 1000
									,responsive:{ /* Fix for mobile */
										0 : {
											items : 1
										}
									}
									,onInitialized: function(){
										_this.addClass('loaded').removeClass('loading');
									}
								});
					});
				</script>
				<?php
				endif;
			}
			vela_restore_product_hooks_shortcode();
			remove_action('woocommerce_after_shop_loop_item', 'vela_template_loop_time_deals', 100);
			
			echo $after_widget;
			wp_reset_postdata();
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= strip_tags($new_instance['title']);
			$instance['product_type'] 		= $new_instance['product_type'];
			$instance['product_cats'] 		= $new_instance['product_cats'];			
			$instance['limit'] 				= absint($new_instance['limit']);		
			$instance['show_thumbnail'] 	= $new_instance['show_thumbnail'];		
			$instance['show_counter'] 		= $new_instance['show_counter'];		
			$instance['show_categories'] 	= $new_instance['show_categories'];		
			$instance['show_product_title'] = $new_instance['show_product_title'];		
			$instance['show_price'] 		= $new_instance['show_price'];		
			$instance['show_rating'] 		= $new_instance['show_rating'];		
			$instance['show_add_to_cart'] 	= $new_instance['show_add_to_cart'];		
			$instance['is_slider'] 			= $new_instance['is_slider'];		
			$instance['show_nav'] 			= $new_instance['show_nav'];		
			$instance['auto_play'] 			= $new_instance['auto_play'];	
			
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'title'					=> 'Hot Deals'
				,'product_type'			=> 'recent'
				,'product_cats'			=> array()
				,'limit'				=> '5'
				,'show_thumbnail' 		=> 1
				,'show_counter' 		=> 1
				,'show_categories' 		=> 0
				,'show_product_title' 	=> 1
				,'show_price' 			=> 1
				,'show_rating' 			=> 1
				,'show_add_to_cart' 	=> 1
				,'is_slider'			=> 1
				,'show_nav' 			=> 1
				,'auto_play' 			=> 1
			);
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
			$categories = $this->get_list_categories(0);
			if( !is_array($instance['product_cats']) ){
				$instance['product_cats'] = array();
			}
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'velatemplates'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('product_type'); ?>"><?php esc_html_e('Product type', 'velatemplates'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('product_type'); ?>" name="<?php echo $this->get_field_name('product_type'); ?>">
					<option value="recent" <?php selected($instance['product_type'], 'recent'); ?>><?php esc_html_e('Recent', 'velatemplates'); ?></option>
					<option value="featured" <?php selected($instance['product_type'], 'featured'); ?>><?php esc_html_e('Featured', 'velatemplates'); ?></option>
					<option value="best_selling" <?php selected($instance['product_type'], 'best_selling'); ?>><?php esc_html_e('Best selling', 'velatemplates'); ?></option>
					<option value="top_rated" <?php selected($instance['product_type'], 'top_rated'); ?>><?php esc_html_e('Top rated', 'velatemplates'); ?></option>
					<option value="mixed_order" <?php selected($instance['product_type'], 'mixed_order'); ?>><?php esc_html_e('Mixed order', 'velatemplates'); ?></option>
				</select>
			</p>
		
			<p>
				<label><?php esc_html_e('Select categories', 'velatemplates'); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($categories as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('product_cats'); ?>[<?php esc_attr($cat->term_id); ?>]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo (in_array($cat->term_id,$instance['product_cats']))?'checked':''; ?> />
									<?php echo esc_html($cat->name); ?>
								</label>
								<?php $this->get_list_sub_categories($cat->term_id, $instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of posts to show', 'velatemplates'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" value="1" <?php echo ($instance['show_thumbnail'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php esc_html_e('Show thumbnail', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_counter'); ?>" name="<?php echo $this->get_field_name('show_counter'); ?>" value="1" <?php echo ($instance['show_counter'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_counter'); ?>"><?php esc_html_e('Show counter', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_categories'); ?>" name="<?php echo $this->get_field_name('show_categories'); ?>" value="1" <?php echo ($instance['show_categories'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_categories'); ?>"><?php esc_html_e('Show categories', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_product_title'); ?>" name="<?php echo $this->get_field_name('show_product_title'); ?>" value="1" <?php echo ($instance['show_product_title'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_product_title'); ?>"><?php esc_html_e('Show product title', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_price'); ?>" name="<?php echo $this->get_field_name('show_price'); ?>" value="1" <?php echo ($instance['show_price'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_price'); ?>"><?php esc_html_e('Show price', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_rating'); ?>" name="<?php echo $this->get_field_name('show_rating'); ?>" value="1" <?php echo ($instance['show_rating'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_rating'); ?>"><?php esc_html_e('Show rating', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_add_to_cart'); ?>" name="<?php echo $this->get_field_name('show_add_to_cart'); ?>" value="1" <?php echo ($instance['show_add_to_cart'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_add_to_cart'); ?>"><?php esc_html_e('Show add to cart button', 'velatemplates'); ?></label>
			</p>
			
			<hr/>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php echo ($instance['is_slider'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" value="1" <?php echo ($instance['show_nav'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php esc_html_e('Show navigation button', 'velatemplates'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" value="1" <?php echo ($instance['auto_play'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php esc_html_e('Auto play', 'velatemplates'); ?></label>
			</p>
			
			<?php 
		}
		
		function get_list_categories( $cat_parent_id ){
			if ( !in_array("woocommerce/woocommerce.php", apply_filters('active_plugins', get_option('active_plugins'))) ) {
				return array();
			}
			$args = array(
					'taxonomy' 			=> 'product_cat'
					,'hierarchical'		=> 1
					,'parent'			=> $cat_parent_id
					,'title_li'			=> ''
					,'child_of'			=> 0
				);
			$cats = get_categories($args);
			return $cats;
		}
		
		function get_list_sub_categories( $cat_parent_id, $instance ){
			$sub_categories = $this->get_list_categories($cat_parent_id); 
			if( count($sub_categories) > 0){
			?>
				<ul class="children">
					<?php foreach( $sub_categories as $sub_cat ){ ?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('product_cats'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['product_cats']))?'checked':''; ?> />
								<?php echo esc_html($sub_cat->name); ?>
							</label>
							<?php $this->get_list_sub_categories($sub_cat->term_id, $instance); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }
		}
	}
}

