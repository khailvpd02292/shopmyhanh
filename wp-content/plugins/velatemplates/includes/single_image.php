<?php
add_action('widgets_init', 'vela_single_image_load_widgets');

function vela_single_image_load_widgets()
{
	register_widget('Vela_Single_Image_Widget');
}

if( !class_exists('Vela_Single_Image_Widget') ){
	class Vela_Single_Image_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'vela-single-image', 'description' => esc_html__('Display a single image', 'velatemplates'));
			parent::__construct('vela_single_image', esc_html__('Vela - Single Image', 'velatemplates'), $widgetOps);
		}

		function widget( $args, $instance ) {
			extract($args);
			
			if( ! shortcode_exists('vela_single_image') ){
				return;
			}
			
			$shortcode_content = '[vela_single_image ';
			$shortcode_content .= ' img_url="'.$instance['img_url'].'"';
			$shortcode_content .= ' style_effect="'.$instance['style_effect'].'"';
			$shortcode_content .= ' link="'.$instance['link'].'"';
			$shortcode_content .= ' link_title="'.$instance['link_title'].'"';
			$shortcode_content .= ' target="'.$instance['target'].'"';
			$shortcode_content .= ']';
			
			$before_title = '<h3 class="widget-title heading-title hidden">';
			$after_title = '</h3>';
			
			echo $before_widget;
			
			echo $before_title . esc_html($instance['link_title']) . $after_title;
			
			echo do_shortcode($shortcode_content);
			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['img_url'] 				= $new_instance['img_url'];		
			$instance['style_effect'] 			= $new_instance['style_effect'];	
			$instance['link'] 					= $new_instance['link'];	
			$instance['link_title'] 			= $new_instance['link_title'];	
			$instance['target'] 				= $new_instance['target'];	
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'img_url'			=> ''
				,'style_effect'		=> ''
				,'link' 			=> '#'
				,'link_title' 		=> ''						
				,'target' 			=> '_blank'
			);
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
		?>
			<p>
				<label for="<?php echo $this->get_field_id('link'); ?>"><?php esc_html_e('Link','velatemplates'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo esc_attr($instance['link']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('link_title'); ?>"><?php esc_html_e('Link title','velatemplates'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('link_title'); ?>" name="<?php echo $this->get_field_name('link_title'); ?>" value="<?php echo esc_attr($instance['link_title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('target'); ?>"><?php esc_html_e('Target','velatemplates'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>">
					<option value="_blank" <?php selected('_blank', $instance['target']) ?>><?php esc_html_e('New Window Tab', 'velatemplates') ?></option>
					<option value="_self" <?php selected('_self', $instance['target']) ?>><?php esc_html_e('Self', 'velatemplates') ?></option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('img_url'); ?>"><?php esc_html_e('Image URL','velatemplates'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('img_url'); ?>" name="<?php echo $this->get_field_name('img_url'); ?>" value="<?php echo esc_attr($instance['img_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('style_effect'); ?>"><?php esc_html_e('Style Effect','velatemplates'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('style_effect'); ?>" id="<?php echo $this->get_field_id('style_effect'); ?>">
					<option value="eff-widespread-corner-left-right" <?php selected('eff-widespread-corner-left-right', $instance['style_effect']) ?>><?php esc_html_e('Widespread Corner', 'velatemplates') ?></option>
					<option value="eff-border-scale" <?php selected('eff-border-scale', $instance['style_effect']) ?>><?php esc_html_e('Border Scale', 'velatemplates') ?></option>
					<option value="eff-fade" <?php selected('eff-fade', $instance['style_effect']) ?>><?php esc_html_e('Fade', 'velatemplates') ?></option>
				</select>
			</p>
			
			<?php 
		}
	}
}

