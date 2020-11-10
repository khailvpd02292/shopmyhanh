<?php
/**
 * Blog widget
 *
 * @package Leto
 */

class Shop_Blog extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'shop_blog_widget', 'description' => __( 'Show the latest news from your blog.', 'shop') );
        parent::__construct(false, $name = __('Sangtran: Blog', 'shop'), $widget_ops);
		$this->alt_option_name = 'shop_blog_widget';
		
    }
	
	function form($instance) {
		$title     			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$category  			= isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';			
		$number   			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
	?>

	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'shop'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Pick a category to display posts from:', 'shop'); ?></label>
		<?php $args = array(
			'show_option_none'   => __( 'Show posts from all categories', 'shop'),
			'name'               => $this->get_field_name('category'),
			'id'                 => $this->get_field_id('category'),
			'class'              => 'chosen-dropdown',
			'selected'			 => $category,
		); ?>
       	<?php wp_dropdown_categories($args); ?>
    </p>  

	<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'shop' ); ?></label>
	<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field($new_instance['title']);
		$instance['category'] 		= sanitize_text_field( $new_instance['category'] );
		$instance['number'] 		= (int) $new_instance['number'];		  
		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$category = isset( $instance['category'] ) ? $instance['category'] : '';

		if ( $category == -1 ) {
			$cat = '';
		} else {
			$cat = $category;
		}


		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		if ( ! $number )
			$number = 3;
		
		$r = new WP_Query( array(
			'post_type' => 'post',
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'posts_per_page'	  => $number,
			'cat'		  		  => $cat,		
			'ignore_sticky_posts' => true
		) );
		//var_dump($r);
		echo $args['before_widget'];
		
		if ($r->have_posts()) :
		?>

			
			<div class="row">
				<div class="col text-center">
					<div class="section_title show_arrivals_title">
						<h2><?php echo $title; ?></h2>
					</div>
				</div>
			</div>

			<div class="row blogs_container">
				<?php while ( $r->have_posts() ) : $r->the_post();?>
				<?php 
					$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
				?>
				<div class="col-sm-4 col-md-4 blog_item_col">
					<div class="blog_item">
						<div class="blog_background" style="background-image:url(<?php echo $feat_image; ?>)"></div>
						<div class="blog_content align-items-nav text-center">
							
							<h4 class="blog_title"><?php the_title() ; ?></h4> 
							<span class="blog_meta"><?php the_author(); ?> | <?php the_time('d/m/Y'); ?></span>
							<a class="blog_more" href="<?php echo esc_url( get_permalink() ); ?>">Read more</a>
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
		
			<?php wp_reset_postdata(); ?>
			<?php

		endif;

		echo $args['after_widget'];


	}
	
}