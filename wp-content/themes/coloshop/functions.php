<?php

define( 'THEME_URL', get_stylesheet_directory_uri());
define( 'CORE', THEME_URL .'/core');

//Nhung file init.php
require_once( dirname( __FILE__ ) . '/core/init.php' );

// thiet lap chieu dai noi dung
if( !isset($content_width)){
	$content_width = 620;
}

//khai bao chuc nang theme
if( !function_exists('shop_theme_setup')){
	function shop_theme_setup(){
		//thiet lap textdomain
		$language_folder = THEME_URL.'languages';	
		load_theme_textdomain( 'shop', $language_folder);
		
		//them link rss
		add_theme_support( 'automatic-feed-links' );
		
		//thumbnails
		add_theme_support( 'post-thumbnails');

		//title-tag
		add_theme_support( 'title-tag' );

		$post_formats = array('video', 'image', 'audio', 'gallery');
		
		add_theme_support( 'post-formats', $post_formats );

		$default_background = array( 'default-color' => '#e8e8e8' );
		
		add_theme_support( 'custom-background', $default_background);

		add_theme_support( 'woocommerce' );

		register_nav_menu( 'primary-menu', __('Primary Menu', 'shop')); 
		register_nav_menu( 'footer-menu', __('Footer Menu', 'shop')); 

		register_nav_menu( 'social-menu', __('Social Menu', 'shop')); 
		/*ho tro html*/
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );


	}

	add_action('after_setup_theme', 'shop_theme_setup');
}

if( !function_exists("shop_theme_style") ){
	function shop_theme_style(){
		wp_register_style('font-awesome', THEME_URL . "/assets/css/font-awesome.min.css",'all');
		wp_enqueue_style('font-awesome');

		wp_register_style( 'bootstrap-min', THEME_URL . "/assets/css/bootstrap.min.css",'all');
		wp_enqueue_style('bootstrap-min');

		wp_register_style( 'carousel', THEME_URL . "/assets/css/owl.carousel.css",'all');
		wp_enqueue_style('carousel');

		wp_register_style( 'default', THEME_URL . "/assets/css/owl.theme.default.css",'all');
		wp_enqueue_style('default');

		wp_register_style( 'animate', THEME_URL . "/assets/css/animate.css",'all');
		wp_enqueue_style('animate');
		
		wp_register_style( 'style', THEME_URL . "/assets/css/style.css",'all');
		wp_enqueue_style('style');

		if( !is_home() ){
			wp_register_style( 'style.woorcomerce', THEME_URL . "/assets/css/style.woorcomerce.css",'all');
			wp_enqueue_style('style.woorcomerce');
		}
	}
}
add_action('wp_enqueue_scripts','shop_theme_style');

if( !function_exists("shop_theme_jquery_footer") ){
	function shop_theme_jquery_footer(){
		wp_register_script('isotope', THEME_URL . "/assets/js/isotope.pkgd.min.js",array('jquery'));
		wp_enqueue_script('isotope');
		
		wp_register_script('carousel', THEME_URL . "/assets/js/owl.carousel.js",array('jquery'));
		wp_enqueue_script('carousel');

		wp_register_script('custom', THEME_URL . "/assets/js/custom.js",array('jquery'));
		wp_enqueue_script('custom');

		// wp_register_script('custommr', THEME_URL . "/assets/js/custommer.js",array('jquery'));
		// wp_enqueue_script('custommr');
	}
}
add_action('wp_footer','shop_theme_jquery_footer',true );

function hide_admin_bar(){ return false; }
add_filter( 'show_admin_bar', 'hide_admin_bar' );

