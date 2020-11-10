<?php 
/**
 * Plugin Name: Velatemplates
 * Plugin URI: http://velatemplates.com
 * Description: Add shortcodes and custom post types for Velatemplates's theme
 * Version: 1.0.0
 * Author: Velatemplates Team
 * Author URI: http://velatemplates.com
 */
class Velatemplates_Plugin{

	function __construct(){
		$this->include_files();
	}
	
	function include_files(){
		$file_names = array('register', 'shortcodes', 'banner', 'brands_slider', 'footer', 'product_deals', 'single_image', 'testimonial');
		foreach( $file_names as $file_name ){
			$file = plugin_dir_path( __FILE__ ) . '/includes/' . $file_name . '.php';
			if( file_exists($file) ){
				require_once($file);
			}
		}
	}
	
}
new Velatemplates_Plugin();

?>