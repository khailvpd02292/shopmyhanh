<?php
class woofiltersViewWpf extends viewWpf {
	public function getTabContent() {
		frameWpf::_()->getModule('templates')->loadJqGrid();
		frameWpf::_()->addScript('admin.woofilters.list', $this->getModule()->getModPath(). 'js/admin.woofilters.list.js');
		frameWpf::_()->addScript('adminCreateTableWpf', $this->getModule()->getModPath(). 'js/create-filter.js', array(), false, true);
		frameWpf::_()->getModule('templates')->loadFontAwesome();
		frameWpf::_()->addJSVar('admin.woofilters.list', 'wpfTblDataUrl', uriWpf::mod('woofilters', 'getListForTbl', array('reqType' => 'ajax')));
		frameWpf::_()->addJSVar('admin.woofilters.list', 'url', admin_url('admin-ajax.php'));
		frameWpf::_()->getModule('templates')->loadBootstrapSimple();
		frameWpf::_()->addStyle('admin.filters', $this->getModule()->getModPath(). 'css/admin.woofilters.css');
		$this->assign('addNewLink', frameWpf::_()->getModule('options')->getTabUrl('woofilters#wpfadd'));

		return parent::getContent('woofiltersAdmin');
	}

	public function getEditTabContent($idIn) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if(!$isWooCommercePluginActivated) {
			return;
		}
		$idIn = isset($idIn) ? (int) $idIn : 0;
		$filter = $this->getModel('woofilters')->getById($idIn);

		$settings = unserialize($filter['setting_data']);
		frameWpf::_()->getModule('templates')->loadChosenSelects();
		frameWpf::_()->getModule('templates')->loadBootstrapSimple();
		frameWpf::_()->getModule('templates')->loadJqueryUi();
		frameWpf::_()->addScript('notify-js', WPF_JS_PATH. 'notify.js', array(), false, true);
		frameWpf::_()->addScript('admin.filters', $this->getModule()->getModPath(). 'js/admin.woofilters.js');
		frameWpf::_()->addScript('adminCreateTableWpf', $this->getModule()->getModPath().'js/create-filter.js', array(), false, true);
		frameWpf::_()->addJSVar('admin.filters', 'url', admin_url('admin-ajax.php'));
		frameWpf::_()->addStyle('common.filters', $this->getModule()->getModPath(). 'css/common.woofilters.css');
		frameWpf::_()->addStyle('admin.filters', $this->getModule()->getModPath(). 'css/admin.woofilters.css');
		frameWpf::_()->addStyle('loaders', $this->getModule()->getModPath(). 'css/loaders.css');

		dispatcherWpf::doAction('addScriptsContent', true);

		$link = frameWpf::_()->getModule('options')->getTabUrl( $this->getCode() );

		$this->assign('link', $link);
		$this->assign('settings', $settings);
		$this->assign('filter', $filter);