//khai bao breadcrumbs
function wp_breadcrumbs(){ 
	$delimiter = '&raquo;';
	$name = 'Home';
	$currentBefore = '<span class="current">';
	$currentAfter = '</span>';

	if(!is_home() && !is_front_page() || is_paged()){

		global $post;
		$home = get_bloginfo('url');
		echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';

		if(is_tax()){
			  $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			  echo $currentBefore . $term->name . $currentAfter;

		} elseif (is_category()){
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
			echo $currentBefore . '';
			single_cat_title();
			echo '' . $currentAfter;

		} elseif (is_day()){
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $currentBefore . get_the_time('d') . $currentAfter;

		} elseif (is_month()){
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $currentBefore . get_the_time('F') . $currentAfter;

		} elseif (is_year()){
			echo $currentBefore . get_the_time('Y') . $currentAfter;

		} elseif (is_single()){
			$postType = get_post_type();
			if($postType == 'post'){
				$cat = get_the_category(); $cat = $cat[0];
				echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			} elseif($postType == 'portfolio'){
				$terms = get_the_term_list($post->ID, 'portfolio-category', '', '###', '');
				$terms = explode('###', $terms);
				echo $terms[0]. ' ' . $delimiter . ' ';
			}
			echo $currentBefore;
			the_title();
			echo $currentAfter;

		} elseif (is_page() && !$post->post_parent){
			echo $currentBefore;
			the_title();
			echo $currentAfter;

		} elseif (is_page() && $post->post_parent){
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while($parent_id){
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $currentBefore;
			the_title();
			echo $currentAfter;
		} elseif (is_search()){
			echo $currentBefore . __('Search Results for:', 'wpinsite'). ' &quot;' . get_search_query() . '&quot;' . $currentAfter;
		} elseif (is_tag()){
			echo $currentBefore . __('Post Tagged with:', 'wpinsite'). ' &quot;';
			single_tag_title();
			echo '&quot;' . $currentAfter;
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo $currentBefore . __('Author Archive', 'wpinsite') . $currentAfter;
		} elseif (is_404()){
			echo $currentBefore . __('Page Not Found', 'wpinsite') . $currentAfter;
		}
		if(get_query_var('paged')){
		if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
		echo ' ' . $delimiter . ' ' . __('Page') . ' ' . get_query_var('paged');
		if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}

	}
}

//khai bao phân trang
function numbered_pagination(){
    global $wp_query;
    $big = 99999999;
    echo '<div class="row pagination-area">';
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'total' => $wp_query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'show_all' => false,
        'end_size' => 2,
        'mid_size' => 3,
        'prev_next' => true,
        'prev_text' => 'Prev',
        'next_text' => 'Next',
        'type' => 'list'
    ));
    echo '</div>';
}

//fix phan trang archive
function custom_posts_per_page( $query ) {

    if ( $query->is_archive('project') ) {
        set_query_var('posts_per_page', 1);
    }
}
add_action( 'pre_get_posts', 'custom_posts_per_page' );

add_filter( 'woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumbs' );
function custom_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<div class="breadcrumbs align-items-nav flex-row"><ul>',
            'wrap_after'  => '</ul></div>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' )
        );
}

if( !function_exists('shop_has_woocommerce') ){
	function shop_has_woocommerce(){
		$_actived = apply_filters('active_plugins', get_option('active_plugins'));
		if( in_array("woocommerce/woocommerce.php", $_actived) || class_exists('WooCommerce') ){
			return true;
		}
		return false;
	}
}

/*** Tiny account ***/
if( !function_exists('shop_tiny_account') ){
	function shop_tiny_account(){
		$login_url = '#';
		$register_url = '#';
		$profile_url = '#';
		$logout_url = wp_logout_url(get_permalink());
		
		if( shop_has_woocommerce() ){ /* Active woocommerce */
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			if ( $myaccount_page_id ) {
			  $login_url = get_permalink( $myaccount_page_id );
			  $register_url = $login_url;
			  $profile_url = $login_url;
			}		
		}
		else{
			$login_url = wp_login_url();
			$register_url = wp_registration_url();
			$profile_url = admin_url( 'profile.php' );
		}
		
		$redirect_to = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		$_user_logged = is_user_logged_in();
		ob_start();
		
		?>
		<div class="shop-tiny-account-wrapper">
			<div class="account-control">
				<a href="#"><i class="fa fa-user" aria-hidden="true"></i></a>
                   <!--  <span class="icon-account-control"></span> -->
				<?php if( !$_user_logged ): ?>
					<a  class="login" href="<?php echo esc_url($login_url); ?>" title="<?php esc_html_e('Login','shop'); ?>"><span><?php esc_html_e('Login','shop');?></span></a>
					 
					<a class="sign-up" href="<?php echo esc_url($register_url); ?>" title="<?php esc_html_e('Create New Account','shop'); ?>"><span><?php esc_html_e('Sign up','shop');?></span></a>
				<?php else: ?>
					<ul class="shop-login">
					<li><a class="my-account" href="<?php echo esc_url($profile_url); ?>" title="<?php esc_html_e('Tài khoản','shop'); ?>"><span><?php esc_html_e('Tài khoản','shop');?></span></a></li>

					<li><a class="log-out" href="<?php echo esc_url($logout_url); ?>" title="<?php esc_html_e('Đăng xuất','shop'); ?>"><span><?php esc_html_e('Đăng xuất','shop');?></span></a></li>
					</ul>
				<?php endif; ?>
			</div>
			<?php if( !$_user_logged ): ?>
			<div class="account-dropdown-form dropdown-container">
				<div class="form-content">	
					<form name="shop-login-form" class="shop-login-form" action="<?php echo esc_url(wp_login_url()); ?>" method="post">
			
						<p class="login-username">
							<label><?php esc_html_e('Tên đăng nhập', 'shop'); ?></label>
							<input type="text" name="log" class="input" value="" size="20" autocomplete="off">
						</p>
						<p class="login-password">
							<label><?php esc_html_e('Mật khẩu', 'shop'); ?></label>
							<input type="password" name="pwd" class="input" value="" size="20">
						</p>
						
						<p class="login-submit">
							<input type="submit" name="wp-submit" class="button-secondary button" value="<?php esc_html_e('Đăng nhập', 'shop'); ?>">
							<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
						</p>
						
					</form>
		
					<p class="forgot-pass"><a href="<?php echo esc_url(wp_lostpassword_url()); ?>" title="<?php esc_html_e('Forgot Your Password?','shop');?>"><?php esc_html_e('Quên mật khẩu?','shop');?></a></p>
				</div>
			</div>
			<?php endif; ?>
		</div>
		
		<?php
		return ob_get_clean();
	}
}

