<?php 
/*** Vela Testimonial ***/
if( !class_exists('Vela_Testimonials') ){
	class Vela_Testimonials{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'vela_testimonial';
			$this->thumb_size_name = 'vela_testimonial_thumb';
			$this->thumb_size_array = array(150, 150);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_filter('enter_title_here', array($this, 'enter_title_here'));
				add_filter('manage_'.$this->post_type.'_posts_columns', array($this, 'custom_column_headers'), 10);
				add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'custom_columns'), 10, 2);
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Testimonials', 'post type general name', 'velatemplates' ),
				'singular_name' 	=> esc_html_x( 'Testimonial', 'post type singular name', 'velatemplates' ),
				'add_new' 			=> esc_html_x( 'Add New', 'testimonial', 'velatemplates' ),
				'add_new_item' 		=> esc_html__( 'Add New Testimonial', 'velatemplates' ),
				'edit_item' 		=> esc_html__( 'Edit Testimonial', 'velatemplates' ),
				'new_item' 			=> esc_html__( 'New Testimonial', 'velatemplates' ),
				'all_items' 		=> esc_html__( 'All Testimonials', 'velatemplates' ),
				'view_item' 		=> esc_html__( 'View Testimonial', 'velatemplates' ),
				'search_items' 		=> esc_html__( 'Search Testimonials', 'velatemplates' ),
				'not_found' 		=> esc_html__( 'No Testimonials Found', 'velatemplates' ),
				'not_found_in_trash'=> esc_html__( 'No Testimonials Found In Trash', 'velatemplates' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Testimonials', 'velatemplates' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> 'vela_testimonials',
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> '',
                                'menu_icon'             => 'dashicons-format-quote',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'velatemplates' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'velatemplates' ),
								'search_items'        => esc_html__( 'Search Categories', 'velatemplates' ),
								'all_items'           => esc_html__( 'All Categories', 'velatemplates' ),
								'parent_item'         => esc_html__( 'Parent Category', 'velatemplates' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'velatemplates' ),
								'edit_item'           => esc_html__( 'Edit Category', 'velatemplates' ),
								'update_item'         => esc_html__( 'Update Category', 'velatemplates' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'velatemplates' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'velatemplates' ),
								'menu_name'           => esc_html__( 'Categories', 'velatemplates' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('vela_testimonial_cat', $this->post_type, $args);
		}
		
		function enter_title_here( $title ) {
			if( get_post_type() == $this->post_type ) {
				$title = esc_html__('Enter the customer\'s name here', 'velatemplates');
			}
			return $title;
		}
		
		function get_image( $id, $size = '' ){
			$response = '';
			if( $size == '' ){
				$size = $this->thumb_size_array[0];
			}
			if ( has_post_thumbnail( $id ) ) {
				if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
					$size = array( intval( $size ), intval( $size ) );
				} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
					$size = $this->thumb_size_array;
				}
				$response = get_the_post_thumbnail( intval( $id ), $size );
			} else {
				$gravatar_email = get_post_meta( $id, 'vela_gravatar_email', true );
				if ( '' != $gravatar_email && is_email( $gravatar_email ) ) {
					$response = get_avatar( $gravatar_email, $size );
				}
			}

			return $response;
		}
		
		function custom_columns( $column_name, $id ){
			global $wpdb, $post;

			$meta = get_post_custom( $id );
			switch ( $column_name ) {
				case 'image':
					$value = '';
					$value = $this->get_image( $id, 40 );
					echo $value;
				break;
				default:
				break;

			}
		}
		
		function custom_column_headers( $defaults ){
			$new_columns = array( 'image' => esc_html__( 'Image', 'velatemplates' ) );
			$last_item = '';
			
			if( isset($defaults['date']) ) { unset($defaults['date']); }
			if( count($defaults) > 2 ) {
				$last_item = array_slice($defaults, -1);
				array_pop($defaults);
			}
			
			$defaults = array_merge($defaults, $new_columns);
			if( $last_item != '' ) {
				foreach ( $last_item as $k => $v ) {
					$defaults[$k] = $v;
					break;
				}
			}

			return $defaults;
		}
	}
}
global $vela_testimonials;
$vela_testimonials = new Vela_Testimonials();

