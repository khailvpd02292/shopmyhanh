<?php
class woofiltersWpf extends moduleWpf {
	public function init() {
		dispatcherWpf::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_shortcode(WPF_SHORTCODE, array($this, 'render'));
		add_shortcode(WPF_SHORTCODE_PRODUCTS, array($this, 'renderProductsList'));
		if(is_admin()) {
			add_action('admin_notices', array($this, 'showAdminErrors'));
		}

		add_action('woocommerce_product_query', array($this, 'loadProductsFilter'));
		add_filter('woocommerce_product_query_tax_query', array($this, 'customProductQueryTaxQuery'), 10, 2);

		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		add_filter('loop_shop_per_page', array($this, 'newLoopShopPerPage'), 20 );
	}

	public function newLoopShopPerPage($count) {
		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['count_product_shop']) && isset($options['count_product_shop']['value']) && !empty($options['count_product_shop']['value'])){
			$count  = $options['count_product_shop']['value'];	
		}
		return $count ;
	}

	public function loadProductsFilter( $q ){
		if(reqWpf::getVar('pr_stock')) {
			$metaQuery = array(
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '='
				)
			);
			$q->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $metaQuery ) );
		}
		if(reqWpf::getVar('pr_onsale')) {
			$metaQuery = array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			);
			$args['meta_query'][] = $metaQuery;
			$q->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $metaQuery ) );
		}

		if(reqWpf::getVar('pr_author')) {
			$author_obj = get_user_by('slug', reqWpf::getVar('pr_author'));
			if(isset($author_obj->ID)){
				$q->set( 'author', $author_obj->ID );
			}
		}
		if(reqWpf::getVar('pr_rating')) {
			$ratingRange = reqWpf::getVar('pr_rating');
			$range = explode('-', $ratingRange);
			if(intval($range[1] ) !== 5){
				$range[1] = $range[1] - 0.001;
			}
			$metaQuery = array(
				array( // Simple products type
					'key' => '_wc_average_rating',
					'value' => array($range[0], $range[1]),
					'type' => 'DECIMAL',
					'compare' => 'BETWEEN'
				)
			);
			$q->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $metaQuery ) );
		}

	}
	public function customProductQueryTaxQuery($tax_query) {
		foreach($tax_query as $i => $tax) {
			if(is_array($tax) && isset($tax['field']) && $tax['field'] == 'slug') {
				$name = str_replace('pa_', 'filter_', $tax['taxonomy']);
				$param = reqWpf::getVar($name);
				if(!is_null($param)) {
					$slugs = explode('|', $param);
					if(sizeof($slugs) > 1) {
						$tax_query[$i]['terms'] = $slugs;
						$tax_query[$i]['operator'] = 'IN';
					}
				}
			}
		}
		if(reqWpf::getVar('pr_featured')) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured'
			);
		}
		if(reqWpf::getVar('filter_cat')) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'name',
				'terms'    => reqWpf::getVar('filter_cat')
			);
		}
		return $tax_query;
	}
	public function addAdminTab($tabs) {
		$tabs[ $this->getCode(). '#wpfadd' ] = array(
			'label' => __('Add New Filter', WPF_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-plus-circle', 'sort_order' => 10, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode(). '_edit' ] = array(
			'label' => __('Edit', WPF_LANG_CODE), 'callback' => array($this, 'getEditTabContent'), 'sort_order' => 20, 'child_of' => $this->getCode(), 'hidden' => 1, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() ] = array(
			'label' => __('Show All Filters', WPF_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-list', 'sort_order' => 20, //'is_main' => true,
		);
		return $tabs;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getEditTabContent() {
		$id = reqWpf::getVar('id', 'get');
		return $this->getView()->getEditTabContent( $id );
	}
	public function getEditLink($id, $tableTab = '') {
		$link = frameWpf::_()->getModule('options')->getTabUrl( $this->getCode(). '_edit' );
		$link .= '&id='. $id;
		if(!empty($tableTab)) {
			$link .= '#'. $tableTab;
		}
		return $link;
	}
	public function render($params){
		return $this->getView()->renderHtml($params);
	}
	public function renderProductsList($params){
		return $this->getView()->renderProductsListHtml($params);
	}
	public function showAdminErrors() {
		// check WooCommerce is installed and activated
		if(!$this->isWooCommercePluginActivated()) {
			// WooCommerce install url
			$wooCommerceInstallUrl = add_query_arg(
				array(
					's' => 'WooCommerce',
					'tab' => 'search',
					'type' => 'term',
				),
				admin_url( 'plugin-install.php' )
			);
			$tableView = $this->getView();
			$tableView->assign('errorMsg',
				$this->translate('For work with "')
				. WPF_WP_PLUGIN_NAME
				. $this->translate('" plugin, You need to install and activate <a target="_blank" href="' . $wooCommerceInstallUrl . '">WooCommerce</a> plugin')
			);
			// check current module
			if(reqWpf::getVar('page') == WPF_SHORTCODE) {
				// show message
				echo $tableView->getContent('showAdminNotice');
			}
		}
	}
	public function isWooCommercePluginActivated() {
		return class_exists('WooCommerce');
	}
}

