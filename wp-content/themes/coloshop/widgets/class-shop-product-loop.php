<?php
/**
 * Product loop widget
 *
 * @package Leto
 */

class Shop_Product_Loop extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'shop_product_loop', 'description' => __( 'Display products in a grid.', 'shop') );
		parent::__construct(false, $name = __('sangtran: Product loop', 'shop'), $widget_ops);
		$this->alt_option_name = 'shop_product_loop';
	}
	
	function form($instance) {
		$title     		= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$includes       = isset( $instance['includes'] ) ? array_map( 'esc_attr', $instance['includes'] ) : '';
		$number         = isset( $instance['number'] ) ? intval( $instance['number'] ) : 6;
		$show_filter    = isset( $instance['show_filter'] ) ? (bool) $instance['show_filter'] : true;
		$mode  			= isset( $instance['mode'] ) ? esc_attr( $instance['mode'] ) : 'grid';
		$orderby  		= isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'date';
		$button_url		= isset( $instance['button_url'] ) ? esc_url( $instance['button_url'] ) : '';
		$button_text	= isset( $instance['button_text'] ) ? esc_attr( $instance['button_text'] ) : '';
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'shop'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('includes'); ?>"><?php _e('Choose the categories you wish to display products from:', 'shop'); ?></label>
	        <select data-placeholder="<?php echo __('Select the categories you wish to display posts from.', 'shop'); ?>" multiple="multiple"  name="<?php echo $this->get_field_name('includes'); ?>[]" id="<?php echo $this->get_field_id('includes'); ?>" class="widefat category-select chosen-container chosen-container-multi" >
			<?php
			$cats = get_categories( array('taxonomy' => 'product_cat' ) );
			foreach( $cats as $cat ) : ?>
	                <?php printf(
	                    '<option value="%s" %s>%s</option>',
	                    $cat->cat_ID,
	                    in_array( $cat->cat_ID, (array)$includes) ? 'selected="selected"' : '',
	                    $cat->cat_name
	                );?>
	               <?php endforeach; ?>
	       	</select>
	      
		</p>  
	   <p><input class="checkbox" type="checkbox" <?php checked( $show_filter ); ?> id="<?php echo $this->get_field_id( 'show_filter' ); ?>" name="<?php echo $this->get_field_name( 'show_filter' ); ?>" />
	   <label for="<?php echo $this->get_field_id( 'show_filter' ); ?>"><?php _e( 'Show navigation filter? (category slugs must be specified)', 'shop' ); ?></label></p>
	   <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of products to show:', 'shop' ); ?></label>
	   <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo $number; ?>" size="3" /></p>
		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order products by', 'shop'); ?></label>
	        <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">		
				<option value="date" <?php selected( $orderby, 'date' ); ?>><?php echo __('Date', 'shop'); ?></option>
				<option value="meta_value_num" <?php selected( $orderby, 'meta_value_num' ); ?>><?php echo __('Best sellers', 'shop'); ?></option>
				<option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php echo __('Random', 'shop'); ?></option>
	       	</select>
	    </p>

	    <p><label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e('Select item', 'shop'); ?></label>
	        <select name="<?php echo $this->get_field_name('mode'); ?>" id="<?php echo $this->get_field_id('mode'); ?>">		
				<option value="grid" <?php selected( $mode, 'grid' ); ?>><?php echo __('Grid', 'shop'); ?></option>
				<option value="slider" <?php selected( $mode, 'slider' ); ?>><?php echo __('Slider', 'shop'); ?></option>
				<!-- <option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php echo __('Random', 'shop'); ?></option> -->
	       	</select>
	    </p>

	    <p><em><?php echo esc_attr__( 'You can add a button under the products', 'shop' ); ?></em></p>
	   <p><label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e( 'Button URL', 'shop' ); ?></label>
	   <input class="widefat" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" type="url" value="<?php echo $button_url; ?>" size="3" /></p>    
	   <p><label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text', 'shop' ); ?></label>
	   <input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text" value="<?php echo $button_text; ?>" size="3" /></p>    
	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field($new_instance['title']);
		$instance['includes'] 		= array_map( 'sanitize_text_field', (array)$new_instance['includes'] );			
		$instance['number']         = intval($new_instance['number']);
		$instance['show_filter']    = isset( $new_instance['show_filter'] ) ? (bool) $new_instance['show_filter'] : false;
	    $instance['mode'] 			= sanitize_text_field($new_instance['mode']);
	    $instance['orderby'] 		= sanitize_text_field($new_instance['orderby']);
	    $instance['button_url'] 	= esc_url_raw($new_instance['button_url']);
	    $instance['button_text'] 	= sanitize_text_field($new_instance['button_text']);


		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$includes       = isset( $instance['includes'] ) ? array_map( 'esc_attr', $instance['includes'] ) : '';

    	if ( !empty( $includes ) ) {

    		$includes = array_filter( $includes );
    		$tax_query = array(                           
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $includes,
					)
				);
    	} else {
    		$tax_query = array();
    	}

	    $number        	= ( ! empty( $instance['number'] ) ) ? intval( $instance['number'] ) : 6;
	    if ( ! $number ) {
	      $number = 6;
	    } 
	    $show_filter   	= isset( $instance['show_filter'] ) ? (bool) $instance['show_filter'] : true;
		$mode 			= !empty( $instance['mode'] && $instance['mode'] != "" ) ? esc_html($instance['mode']) : 'grid';
		//var_dump(esc_html($instance['mode']) );
		$orderby 		= isset( $instance['orderby'] ) ? esc_html($instance['orderby']) : 'date';
		$button_url 	= isset( $instance['button_url'] ) ? esc_url($instance['button_url']) : '';
		$button_text 	= isset( $instance['button_text'] ) ? esc_html($instance['button_text']) : '';


		$query = new WP_Query( array (
			'post_type' => 'product',
			'posts_per_page' => $number,
			'category_name' => '',
			'meta_key' => 'total_sales',
			'orderby' => $orderby,
			'tax_query' => $tax_query
		) );

		echo $args['before_widget'];

		if ( $query->have_posts() ) : ?>

			<!-- <?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?> -->

			<div class="shop_arrivals shop-product-grid  wrap-<?php echo $mode; ?>">
				<div class="container">
					<div class="row">
						<div class="col text-center">
							<div class="section_title show_arrivals_title">
								<h2><?php echo $title; ?></h2>
							</div>
						</div>
					</div>
				

					<?php if ( $mode == 'grid' ) : ?>
					    <?php //Begin product category filter
					      	if ( $includes && $show_filter == true ) :
					         	$included_ids = array();

						        foreach( $includes as $term ) {
						            $term_obj = get_term_by( 'id', $term, 'product_cat');
						            if (is_object($term_obj)) {
										$term_id  = $term_obj->term_id;
										$included_ids[] = $term_id;
						            }
						        }

						        $id_string = implode( ',', $included_ids );
						        $terms = get_terms( 'product_cat', array( 'include' => $id_string ) );
					            $class = 'col-xs-6 col-sm-4 product-items';
					    ?>
						<div class="row align-items-center">
							<div class="col text-center">
								<div class="shop_arrivals_sorting">	
									<ul class="shop-filter-categories clearfix button-group filters-button-group">
										<li class="shop_sorting_button  align-items-center active " data-filter="*"><a href="#">TẤT CẢ</a></li>
										<?php $count = count($terms);
											if ( $count > 0 ){
												foreach ( $terms as $term ) { ?>
													<li class="shop_sorting_button  align-items-center is-checked" data-filter=".<?php echo $term->slug; ?>"><a href='#' ><?php echo $term->name; ?></a></li>
											<?php }
										} ?>
									</ul>
								</div>		
							</div>
						</div>	
						<?php endif; ?>
					<?php endif; ?>
					
					<div class="row">	
						<div class="col text-center shop-slider-product">					
							<div class="shop-filter-products <?php echo ($mode == 'grid') ? ' ' : ' owl-carousel'; ?>  woocommerce  products <?php echo $mode;?>" data-isotope='{ "itemSelector": ".product-items", "layoutMode": "fitRows" }'>
						
							<?php $i=0; while ( $query->have_posts() ) : $query->the_post(); ?>

								<?php 
									global $post;
									$id = $post->ID;
									$product = wc_get_product(get_the_ID());
									$termsArray = get_the_terms( $id, 'product_cat' );
									$termsString = "";
									$i++;
									if ( $termsArray) {
										foreach ( $termsArray as $term ) {
											$termsString .= $term->slug;
										}
									}
								?>
				
								<div class="<?php echo $class; ?> main_items <?php echo $termsString;?> " data-type="<?php echo $termsString;?>"  data-id="id-<?php echo $i;?>"> 
									<div class="product-inner">
										<?php if ( has_post_thumbnail() ) : ?>
										<div class="product-thumb">
											<a href="<?php the_permalink(); ?>" class="product-thumb__link">
												<?php echo woocommerce_get_product_thumbnail(); ?>
											</a>
										</div>
										<?php endif; ?>
										<?php if($product->is_on_sale()): ?>
											<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span><?php echo esc_html__( 'Sale!', 'woocommerce' ); ?></span></div>
										<?php endif; ?>
										<?php  ?>
											<div class="yth-favorite">
												<?php shop_woocomerce_template_loop_wishlist(); ?>
											</div>
										<?php  ?>
										<div class="product-details">
											<?php woocommerce_template_loop_rating(); ?>
											<h6 class="product-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h6>
											<div class="product-price-button">
												<span class="product-price">
													<?php woocommerce_template_single_price(); ?>
												</span>
												
											</div>
											<?php //get_template_part( 'template-parts/woocommerce', 'modal' ); ?>
										</div>

									</div>
									<!-- <div class="product-button">
										<a href="#modal-quickview" class="product-quickview"><?php esc_html_e( 'Show more', 'leto' ); ?></a>
									</div> -->
									<?php if( !($mode == 'slider') ): ?>
									<div class="red_button  shop_add_to_cart_button">
										<?php woocommerce_template_loop_add_to_cart(); ?>
									</div>
									<!-- <script type="text/javascript">jQuery(document).ready(function($){ $(".red_button a.add_to_cart_button").click(function(){
										$(this).hide(5000);
									})});</script> -->
									<?php endif;?>
								</div>

							<?php endwhile; ?>

							</div>
							<?php if($mode == 'slider') : ?>
								<div class="product_slider_nav_left product_slider_nav align-items-nav">
									<i class="fa fa-chevron-left" aria-hidden="true"></i>
								</div>
								<div class="product_slider_nav_right product_slider_nav align-items-nav">
									<i class="fa fa-chevron-right" aria-hidden="true"></i>
								</div>
							<?php endif;?>
							<?php if ( $button_url ) : ?>
							<a href="<?php echo $button_url; ?>" class="button-underline"><?php echo $button_text; ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div><!-- /.best-seller-section -->


	<?php
		wp_reset_postdata();
		endif;
		echo $args['after_widget'];
	}
	
}