/*** Tiny cart ***/
if( !function_exists('shop_tiny_cart') ){
	function shop_tiny_cart(){
		if( !shop_has_woocommerce() ){
			return '';
		}
		$cart_empty = WC()->cart->is_empty();
		$cart_url = WC()->cart->get_cart_url(); // since 2.5.0 use wc_get_cart_url();
		$checkout_url = WC()->cart->get_checkout_url(); // since 2.5.0 use wc_get_checkout_url();
		$cart_number = WC()->cart->get_cart_contents_count();
		ob_start();
		?>
			<div class="shop-tiny-cart-wrapper">
				<a class="cart-control" href="<?php echo esc_url($cart_url); ?>" title="<?php esc_html_e('View your shopping bag','shop');?>">
					<span class="fa fa-shopping-cart"></span>
                                        <span class="cart-number<?php if($cart_number > 0){ echo esc_html(" no-bracket"); } ?>"><?php echo esc_html($cart_number); ?></span>
					<span class="cart-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
				</a>
				<div class="cart-dropdown-form dropdown-container">
					<div class="form-content">
						<?php if( $cart_empty ): ?>
							<label><?php esc_html_e('Chưa có sản phẩm nào trong giỏ hàng', 'shop'); ?></label>
						<?php else: ?>
							<ul class="cart-list">
								<?php 
								$cart = WC()->cart->get_cart();
								foreach( $cart as $cart_item_key => $cart_item ):
									$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									if ( !( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) ){
										continue;
									}
										
									$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									
								?>
									<li>
										<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
											<?php echo  $_product->get_image(); ?>
										</a>
										<div class="cart-item-wrapper">	
											<h3 class="product-name">
												<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
													<?php echo  $_product->get_title(); ?>
												</a>
											</h3><br/>
											<p class="price_quantity">
												<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="price"> ' . $product_price . '<span class="amount icon"> x </span></span>' . $cart_item['quantity'], $cart_item, $cart_item_key ); ?>
											</p>
											<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s" data-key="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), esc_html__( 'Remove this item', 'shop' ), $cart_item_key ), $cart_item_key ); ?>
										</div>
									</li>
								
								<?php endforeach; ?>
							</ul>
							<div class="dropdown-footer">
								<div class="total"><span class="total-title"><?php esc_html_e('Tổng tiền :', 'shop');?></span><?php echo WC()->cart->get_cart_subtotal(); ?> </div>
								
								<a href="<?php echo esc_url($cart_url); ?>" class="button view-cart"><?php esc_html_e('Xem giỏ hàng', 'shop'); ?></a>
								<a href="<?php echo esc_url($checkout_url); ?>" class="button checkout button-secondary"><?php esc_html_e('Đặt hàng', 'shop'); ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}
add_filter('woocommerce_add_to_cart_fragments', 'shop_tiny_cart_filter');
function shop_tiny_cart_filter($fragments){
	$fragments['.shop-tiny-cart-wrapper'] = shop_tiny_cart();
	return $fragments;
}

function shop_remove_cart_item(){
	$cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
	if( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) ){
		WC()->cart->remove_cart_item( $cart_item_key );
	}
	WC_AJAX::get_refreshed_fragments();
}
add_action('wp_ajax_vela_remove_cart_item', 'shop_remove_cart_item');
add_action('wp_ajax_nopriv_vela_remove_cart_item', 'shop_remove_cart_item');
// get template tags

// /**whilst**/
if ( !function_exists('shop_woocomerce_template_loop_wishlist') ) {
    function shop_woocomerce_template_loop_wishlist() {
        wc_get_template( 'loop/wishlist.php' );
    }
    add_action( 'shop_woocommerce_product_actions', 'shop_woocomerce_template_loop_wishlist', 5 );
} 