/*** Vela Brands ***/
if( !class_exists('Vela_Brands') ){
	class Vela_Brands{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'vela_brand';
			$this->thumb_size_name = 'vela_brand_thumb';
			$size_options = get_option('vela_brand_setting', array());
			$logo_width = isset($size_options['size']['width'])?$size_options['size']['width']:240;
			$logo_height = isset($size_options['size']['height'])?$size_options['size']['height']:130;
			$this->thumb_size_array = array($logo_width, $logo_height);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_action('admin_menu', array( $this, 'register_setting_page' ));
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 			=> esc_html_x( 'Brands', 'post type general name', 'velatemplates' ),
				'singular_name' 	=> esc_html_x( 'Logo', 'post type singular name', 'velatemplates' ),
				'add_new' 		=> esc_html_x( 'Add New', 'logo', 'velatemplates' ),
				'add_new_item' 		=> esc_html__( 'Add New Logo', 'velatemplates' ),
				'edit_item' 		=> esc_html__( 'Edit Logo', 'velatemplates' ),
				'new_item' 		=> esc_html__( 'New Logo', 'velatemplates' ),
				'all_items' 		=> esc_html__( 'All Brands', 'velatemplates' ),
				'view_item' 		=> esc_html__( 'View Brand', 'velatemplates' ),
				'search_items' 		=> esc_html__( 'Search Brands', 'velatemplates' ),
				'not_found' 		=>  esc_html__( 'No Brands Found', 'velatemplates' ),
				'not_found_in_trash'=> esc_html__( 'No Brands Found In Trash', 'velatemplates' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Brands', 'velatemplates' )
			);
			$args = array(
				'labels' 		=> $labels,
				'public' 		=> true,
				'publicly_queryable'=> true,
				'show_ui' 		=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 		=> array( 'slug' => str_replace('vela_', '', $this->post_type) ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 		=> array( 'title', 'thumbnail' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> '',
                                'menu_icon'             => 'dashicons-format-gallery',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'velatemplates' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'velatemplates' ),
								'search_items'        => esc_html__( 'Search Categories', 'velatemplates' ),
								'all_items'           => esc_html__( 'All Categories', 'velatemplates' ),
								'parent_item'         => esc_html__( 'Parent Category', 'velatemplates' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'velatemplates' ),
								'edit_item'           => esc_html__( 'Edit Category', 'velatemplates' ),
								'update_item'         => esc_html__( 'Update Category', 'velatemplates' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'velatemplates' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'velatemplates' ),
								'menu_name'           => esc_html__( 'Categories', 'velatemplates' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('vela_brand_cat', $this->post_type, $args);
		}
		
		function register_setting_page(){
			add_submenu_page('edit.php?post_type='.$this->post_type, esc_html__('Logo Settings','velatemplates'), 
						esc_html__('Settings','velatemplates'), 'manage_options', 'vela-logo-settings', array($this, 'setting_page_content'));
		}
		
		function setting_page_content(){
			$options_default = array(
							'size' => array(
								'width' => 240
								,'height' => 130
								,'crop' => 1
							)
							,'responsive' => array(
								'break_point'	=> array(0, 220, 320, 500, 900, 1050, 1180)
								,'item'			=> array(1, 2, 3, 4, 5, 6, 6)
							)
						);
						
			$options = get_option('vela_brand_setting', $options_default);
			if(isset($_POST['vela_brand_save_setting'])){
				$options['size']['width'] = $_POST['width'];
				$options['size']['height'] = $_POST['height'];
				$options['size']['crop'] = $_POST['crop'];
				$options['responsive']['break_point'] = $_POST['responsive']['break_point'];
				$options['responsive']['item'] = $_POST['responsive']['item'];
				update_option('vela_brand_setting', $options);
			}
			if( isset($_POST['vela_brand_reset_setting']) ){
				update_option('vela_brand_setting', $options_default);
				$options = $options_default;
			}
			?>
			<h2 class="vela-logo-settings-page-title"><?php esc_html_e('Logo Settings','velatemplates'); ?></h2>
			<div id="vela-logo-setting-page-wrapper">
				<form method="post">
					<h3><?php esc_html_e('Image Size', 'velatemplates'); ?></h3>
					<p class="description"><?php esc_html_e('You must regenerate thumbnails after changing','velatemplates'); ?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image width','velatemplates'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="width" value="<?php echo esc_attr($options['size']['width']); ?>" />
									<p class="description"><?php esc_html_e('Input image width (In pixels)','velatemplates'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image height','velatemplates'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="height" value="<?php echo esc_attr($options['size']['height']); ?>" />
									<p class="description"><?php esc_html_e('Input image height (In pixels)','velatemplates'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Crop','velatemplates'); ?></label></th>
								<td>
									<select name="crop">
										<option value="1" <?php echo ($options['size']['crop']==1)?'selected':''; ?>>Yes</option>
										<option value="0" <?php echo ($options['size']['crop']==0)?'selected':''; ?>>No</option>
									</select>
									<p class="description"><?php esc_html_e('Crop image after uploading','velatemplates'); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<h3><?php esc_html_e('Slider Responsive Options', 'velatemplates'); ?></h3>
					<div class="responsive-options-wrapper">
						<ul>
							<?php foreach( $options['responsive']['break_point'] as $k => $break){ ?>
							<li>
								<label><?php esc_html_e('Breakpoint from','velatemplates'); ?></label>
								<input name="responsive[break_point][]" type="number" min="0" step="1" value="<?php echo (int)$break; ?>" class="small-text" />
								<span>px</span>
								<input name="responsive[item][]" type="number" min="0" step="1" value="<?php echo (int)$options['responsive']['item'][$k]; ?>" class="small-text" />
								<label><?php esc_html_e('Items','velatemplates'); ?></label>
							</li>
							<?php } ?>
						</ul>
					</div>
					
					<input type="submit" name="vela_brand_save_setting" value="<?php esc_html_e('Save changes','velatemplates'); ?>" class="button button-primary" />
					<input type="submit" name="vela_brand_reset_setting" value="<?php esc_html_e('Reset','velatemplates'); ?>" class="button" />
				</form>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					"use strict";
					jQuery('input[name="vela_brand_reset_setting"]').bind('click', function(e){
						var ok = confirm('Do you want to reset all settings?');
						if( !ok ){
							e.preventDefault();
						}
					});
				});
			</script>
			<?php
		}
	}
}
new Vela_Brands();

/*** Vela Footer ***/
if( !class_exists('Vela_Footer') ){
	class Vela_Footer{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'vela_footer';
			add_action('init', array($this, 'register_post_type'));
			add_action('wp_head', array($this, 'add_custom_css'));
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Vela Footer', 'post type general name', 'velatemplates' ),
				'singular_name' 	=> esc_html_x( 'Vela Footer', 'post type singular name', 'velatemplates' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'velatemplates' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'velatemplates' ),
				'edit_item' 		=> esc_html__( 'Edit Footer Block', 'velatemplates' ),
				'new_item' 			=> esc_html__( 'New Footer', 'velatemplates' ),
				'all_items' 		=> esc_html__( 'All Vela Footer', 'velatemplates' ),
				'view_item' 		=> esc_html__( 'View Vela Footer', 'velatemplates' ),
				'search_items' 		=> esc_html__( 'Search Vela Footer', 'velatemplates' ),
				'not_found' 		=> esc_html__( 'No Vela Footer Found', 'velatemplates' ),
				'not_found_in_trash'=> esc_html__( 'No Vela Footer Found In Trash', 'velatemplates' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Vela Footer', 'velatemplates' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> false,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
                                'menu_icon'             => 'dashicons-admin-customizer',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function add_custom_css(){
			global $post;
			$args = array(
				'post_type' 		=> $this->post_type
				,'posts_per_page' 	=> -1
				,'post_status'		=> 'publish'
			);
			$posts = new WP_Query($args);
			if( $posts->have_posts() ){
				$custom_css = '';
				while( $posts->have_posts() ){
					$posts->the_post();
					$custom_css .= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
				}
				if( !empty($custom_css) ){
					echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
					echo $custom_css;
					echo '</style>';
				}
			}
			wp_reset_postdata();
		}
	}
}
new Vela_Footer();

?>