		return parent::getContent('woofiltersEditAdmin');
	}

	public function renderHtml($params){
		frameWpf::_()->getModule('templates')->loadCoreJs();
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();

		if(!$isWooCommercePluginActivated) {
			return;
		}
		$html = '';
		frameWpf::_()->addScript('jquery-ui-slider');
		frameWpf::_()->addScript('jquery-touch-punch');
		//frameWpf::_()->addStyle('common.filters', $this->getModule()->getModPath(). 'css/common.woofilters.css');
		frameWpf::_()->addStyle('frontend.filters', $this->getModule()->getModPath(). 'css/frontend.woofilters.css');
		frameWpf::_()->addScript('frontend.filters', $this->getModule()->getModPath(). 'js/frontend.woofilters.js');
		frameWpf::_()->addStyle('loaders', $this->getModule()->getModPath(). 'css/loaders.css');
		frameWpf::_()->addJSVar('frontend.filters', 'url', admin_url('admin-ajax.php'));
		frameWpf::_()->getModule('templates')->loadJqueryUi();
		frameWpf::_()->getModule('templates')->loadFontAwesome();

//		frameWpf::_()->addScript('jquery.slider.js', $this->getModule()->getModPath(). 'js/jquery.slider.min.js');
		frameWpf::_()->addScript('jquery.slider.js.jshashtable', $this->getModule()->getModPath(). 'js/jquery_slider/jshashtable-2.1_src.js');
		frameWpf::_()->addScript('jquery.slider.js.numberformatter', $this->getModule()->getModPath(). 'js/jquery_slider/jquery.numberformatter-1.2.3.js');
		frameWpf::_()->addScript('jquery.slider.js.tmpl', $this->getModule()->getModPath(). 'js/jquery_slider/tmpl.js');
		frameWpf::_()->addScript('jquery.slider.js.dependClass', $this->getModule()->getModPath(). 'js/jquery_slider/jquery.dependClass-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js.draggable', $this->getModule()->getModPath(). 'js/jquery_slider/draggable-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js', $this->getModule()->getModPath(). 'js/jquery_slider/jquery.slider.js');

		frameWpf::_()->addStyle('jquery.slider.css', $this->getModule()->getModPath(). 'css/jquery.slider.min.css');
		
		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['move_sidebar']) && isset($options['move_sidebar']['value']) && !empty($options['move_sidebar']['value'])){
			frameWpf::_()->addStyle('move.sidebar.css', $this->getModule()->getModPath(). 'css/move.sidebar.css');
		}

		$id = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$id){
			return false;
		}

		$filter = $this->getModel('woofilters')->getById($id);
		$settings = unserialize($filter['setting_data']);

		dispatcherWpf::doAction('addScriptsContent', false);

		$viewId = $id . '_' . mt_rand(0, 999999);

		$displayShop = false;
		$displayCategory = false;

		if(!empty($settings["settings"]['display_on_page'])
			&& ( $settings["settings"]['display_on_page'] === 'shop'
			|| $settings["settings"]['display_on_page'] === 'both') ){
			$displayShop = true;
		}
		if(!empty($settings["settings"]['display_on_page'])
			&& ( $settings["settings"]['display_on_page'] === 'category'
				|| $settings["settings"]['display_on_page'] === 'both') ){
			$displayCategory = true;
		}

		$displayMobile = true;
		if(!empty($settings["settings"]['display_for'])){
			if($settings["settings"]['display_for'] === 'mobile'){
				$displayMobile = utilsWpf::isMobile();
			}else if($settings["settings"]['display_for'] === 'both'){
				$displayMobile = true;
			}else if($settings["settings"]['display_for'] === 'desktop'){
				$displayMobile = !utilsWpf::isMobile();
			}
		}

		if(is_product_category() && $displayCategory && $displayMobile){
			$catObj = get_queried_object();
			$html = $this->generateFiltersHtml($settings, $viewId, $catObj->term_id );
		}else if(is_shop() && $displayShop && $displayMobile){
			$html = $this->generateFiltersHtml($settings, $viewId );
		}else if($displayShop && $displayMobile){
			$html = $this->generateFiltersHtml($settings, $viewId, false, true);
		}

		
		$this->assign('viewId', $viewId);
		$this->assign('html', $html);

		return parent::getContent('woofiltersHtml');
	}

	//for now after render we run once filtering, in order to display products on custom page.
	public function renderProductsListHtml($params){
		$html = '<div class="woocommerce wpfNoWooPage">';
			$html .= '<p class="woocommerce-result-count"></p>';
			$html .= '<ul class="products columns-4"></ul>';
			$html .= '<nav class="woocommerce-pagination"></nav>';
			$html .= '<script>jQuery(document).ready(function(){ setTimeout(function() {  jQuery(".wpfFilterButton").trigger("wpffiltering"); }, 1000); })</script>';
		$html .= '</div>';

		return $html;
	}

	public function generateFiltersHtml($filterSettings, $viewId, $prodCatId = false, $noWooPage = false){
		if(!empty($filterSettings['settings']['css_editor'])){
			$filterSettings['settings']['css_editor'] = stripslashes(base64_decode($filterSettings['settings']['css_editor']));
		}
		if(!empty($filterSettings['settings']['js_editor'])){
			$filterSettings['settings']['js_editor'] = stripslashes(base64_decode($filterSettings['settings']['js_editor']));
		}

		$settingsOriginal = $filterSettings;
		$filtersOrder = utilsWpf::jsonDecode($filterSettings["settings"]['filters']['order']);

		$buttonsPosition = (!empty($filterSettings['settings']['main_buttons_position'])) ? $filterSettings['settings']['main_buttons_position'] : 'bottom' ;
		$showCleanButton = (!empty($filterSettings['settings']['show_clean_button'])) ? $filterSettings['settings']['show_clean_button'] : false ;
		$showFilteringButton = (!empty($filterSettings['settings']['show_filtering_button'])) ? $filterSettings['settings']['show_filtering_button'] : false ;
		$enableAjax = (!empty($filterSettings['settings']['enable_ajax'])) ? $filterSettings['settings']['enable_ajax'] : 0 ;
		if($enableAjax == 1) {
			$showFilteringButton = false;
		}

		global $wp_query;
		$postPerPage = get_option('posts_per_page');
		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['count_product_shop']) && isset($options['count_product_shop']['value']) && !empty($options['count_product_shop']['value']) ){
			$postPerPage = $options['count_product_shop']['value'];
		}

		$paged = $wp_query->query_vars['paged'] ? $wp_query->query_vars['paged'] : 1;
		//get all link
		$base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ));
		//get only base link, remove all query params
		$base = explode( '?', $base );
		$base = $base[0];

		$querySettings = array(
			'posts_per_page' => $postPerPage,
			'paged' => $paged,
			'base' => $base,
		);
		if($prodCatId){
			$querySettings['product_category_id'] = $prodCatId;
		}
		$querySettingsStr =  htmlentities(utilsWpf::jsonEncode($querySettings)) ;
		$filterSettings = htmlentities(utilsWpf::jsonEncode($filterSettings));

		$noWooPageData = '';
		if($noWooPage){
			$noWooPageData = 'data-nowoo="true"';
		}
		$width = $this->getFilterSetting($settingsOriginal['settings'], 'filter_width', '100', true);
		$style = 'width:'.$width.$this->getFilterSetting($settingsOriginal['settings'], 'filter_width_in', '%', false, array('%', 'px')).';';
		$html = '<div class="wpfMainWrapper" id="wpfMainWrapper-'.$viewId.'" data-settings="'.$querySettingsStr.'" data-filter-settings="'.$filterSettings.'" '.$noWooPageData.' style="'.$style.'">';

		if( ($buttonsPosition === 'top' || $buttonsPosition === 'both' ) && ($showFilteringButton || $showCleanButton )){
			$html .= '<div class="wpfFilterButtons">';

			if($showFilteringButton){
				$html .= '<button class="wpfFilterButton wpfButton">'.__('Filter', WPF_LANG_CODE).'</button>';
			}
			if($showCleanButton){
				$html .= '<button class="wpfClearButton wpfButton">'.__('Clear', WPF_LANG_CODE).'</button>';
			}
			$html .= '</div>';
		}

		$blockWidth = $this->getFilterSetting($settingsOriginal['settings'], 'filter_block_width', '100', true).$this->getFilterSetting($settingsOriginal['settings'], 'filter_block_width_in', '%', false, array('%', 'px'));
		$blockStyle = 'width:'.$blockWidth.';'.($blockWidth == '100%' ? '' : 'float:left;');
		$isPro = frameWpf::_()->isPro();
		if($isPro) {
			$proView = frameWpf::_()->getModule('woofilterpro')->getView();
		}

		foreach ($filtersOrder as $filter){
			if($filter['settings']['f_enable'] !== true){
				continue;
			}
			$method = 'generate'. str_replace('wpf', '', $filter['id']). 'FilterHtml';
			if($filter['id'] !== 'wpfCategory'){
				if($isPro && method_exists($proView, $method)) {
					$html .= $proView->{$method}($filter, $settingsOriginal, $blockStyle);
				} elseif(method_exists($this, $method)) {
					$html .= $this->{$method}($filter, $settingsOriginal, $blockStyle);
				} 
			}else{
				if(!$prodCatId){ 
					$html .= $this->{$method}($filter, $settingsOriginal, $blockStyle);
				}
			}
		}

		if( ($buttonsPosition === 'bottom' || $buttonsPosition === 'both' ) && ($showFilteringButton || $showCleanButton )){
			$html .= '<div class="wpfFilterButtons">';

			if($showFilteringButton){
				$html .= '<button class="wpfFilterButton wpfButton">'.__('Filter', WPF_LANG_CODE).'</button>';
			}
			if($showCleanButton){
				$html .= '<button class="wpfClearButton wpfButton">'.__('Clear', WPF_LANG_CODE).'</button>';
			}
			$html .= '</div>';
		}
		//if loader enable on load
		if(!empty($settingsOriginal['settings']['filter_loader_icon_onload_enable'])){
			$html .= $this->generateLoaderHtml($settingsOriginal);
		}
		//if loader enable on filtering
		if(!empty($settingsOriginal['settings']['enable_overlay'])){
			$html .= $this->generateOverlayHtml($settingsOriginal);
		}

		$html .= '</div>';
		return $html;

	}

	public function generateOverlayHtml($settings){
		$html = '';
		$html .= '<div id="wpfOverlay">';
		$html .= '<div id="wpfOverlayText">';

		if(!empty($settings['settings']['enable_overlay_word'])){
			if(!empty($settings['settings']['overlay_word'])){
				$html .= $settings['settings']['overlay_word'];
			}
		}
		if(!empty($settings['settings']['enable_overlay_icon'])){
			$colorPreview = (isset($settings['settings']['filter_loader_icon_color']) ? $settings['settings']['filter_loader_icon_color'] : 'black');
			$iconName = (isset($settings['settings']['filter_loader_icon_name']) ? $settings['settings']['filter_loader_icon_name'] : 'default');
			$iconNumber = (isset($settings['settings']['filter_loader_icon_number']) ? $settings['settings']['filter_loader_icon_number'] : '0');

			$html .= '<div class="wpfPreview">';
			if($iconName === 'default'){
				$html .= '<div class="supsystic-filter-loader spinner" style="background-color:'.$colorPreview.'"></div>';
			}else{
				$html .= '<div class="supsystic-filter-loader la-'.$iconName.' la-2x" style="color: '.$colorPreview.'">';
				for($i = 1; $i <= $iconNumber; $i++){
					$html .= '<div></div>';
				}
				$html .= '</div>';
			}
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	public function generateIconCloseOpenTitleHtml($filter, $filterSettings){
		if(empty($filterSettings['settings']['hide_filter_icon'])){
			return '';
		}
		if(!empty($filter['settings']['f_enable_title']) && $filter['settings']['f_enable_title'] === 'yes_open'){
			$icon = '<i class="fa fa-chevron-up"></i>';
		}else if(!empty($filter['settings']['f_enable_title']) && $filter['settings']['f_enable_title'] === 'yes_close'){
			$icon = '<i class="fa fa-chevron-down"></i>';
		}else{
			$icon = '';
		}
		return $icon;
	}
	public function generateDescriptionHtml($filter) {
		$description = $filter['settings']['f_description'] ? $filter['settings']['f_description'] : false;
		if($description){
			$html = '<div class="wfpDescription">'.$description.'</div>';
		}else{
			$html = '';
		}
		return $html;
	}
	public function generateBlockClearHtml($filter, $filterSettings) {
		$html = '';
		if($this->getFilterSetting($filterSettings['settings'], 'show_clean_block', false)) {
			$html = ' <label class="wpfBlockClear">'.__('clear', WPF_LANG_CODE).'</label>';
		}
		return $html;
	}
	public function generateFilterHeaderHtml($filter, $filterSettings) {
		$enableTitle = $this->getFilterSetting($filter['settings'], 'f_enable_title');
		$title = $enableTitle == 'no' ? false : $this->getFilterSetting($filter['settings'], 'f_title', false);

		$html = '';
		if($title) {
			$icon = $this->generateIconCloseOpenTitleHtml($filter, $filterSettings);
			$html .= '<div class="wfpTitle">'.$title.'</div>' . $icon;
		}

		$style = '';
		if($enableTitle == 'yes_close'){
			$style = 'style="display: none;"';
		}
		$html .= $this->generateBlockClearHtml($filter, $filterSettings);

		$html .= '<div class="wpfFilterContent" '.$style.'>';

		return $html;
	}


	public function generatePriceFilterHtml($filter, $filterSettings, $blockStyle){
		// Find min and max price in current result set.
		$prices = $this->wpfGetFilteredPrice();

		$filter['minPrice'] = $prices->wpfMinPrice;
		$filter['maxPrice'] = $prices->wpfMaxPrice;

		$noActive = reqWpf::getVar('min_price') && reqWpf::getVar('max_price') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-get-attribute="min_price,max_price" data-minvalue="'.$prices->wpfMinPrice.'" data-maxvalue="'.$prices->wpfMaxPrice.'" data-slug="'.__('price', WPF_LANG_CODE).'" style="'.$blockStyle.'">';

			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($filterSettings['settings']['price_filter_skin'] === 'default'){
					$html .= '<div id="wpfSliderRange" class="wpfPriceFilterRange"></div>';
					$html .= '<input type="number" min="'.$filter["minPrice"].'" max="'.($filter["maxPrice"] - 1) .'" id="wpfMinPrice" class="wpfPriceRangeField" /><span class="wpfFilterDelimeter"> - </span>';
					$html .= '<input type="number" min="'.$filter["minPrice"].'" max="'.$filter["maxPrice"].'" id="wpfMaxPrice" class="wpfPriceRangeField" />';
				}else{
					$html .= '<div id="" class="wpfPriceFilterRange layout-slider"></div>';
					$html .= '<span style="display: inline-block; width: 100%; padding: 10px 5px;"><input id="wpfSlider" style="display: none" type="text" name="area" value="'.$filter["minPrice"].';'.$filter["maxPrice"].'" /></span>';
					$html .= '<input style="display:none" type="number" min="'.$filter["minPrice"].'" max="'.($filter["maxPrice"] - 1) .'" id="wpfMinPrice" class="wpfPriceRangeField wpfHidden" />';
					$html .= '<input style="display:none" type="number" min="'.$filter["minPrice"].'" max="'.$filter["maxPrice"].'" id="wpfMaxPrice" class="wpfPriceRangeField wpfHidden	" />';
				}
			$html .= '</div>';

		$html .= '</div>';
		return $html;
	}

	public function generatePriceRangeFilterHtml($filter, $filterSettings, $blockStyle){
		if($filter['settings']['f_range_by_hands']){
			$ranges = array_chunk(explode(',', $filter['settings']['f_range_by_hands_values']), 2);
			$htmlOpt = $this->generatePriceRangeOptionsHtml($filter, $ranges);

		}else if($filter['settings']['f_range_automatic']){
			$prices = $this->wpfGetFilteredPrice();

			$minPrice =  $prices->wpfMinPrice === '0' ? '0.01' : $prices->wpfMinPrice;
			$maxPrice =  $prices->wpfMaxPrice;
			$step = !empty($filter['settings']['f_step']) ? $filter['settings']['f_step'] : 50;

			$priceRange = $maxPrice - $minPrice;
			$countElements = ceil($priceRange / $step);

			$ranges = array();
			$priceTempOld = 0;
			for($i = 0; $i < $countElements; $i++){
				if($i === 0){
					$priceTemp = $minPrice + $step;
					$ranges[$i] = array($minPrice, $priceTemp-0.01 );
					$priceTempOld = $priceTemp;
				}else if(($priceTempOld + $step) < $maxPrice){
					$priceTemp = $priceTempOld + $step;
					$ranges[$i] = array($priceTempOld, $priceTemp - 0.01 );
					$priceTempOld = $priceTemp;
				}else{
					$ranges[$i] = array($priceTempOld, $maxPrice );
				}
			}

			$htmlOpt = $this->generatePriceRangeOptionsHtml($filter, $ranges);
		}
		if(!$htmlOpt){
			$htmlOpt = __('Price range filter is empty. Please setup filter correctly.', WPF_LANG_CODE);
		}
		$noActive = reqWpf::getVar('min_price') && reqWpf::getVar('max_price') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="min_price,max_price" data-slug="'.__('price range', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
		$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					if($filter['settings']['f_frontend_type'] === 'list'){
						$style = '';
						if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
							$style = 'max-height:' . $filter['settings']['f_max_height'] . 'px';
						}
						$html .= '<ul class="wpfFilterVerScroll" style="'.$style.'">';
					}
					$html .= $htmlOpt;
					if($filter['settings']['f_frontend_type'] === 'list'){
						$html .= '</ul>';
					}
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>'; //end wpfFilterWrapper

		return $html;
	}

	public function generateSortByFilterHtml($filter, $filterSettings, $blockStyle){
		$optionsSelected = reqWpf::getVar('orderby');
		$optionsAll = array(
			'default' => __('Default', WPF_LANG_CODE),
			'popularity' => __('Popularity', WPF_LANG_CODE),
			'rating' => __('Rating', WPF_LANG_CODE),
			'date' => __('Newness', WPF_LANG_CODE),
			'price' => __('Price: low to high', WPF_LANG_CODE),
			'price-desc' => __('Price: high to low', WPF_LANG_CODE),
			'rand' => __('Random', WPF_LANG_CODE),
			'title' => __('Name', WPF_LANG_CODE),
		);

		$options = $filter['settings']['f_options[]'] ? $filter['settings']['f_options[]'] : false;
		$options = explode(',', $options);
		$noActive = reqWpf::getVar('orderby') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-get-attribute="orderby" data-slug="'.__('sort by', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
					$html .= '<select>';
						foreach ($options as $option){
							$selected = '';
							if($option === $optionsSelected){
								$selected = 'selected';
							}
							$html .= '<option value="'.$option.'" '.$selected.'>'.$optionsAll[$option].'</option>';
						}
					$html .= '</select>';
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>'; //end wpfFilterWrapper

		return $html;
	}

	public function generateCategoryFilterHtml($filter, $filterSettings, $blockStyle){
		$includeCategoryId = (!empty($filter['settings']['f_mlist[]'])) ? explode(',', $filter['settings']['f_mlist[]']) : false;
		$order = $filter['settings']['f_sort_by'] ? $filter['settings']['f_sort_by'] : 'asc';
		$excludeIds = !empty($filter['settings']['f_exclude_terms']) ? $filter['settings']['f_exclude_terms'] : false;
		$hideChild = !empty($filter['settings']['f_hide_taxonomy']) ? true : false;
		$args = array(
			'order' => $order,
			'parent' => 0,
			'include' => $includeCategoryId,
		);
		if($hideChild){
			$args['only_parent'] = $hideChild;
		}
		$productCategory = $this->getTaxonomyHierarchy('product_cat', $args);
		if(!$productCategory){
			return '';
		}
		$filterName = 'filter_cat';
		$catSelected = reqWpf::getVar($filterName);
		if($catSelected){
			$catSelected = explode(' ', $catSelected);
		}

		$htmlOpt = $this->generateTaxonomyOptionsHtml($productCategory, $filter, $catSelected, $excludeIds);
		if($filter['settings']['f_frontend_type'] === 'list'){
			$style = '';
			if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
				$style = 'max-height:' . $filter['settings']['f_max_height'] . 'px';
			}
			$wrapperStart = '<ul class="wpfFilterVerScroll" style="'.$style.'">';
			$wrapperEnd = '</ul>';
		}else if($filter['settings']['f_frontend_type'] === 'dropdown'){
			$wrapperStart = '<select>';
			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$htmlOpt = '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>' . $htmlOpt;
			}else{
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		}

		$noActive = $catSelected ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.'" data-slug="'.__('category', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$search = $filter['settings']['f_show_search_input'] ? $filter['settings']['f_show_search_input'] : false;
				if($search && $filter['settings']['f_frontend_type'] === 'list'){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.__('Search ...', WPF_LANG_CODE).'"></div>';
				}

				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateTagsFilterHtml($filter, $filterSettings, $blockStyle){
		$includeTagsId = !empty($filter['settings']['f_mlist[]']) ? explode(',', $filter['settings']['f_mlist[]']) : false;
		$order = $filter['settings']['f_sort_by'] ? $filter['settings']['f_sort_by'] : 'asc';
		$excludeIds = !empty($filter['settings']['f_exclude_terms']) ? $filter['settings']['f_exclude_terms'] : false;
		$args = array(
			'order' => $order,
			'parent' => 0,
			'include' => $includeTagsId
		);
		$tagSelected = reqWpf::getVar('product_tag');
		if($tagSelected){
			$tagSelected = explode(' ', $tagSelected);
		}
		$productTag = $this->getTaxonomyHierarchy( 'product_tag', $args);
		if(!$productTag){
			return '';
		}

		$htmlOpt = $this->generateTaxonomyOptionsHtml($productTag, $filter, $tagSelected, $excludeIds);

		if($filter['settings']['f_frontend_type'] === 'list'){
			$style = '';
			if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
				$style = 'max-height:' . $filter['settings']['f_max_height'] . 'px';
			}
			$wrapperStart = '<ul class="wpfFilterVerScroll" style="'.$style.'">';
			$wrapperEnd = '</ul>';
		}else if($filter['settings']['f_frontend_type'] === 'dropdown'){
			$wrapperStart = '<select>';
			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$htmlOpt = '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>' . $htmlOpt;
			}else{
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		}else if($filter['settings']['f_frontend_type'] === 'mul_dropdown'){
			$wrapperStart = '<select multiple>';
			$wrapperEnd = '</select>';
		}

		$noActive = reqWpf::getVar('product_tag') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="product_tag" data-slug="'.__('tag', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$search = $filter['settings']['f_show_search_input'] ? $filter['settings']['f_show_search_input'] : false;
				if($search && $filter['settings']['f_frontend_type'] === 'list'){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.__('Search ...', WPF_LANG_CODE).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateAuthorFilterHtml($filter, $filterSettings, $blockStyle){
		$roleNames = !empty($filter['settings']['f_mlist[]']) ? explode(',', $filter['settings']['f_mlist[]']) : false;
		$filterName = 'pr_author';

		//show all roles if user not make choise
		if(!$roleNames){
			if ( ! function_exists( 'get_editable_roles' ) ) {
				require_once ABSPATH . 'wp-admin/includes/user.php';
			}
			$rolesMain = get_editable_roles();
			foreach($rolesMain as $key => $role){
				$roleNames[] = $key;
			}
		}

		$args = array(
			'role__in' => $roleNames,
			'fields' => array('ID','display_name', 'user_nicename')
		);
		$usersMain = get_users( $args );

		$users = array();
		foreach($usersMain as $key => $user){
			$u = new stdClass;
			$u->term_id = $user->ID;
			$u->name = $user->display_name;
			$u->slug = $user->user_nicename;
			$users[] = $u;
		}

		$authorSelected = reqWpf::getVar('pr_author');
		$htmlOpt = $this->generateTaxonomyOptionsHtml($users, $filter, array($authorSelected));

		if($filter['settings']['f_frontend_type'] === 'list'){
			$style = '';
			if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
				$style = 'max-height:' . $filter['settings']['f_max_height'] . 'px';
			}
			$wrapperStart = '<ul class="wpfFilterVerScroll" style="'.$style.'">';
			$wrapperEnd = '</ul>';
		}else if($filter['settings']['f_frontend_type'] === 'dropdown'){
			$wrapperStart = '<select>';
			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$htmlOpt = '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>' . $htmlOpt;
			}else{
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		}

		$noActive = reqWpf::getVar('pr_author') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.'" data-slug="'.__('author', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$search = $filter['settings']['f_show_search_input'] ? $filter['settings']['f_show_search_input'] : false;
				if($search && $filter['settings']['f_frontend_type'] === 'list'){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.__('Search ...', WPF_LANG_CODE).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;

	}

	public function generateFeaturedFilterHtml($filter, $filterSettings, $blockStyle){
		$filterName = 'pr_featured';
		$wrapperStart = '<ul class="wpfFilterVerScroll">';
		$wrapperEnd = '</ul>';

		$u = new stdClass;
		$u->term_id = '1';
		$u->name = 'Featured';
		$u->slug = '1';
		$feature[] = $u;

		$featureSelected = reqWpf::getVar('pr_featured');
		$filter['settings']['f_frontend_type'] = 'list';
		$htmlOpt = $this->generateTaxonomyOptionsHtml($feature, $filter, array($featureSelected));

		$noActive = reqWpf::getVar('pr_featured') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.'" data-slug="'.__('featured', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper


		return $html;
	}

	public function generateOnSaleFilterHtml($filter, $filterSettings, $blockStyle){
		$filterName = 'pr_onsale';

		$wrapperStart = '<ul class="wpfFilterVerScroll">';
		$wrapperEnd = '</ul>';

		$u = new stdClass;
		$u->term_id = '1';
		$u->name = 'OnSale';
		$u->slug = '1';
		$onSale[] = $u;

		$onSaleSelected = reqWpf::getVar('pr_onsale');
		$filter['settings']['f_frontend_type'] = 'list';
		$htmlOpt = $this->generateTaxonomyOptionsHtml($onSale, $filter, array($onSaleSelected));

		$noActive = reqWpf::getVar('pr_onsale') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.'" data-slug="'.__('on sale', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateInStockFilterHtml($filter, $filterSettings, $blockStyle){
		$filterName = 'pr_stock';

		$wrapperStart = '<ul class="wpfFilterVerScroll">';
		$wrapperEnd = '</ul>';

		$u = new stdClass;
		$u->term_id = '1';
		$u->name = 'InStock';
		$u->slug = '1';
		$inStock[] = $u;

		$inStockSelected = reqWpf::getVar('pr_stock');
		$filter['settings']['f_frontend_type'] = 'list';
		$htmlOpt = $this->generateTaxonomyOptionsHtml($inStock, $filter, array($inStockSelected));

		$noActive = reqWpf::getVar('pr_stock') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.'" data-slug="'.__('in stock', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateRatingFilterHtml($filter, $filterSettings, $blockStyle){
		$filterName = 'pr_rating';
		$ratingSelected = reqWpf::getVar($filterName);

		$settings = $this->getFilterSetting($filter, 'settings', array());
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, array('list', 'dropdown', 'mul_dropdown'));
		$filter['settings']['f_frontend_type'] = $type;

		$wrapperStart = '<ul class="wpfFilterVerScroll">';
		$wrapperEnd = '</ul>';

		$ratingItems = array(
			['1', __('5 Only', WPF_LANG_CODE), '5-5'],
			['2', __('between 4 and 5', WPF_LANG_CODE), '4-5'],
			['3', __('between 3 and 4', WPF_LANG_CODE), '3-4'],
			['4', __('between 2 and 3', WPF_LANG_CODE), '2-3'],
			['5', __('between 1 and 2', WPF_LANG_CODE), '1-2'],
		);

		$rating = array();

		foreach($ratingItems as $item){
			$u = new stdClass;
			$u->term_id = $item[2];
			$u->name = $item[1];
			$u->slug = $item[2];
			$rating[] = $u;
		}

		$htmlOpt = $this->generateTaxonomyOptionsHtml($rating, $filter, array($ratingSelected));
		
		if($type === 'list') {
			$wrapperStart = '<ul class="wpfFilterVerScroll">';
			$wrapperEnd = '</ul>';
		} else if($type === 'dropdown'){
			$wrapperStart = '<select>';
			$text = $this->getFilterSetting($settings, 'f_dropdown_first_option_text');

			if(!empty($text)){
				$htmlOpt = '<option value="" data-slug="">'.__($text, WPF_LANG_CODE).'</option>' . $htmlOpt;
			} else {
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		} else if($type === 'mul_dropdown') {
			$wrapperStart = '<select multiple>';
			$wrapperEnd = '</select>';
		}
	
		$noActive = reqWpf::getVar($filterName) ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'" data-get-attribute="'.$filterName.'" data-slug="'.__('rating', WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);

				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateAttributeFilterHtml($filter, $filterSettings, $blockStyle){
		$settings = $this->getFilterSetting($filter, 'settings', array());
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, array('list', 'dropdown', 'mul_dropdown'));
		$filter['settings']['f_frontend_type'] = $type;

		$attrId = $this->getFilterSetting($settings, 'f_list', 0, true);
		$order = $this->getFilterSetting($settings, 'f_sort_by', 'asc');
		$excludeIds = $this->getFilterSetting($settings, 'f_exclude_terms', false);
		$args = array(
			'parent' => 0,
			'order' => $order
		);
		$attrName = wc_attribute_taxonomy_name_by_id($attrId);

		$filterNameSlug = str_replace('pa_', '', $attrName);
		$filterName = 'filter_' . $filterNameSlug;
		$attrLabel = strtolower(wc_attribute_label($attrName));

		$attrSelected = reqWpf::getVar($filterName);
		if($attrSelected){
			$slugs = explode('|', $attrSelected);
			if(sizeof($slugs) <= 1) {
				$slugs = explode(',', $attrSelected);
			}
			$attrSelected = $slugs;
		}

		$productAttr = $this->getTaxonomyHierarchy($attrName, $args);
		if(!$productAttr){
			return '';
		}
		$logic = $this->getFilterSetting($settings, 'f_query_logic', 'or', false, array('or', 'and'));
		$search = $this->getFilterSetting($settings, 'f_show_search_input', false);

		$htmlOpt = $this->generateTaxonomyOptionsHtml($productAttr, $filter, $attrSelected, $excludeIds);

		if($type == 'list'){
			$style = '';
			$height = $this->getFilterSetting($settings, 'f_max_height', 0, true);
			if($height > 0){
				$style = 'max-height:'.$height.'px; ';
			}
			$wrapperStart = '<ul class="wpfFilterVerScroll" style="'.$style.'">';
			$wrapperEnd = '</ul>';
		} else if($type == 'dropdown'){
			$wrapperStart = '<select>';
			$htmlOpt = '<option value="" data-slug="">'.$this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE)).'</option>' . $htmlOpt;
			$wrapperEnd = '</select>';
		}else if($type == 'mul_dropdown'){
			$wrapperStart = '<select multiple>';
			$wrapperEnd = '</select>';
		}

		$noActive = reqWpf::getVar($filterName) ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'" data-get-attribute="'.$filterName.'" data-query-logic="'.$logic.'" data-slug="'.__($filterNameSlug, WPF_LANG_CODE).'" style="'.$blockStyle.'">';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($search && $type == 'list'){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.__('Search ...', WPF_LANG_CODE).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function getFilterSetting($settings, $name, $default = '', $num = false, $arr = false) {
		if(!isset($settings[$name]) || empty($settings[$name])) return $default;
		$value = $settings[$name];
		if($num && !is_numeric($value)) return $default;
		if($arr !== false && !in_array($value, $arr)) return $default;
		return $value;
	}

	public function showEditTablepressFormControls() {
		parent::display('woofiltersEditFormControls');
	}

	/**
	 * Recursively get taxonomy and its children
	 *
	 * @param string $taxonomy
	 * @param int $parent - parent term id
	 * @return array
	 */
	public function getTaxonomyHierarchy( $taxonomy, $argsIn ) {
		// only 1 taxonomy
		$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
		// get all direct decendants of the $parent
		$args = array(
			'orderby'    => 'name',
			'order' => $argsIn['order'],
			'hide_empty' => false,
		);

		if(!empty($argsIn['include'])){
			$args['include'] = $argsIn['include'];
		}

		if(!empty($argsIn['parent']) && $argsIn['parent'] !== 0){
			$args['parent'] = $argsIn['parent'];
		}else{
			$args['parent'] = 0;
		}

		if($taxonomy === ''){
			return false;
		}
		$terms = get_terms( $taxonomy, $args );
		// prepare a new array.  these are the children of $parent
		// we'll ultimately copy all the $terms into this new array, but only after they
		// find their own children

		$children = array();
		// go through all the direct decendants of $parent, and gather their children
		foreach ( $terms as $term ){
			if(empty($argsIn['only_parent'])){
				if(!empty($term->term_id)){
					$args = array(
						'orderby'    => 'name',
						'order' => $argsIn['order'],
						'hide_empty' => false,
						'parent' => $term->term_id,
					);

					// recurse to get the direct decendants of "this" term
					$term->children = $this->getTaxonomyHierarchy( $taxonomy, $args );
				}
			}
			// add the term to our new array
			$children[ $term->term_id ] = $term;
		}
		// send the results back to the caller
		return $children;
	}

	private function wpfGetFilteredPrice() {
		global $wpdb;
		global $woocommerce;

		$args       = isset( $woocommerce->query->get_main_query()->query_vars ) ? $woocommerce->query->get_main_query()->query_vars : false;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as wpfMinPrice, max( CEILING( price_meta.meta_value ) ) as wpfMaxPrice FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ";
//		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];
//		if ( $search = WC_Query::get_main_search_query_sql() ) {
//			$sql .= ' AND ' . $search;
//		}

		return $wpdb->get_row( $sql );
	}

	private function generateTaxonomyOptionsHtml($productCategory, $filter = false, $selectedSlug, $excludeIds = false, $pre = ''){
		$html = '';
		if($excludeIds && !is_array($excludeIds) ){
			$excludeIds = explode(',', $excludeIds);
		}

		foreach ($productCategory as $cat) {
			if( !empty($excludeIds) && in_array($cat->term_id, $excludeIds)){
				continue;
			}
			if($filter['settings']['f_frontend_type'] === 'list'){
				$html .= '<li data-term-id="'. $cat->term_id . '" data-term-slug="'.$cat->slug.'">';
				$html .= '<label>';
				$html .= '<span class="wpfCheckbox">';
				$cheched = '';

				if(is_array($selectedSlug) && in_array($cat->slug, $selectedSlug)){
					$cheched = 'checked';
				}
				$html .= '<input type="checkbox" '.$cheched.'>';
				$html .= '</span>';
				$html .= '<span class="wpfDisplay">';
				$html .= '<span class="wpfValue">'.$cat->name.'</span>';
				if(!empty($filter['settings']['f_show_count']) && !empty($cat->count) ){
					$html .= '<span class="wpfCount">('.$cat->count.')</span>';
				}
				$html .= '</span>';

				$html .= '</label>';
				if(!empty($cat->children)){
					if($filter['settings']['f_show_hierarchical']){ $html .= '<ul>'; }
					$html .= $this->generateTaxonomyOptionsHtml($cat->children, $filter, $selectedSlug, $excludeIds);
					if($filter['settings']['f_show_hierarchical']){ $html .= '</ul>'; }

				}
				$html .= '</li>';
			}else if($filter['settings']['f_frontend_type'] === 'dropdown' || $filter['settings']['f_frontend_type'] === 'mul_dropdown'){

				$selected = '';
				if(is_array($selectedSlug) && in_array($cat->slug, $selectedSlug)){
					$selected = 'selected';
				}
				$html .= '<option value="'. $cat->term_id . '" data-slug="'. $cat->slug .'" '.$selected.'>'.$pre.$cat->name.'</option>';

				if(!empty($cat->children) && $filter['settings']['f_show_hierarchical']){
					$html .= $this->generateTaxonomyOptionsHtml($cat->children, $filter, $selectedSlug, false, $pre.'&nbsp;&nbsp;&nbsp;');
				}
			}
		}
		return $html;
	}

	private function generatePriceRangeOptionsHtml($filter, $ranges){
		$html = '';

		$minValue = reqWpf::getVar('min_price');
		$maxValue = reqWpf::getVar('max_price');
		$urlRange = $minValue . ',' . $maxValue;

		if($filter['settings']['f_frontend_type'] === 'list'){
			foreach ($ranges as $range){
				if(!empty($range['1']) && !empty($range['0'])){
					if($range['1'] === 'i'){
					$price = $this->wpf_get_filtered_price();
						$range['1'] = $price->max_price;
					}
					$priceRange = wc_price($range[0]) . ' - ' . wc_price($range[1]);
					$dataRange = $range[0] . ',' . $range[1];
					$checked = '';
					if($dataRange === $urlRange){
						$checked = 'checked';
					}
					$html .= '<li data-range="'. $dataRange .'">';
						$html .= '<label>';
							$html .= '<span class="wpfCheckbox">';
								$html .= '<input type="checkbox" '.$checked.'>';
							$html .= '</span>';
							$html .= '<span class="wpfDisplay">';
								$html .= '<span class="wpfValue">'.$priceRange.'</span>';
							$html .= '</span>';
						$html .= '</label>';
					$html .= '</li>';
				}
				?>
				<?php
			}
		}else if($filter['settings']['f_frontend_type'] === 'dropdown'){
			$html .= '<select>';

			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$html .= '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>';
			}else{
				$html .= '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>';
			}

			foreach ($ranges as $range){
				if(!empty($range['1']) && !empty($range['0'])){
					$priceRange = wc_price($range[0]) . ' - ' . wc_price($range[1]);
					$dataRange = $range[0] . ',' . $range[1];
					$selected = '';
					if($dataRange === $urlRange){
						$selected = 'selected';
					}
					$html .= '<option data-range="'. $dataRange .'" '.$selected.'>'.$priceRange.'</option>';
				}
				?>
				<?php
			}
			$html .= '</select>';
		}

		return $html;
	}

	private function generateLoaderHtml($settings){
		$colorPreview = (isset($settings['settings']['filter_loader_icon_color']) ? $settings['settings']['filter_loader_icon_color'] : 'black');
		$iconName = (isset($settings['settings']['filter_loader_icon_name']) ? $settings['settings']['filter_loader_icon_name'] : 'default');
		$iconNumber = (isset($settings['settings']['filter_loader_icon_number']) ? $settings['settings']['filter_loader_icon_number'] : '0');

		$htmlPreview = '<div class="wpfPreview wpfPreviewLoader wpfHidden">';
		if($iconName === 'default'){
			$htmlPreview .= '<div class="supsystic-filter-loader spinner" style="background-color:'.$colorPreview.'"></div>';
		}else{
			$htmlPreview .= '<div class="supsystic-filter-loader la-'.$iconName.' la-2x" style="color: '.$colorPreview.'">';
			for($i = 1; $i <= $iconNumber; $i++){
				$htmlPreview .= '<div></div>';
			}
			$htmlPreview .= '</div>';
		}
		$htmlPreview .= '</div>';
		return $htmlPreview;
	}

	private function wpf_get_filtered_price() {
		global $wpdb;

		$args       = wc()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}

}