//khai bao widget
if( !function_exists("shop_theme_widget")){
	function shop_theme_widget(){

		register_sidebar( array(
			'name'          => __( 'Banner Boder', 'shop' ),
			'id'            => 'boder-sidebar',
			'description'   => __( 'Tao banner', 'shop' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<div class="widget-title"><h3>',
			'after_title'   => '</h3></div>'
		) );

		register_sidebar( array(
			'name' 			=> __('Best Sellers', 'shop'),
			'id'   			=> 'seller-sidebar',
			'description' 	=> 'Tạo seller',
			'class'			=> 'main-sidebar',
			'before_title'  => '<h3 class = "widget-title">$s',
			'after_sidebar' => '</h3>'

		) );

		register_sidebar( array(
			'name' 			=> __('Deal of the week', 'shop'),
			'id'   			=> 'deal-widget',
			'description' 	=> 'Tao thoi gian',
			'before_widget' => '<div id="%1$s" class="col-lg-6 widget %2$s"><div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">',
			'after_widget'  => '</div></div>',
			'class'			=> 'deal-of-sidebar',
			'before_title'  => '<h2 class = "widget-title">',
			'after_sidebar' => '</h2>'

		) ); 

		register_sidebar( array(
			'name' 			=> __('Blog post', 'shop'),
			'id'   			=> 'blog-widget',
			'description' 	=> 'Blog post sidebar',
			'class'			=> 'blog-post-sidebar',
			'before_title'  => '<h2 class = "widget-title">',
			'after_sidebar' => '</h2>'

		) ); 

		register_sidebar( array(
			'name'          => __( 'Sider Bar', 'shop' ),
			'id'            => 'sidebar-bar',
			'description'   => __( 'Tao sidebar', 'shop' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<div class="widget-title"><h3>',
			'after_title'   => '</h3></div>'
		) );
	}

	add_action( 'widgets_init', 'shop_theme_widget' );
}

if( !function_exists("shop_theme_add_wiget")){
	function shop_theme_add_wiget(){
		if ( class_exists('WooCommerce') ) {
			register_widget( 'Shop_Product_Loop' );
		}
		
		if( class_exists('WaitingWidget') ){
			register_widget('Widgets_Deal') ;			
		}

		register_widget('Shop_Blog');
	}
add_action( 'widgets_init', 'shop_theme_add_wiget' );
	
}

///add defult wiget
if(!function_exists("shop_theme_defaul")){
	function shop_theme_defaul(){
		$active_widgets = get_option( 'sidebars_widgets' ); 
		if(!empty($active_widgets['deal-widget'])){
			return ;
		}

		$counter = 1;  
		$active_widgets['deal-widget'][0] = 'media_image-'.$counter;

		$counter++;
		
		$active_widgets['deal-widget'][1] = 'wpb_waiting-'.$counter;
		
		update_option( 'sidebars_widgets', $active_widgets );  
	}

	add_action('widgets_init', 'shop_theme_defaul');
}

/* Cài đặt Plugins */
add_action( 'tgmpa_register', 'shop_register_required_plugins' );
function shop_register_required_plugins(){
	$plugin_dir_path = get_template_directory() . '/inc/plugins/';
	/**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'               => 'WooCommerce', // The plugin name.
            'slug'               => 'woocommerce', // The plugin slug (typically the folder name).
            'required'           => true, 
        )
		,array(
            'name'               => 'Revolution Slider', // The plugin name.
            'slug'               => 'revslider', // The plugin slug (typically the folder name).
            'source'             => $plugin_dir_path . 'revslider.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '5.4.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        )
        ,array(
            'name'               => 'Contact Form 7', // The plugin name.
            'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        )
		,array(
            'name'               => 'YITH WooCommerce Wishlist', // The plugin name.
            'slug'               => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        )
		,array(
            'name'               => 'YITH WooCommerce Compare', // The plugin name.
            'slug'               => 'yith-woocommerce-compare', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        )
        ,array(
            'name'               => 'Woo Product Filter', // The plugin name.
            'slug'               => 'woo-product-filter', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        )

    );

    /*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
    $config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

    tgmpa( $plugins, $config );
}
//link url inc
include_once('inc/template-tags.php');
include_once('inc/template-function.php');
include_once('inc/class-tgm-plugin-activation.php');
//widgets
include_once(dirname( __FILE__ ) .'/widgets/class-shop-product-loop.php');
include_once(dirname( __FILE__ ) .'/widgets/class_shop_widgets_deal.php');
include_once(dirname( __FILE__ ) .'/widgets/class-shop-blogs.php');

