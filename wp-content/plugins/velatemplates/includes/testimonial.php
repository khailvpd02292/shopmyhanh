<?php
add_action('widgets_init', 'vela_testimonial_load_widgets');

function vela_testimonial_load_widgets()
{
	register_widget('Vela_Testimonial_Widget');
}

if( !class_exists('Vela_Testimonial_Widget') ){
	class Vela_Testimonial_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'vela-testimonial-widget', 'description' => esc_html__('Display the testimonials', 'velatemplates'));
			parent::__construct('vela_testimonial', esc_html__('Vela - Testimonial', 'velatemplates'), $widgetOps);
		}

		function widget( $args, $instance ) {
			extract($args);
			
			if( ! shortcode_exists('vela_testimonial') ){
				return;
			}
			
			$title = apply_filters('widget_title', $instance['title']);
			
			$categories 	= (!empty($instance['categories']) && is_array($instance['categories']))?implode(',', $instance['categories']):'';
			$is_slider 		= empty($instance['is_slider'])?0:(int) $instance['is_slider'];
			$show_nav 		= empty($instance['show_nav'])?0:(int) $instance['show_nav'];
			$show_dots 		= empty($instance['show_dots'])?0:(int) $instance['show_dots'];
			$auto_play 		= empty($instance['auto_play'])?0:(int) $instance['auto_play'];
			
			$shortcode_content = '[vela_testimonial ';
			$shortcode_content .= ' categories="'.$categories.'"';
			$shortcode_content .= ' per_page="'.$instance['per_page'].'"';
			$shortcode_content .= ' ids="'.$instance['ids'].'"';
			$shortcode_content .= ' excerpt_words="'.$instance['excerpt_words'].'"';
			$shortcode_content .= ' is_slider="'.$is_slider.'"';
			$shortcode_content .= ' show_nav="'.$show_nav.'"';
			$shortcode_content .= ' show_dots="'.$show_dots.'"';
			$shortcode_content .= ' auto_play="'.$auto_play.'"';
			$shortcode_content .= ']';
			
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title;
			}
			
			echo do_shortcode($shortcode_content);
			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 						= strip_tags($new_instance['title']);
			$instance['categories'] 				= $new_instance['categories'];
			$instance['per_page'] 					= $new_instance['per_page'];
			$instance['ids'] 						= $new_instance['ids'];
			$instance['excerpt_words'] 				= $new_instance['excerpt_words'];
			$instance['is_slider'] 					= $new_instance['is_slider'];
			$instance['show_nav'] 					= $new_instance['show_nav'];
			$instance['show_dots'] 					= $new_instance['show_dots'];
			$instance['auto_play'] 					= $new_instance['auto_play'];
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'title'						=> 'Testimonial'
				,'categories'				=> array()
				,'per_page'					=> 4
				,'ids'						=> ''
				,'excerpt_words'			=> 30
				,'is_slider'				=> 1
				,'show_nav'					=> 1
				,'show_dots'				=> 0
				,'auto_play'				=> 1
			);
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
			
			$categories = $this->get_list_categories(0);
			if( !is_array($instance['categories']) ){
				$instance['categories'] = array();
			}
			
			$title 			= esc_attr($instance['title']);
			$per_page 		= esc_attr($instance['per_page']);
			$ids 			= esc_attr($instance['ids']);
			$excerpt_words 	= esc_attr($instance['excerpt_words']);
			$is_slider 		= esc_attr($instance['is_slider']);
			$show_nav 		= esc_attr($instance['show_nav']);
			$show_dots 		= esc_attr($instance['show_dots']);
			$auto_play 		= esc_attr($instance['auto_play']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'velatemplates'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label><?php esc_html_e('Select categories', 'velatemplates'); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($categories as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($cat->term_id); ?>]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo (in_array($cat->term_id,$instance['categories']))?'checked':''; ?> />
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
				<label for="<?php echo $this->get_field_id('per_page'); ?>"><?php esc_html_e('Limit', 'velatemplates'); ?> </label>
				<input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('per_page'); ?>" name="<?php echo $this->get_field_name('per_page'); ?>" value="<?php echo esc_attr($per_page); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('ids'); ?>"><?php esc_html_e('Testimonial IDs', 'velatemplates'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>" value="<?php echo esc_attr($ids); ?>" />
				<span class="description"><?php esc_html_e('A comma separated list of testimonial ids', 'velatemplates'); ?></span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('excerpt_words'); ?>"><?php esc_html_e('Number of words in excerpt','velatemplates'); ?> </label>
				<input class="widefat" type="number" min="0" id="<?php echo $this->get_field_id('excerpt_words'); ?>" name="<?php echo $this->get_field_name('excerpt_words'); ?>" value="<?php echo esc_attr($excerpt_words); ?>" />
			</p>
			<hr/>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php checked($is_slider, 1); ?> />
				<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'velatemplates'); ?> </label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" value="1" <?php checked($show_nav, 1); ?> />
				<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php esc_html_e('Show navigation button', 'velatemplates'); ?> </label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_dots'); ?>" name="<?php echo $this->get_field_name('show_dots'); ?>" value="1" <?php checked($show_dots, 1); ?> />
				<label for="<?php echo $this->get_field_id('show_dots'); ?>"><?php esc_html_e('Show pagination dots (remove navigation button)', 'velatemplates'); ?> </label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" value="1" <?php checked($auto_play, 1); ?> />
				<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php esc_html_e('Auto play', 'velatemplates'); ?> </label>
			</p>
			<?php 
		}
		
		function get_list_categories( $cat_parent_id ){
			$args = array(
					'taxonomy' 			=> 'vela_testimonial_cat'
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
								<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['categories']))?'checked':''; ?> />
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

