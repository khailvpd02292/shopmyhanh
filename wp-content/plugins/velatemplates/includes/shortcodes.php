<?php  
class Vela_Custom_Shortcodes{
	
	function __construct(){
		
		add_filter('the_content', array($this, 'remove_extra_p_tag'));
		add_filter('widget_text', array($this, 'remove_extra_p_tag'));
		
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		require_once('custom_shortcodes.php');
	}
	
	function remove_extra_p_tag( $content ){
	
		$block = join("|", array('vela_button'));
		/* opening tag */
		$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
			
		/* closing tag */
		$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
	 
		return $rep;
	}
	
	function register_scripts(){
		global $smof_data;
		$gmap_api_key = !empty($smof_data['vela_gmap_api_key'])?$smof_data['vela_gmap_api_key']:'';
		
		$js_dir = plugin_dir_url( __FILE__ ).'js';
		$css_dir = plugin_dir_url( __FILE__ ).'css';
		
		$deps = array();
		if( class_exists('Vc_Manager') ){
			$deps = array('js_composer_front');
		}
		
		$gmap_api_link = 'https://maps.googleapis.com/maps/api/js';
		if( $gmap_api_key ){
			$gmap_api_link .= '?key=' . $gmap_api_key;
                        wp_register_script('gmap-api', $gmap_api_link, array(), null, true);
		}
		
		if( defined('ICL_LANGUAGE_CODE') ){
			$ajax_uri = admin_url('admin-ajax.php?lang='.ICL_LANGUAGE_CODE, 'relative');
		}
		else{
			$ajax_uri = admin_url('admin-ajax.php', 'relative');
		}
		$data = array(
			'ajax_uri'	=> $ajax_uri
		);
		wp_localize_script('vela-custom-shortcode', 'vela_shortcode_params', $data);
	}
	
}
new Vela_Custom_Shortcodes();
?>