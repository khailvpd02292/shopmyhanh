(function ($, app) {

	function WpfFrontendPage() {
		this.$obj = this;
		this.noWoo = this.$obj.checkNoWooPage();
		return this.$obj;
	}

	WpfFrontendPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		_thisObj.eventsPriceFilter();
		_thisObj.eventsFrontend();
		_thisObj.changeSlugByUrl();
		_thisObj.runCustomJs();
		_thisObj.addCustomCss();
	});

	WpfFrontendPage.prototype.runCustomJs = (function () {
		var _thisObj = this.$obj;
		jQuery('.wpfMainWrapper').each(function () {
			var wrapper = jQuery(this);
			var jsCodeStr = '';
			var settings = _thisObj.getFilterMainSettings(wrapper);
			if(settings){
				settings = settings.settings;
				jsCodeStr = settings.js_editor;
			}
			if(jsCodeStr.length > 0){
				try {
					eval(jsCodeStr);
				}catch(e) {
					console.log(e);
				}

			}
		});
	});

	WpfFrontendPage.prototype.addCustomCss = (function () {
		var _thisObj = this.$obj;
		jQuery('.wpfMainWrapper').each(function () {
			var wrapper = jQuery(this);
			var cssCodeStr = '';
			var settings = _thisObj.getFilterMainSettings(wrapper);
			if(settings){
				settings = settings.settings;
				cssCodeStr = '<style> ' + settings.css_editor + '</style>';
			}
			if(cssCodeStr.length > 0){
				jQuery( cssCodeStr ).insertBefore( wrapper );
			}
		});

	});

	WpfFrontendPage.prototype.eventsPriceFilter = (function () {
		var _thisObj = this.$obj;

		jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').each(function () {
			var filter = jQuery(this);
			var wrapper = filter.closest('.wpfMainWrapper');
			var minSelector = filter.closest('.wpfFilterWrapper').find('#wpfMinPrice');
			var maxSelector = filter.closest('.wpfFilterWrapper').find('#wpfMaxPrice');
			var valMin = parseFloat(minSelector.attr('min'));
			var valMax = parseFloat(maxSelector.attr('max'));
			var curUrl = window.location.href;
			var urlParams = _thisObj.findGetParameter(curUrl);
			var minPriceGetParams = urlParams.min_price ? parseFloat(urlParams.min_price) : valMin;
			var maxPriceGetParams = urlParams.max_price ? parseFloat(urlParams.max_price) : valMax;
			var sliderWrapper = filter.find("#wpfSliderRange");
			var settings = _thisObj.getFilterMainSettings(wrapper);
			if(settings){
				settings = settings.settings;
				var ajaxModeEnable = settings.enable_ajax === '1';
			}

			if(settings.price_filter_skin === 'default'){
				setTimeout(function() {
					sliderWrapper.slider({
						range: true,
						orientation: "horizontal",
						min: valMin,
						max: valMax,
						values: [minPriceGetParams, maxPriceGetParams],
						step: 1,
						slide: function (event, ui) {
							minSelector.val(ui.values[0]);
							maxSelector.val(ui.values[1]);
							jQuery(document.body).trigger("wpfPriceChange");
						},
						start: function () {
							jQuery(document.body).trigger('wpfPriceChange');
						},
						stop: function () {
							if(ajaxModeEnable){
								_thisObj.filtering();
							}
						}
					});
					minSelector.val(sliderWrapper.slider("values", 0));
					maxSelector.val(sliderWrapper.slider("values", 1));
				}, 200);
			}else if(settings.price_filter_skin === 'skin1'){
				var sliderWrapper = jQuery("#wpfSlider");
				sliderWrapper.attr('value', minPriceGetParams + ';' + maxPriceGetParams);
				var countCall = 0;
				var timeOut = 0;
				setTimeout(function() {
					sliderWrapper.slideregor({
						from: valMin,
						to: valMax,
						limits: true,
						step: 1,
						skin: "round",
						onstatechange : function(value) {
							countCall++;
							if(countCall > 2){
								var _this = this;
								var values = _this.getValue().split(';');
								minSelector.val(values[0]);
								maxSelector.val(values[1]);
								if(!sliderWrapper.closest('.wpfFilterWrapper').hasClass('wpfNotActiveSlider')){
									sliderWrapper.closest('.wpfFilterWrapper').removeClass('wpfNotActive');
									clearTimeout(timeOut);
									timeOut = setTimeout(function(){
										jQuery(document.body).trigger('wpfPriceChange');
										if(ajaxModeEnable){
											_thisObj.filtering();
										}
									},1000);
								}

							}
						}
					});

				}, 200);
			}

		});

		//change price filters
		jQuery(document.body).on('wpfPriceChange', function(event){
			jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] input').prop('checked', false);
			jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] select')
				.val(jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] select option:first').val());

			jQuery('#wpfSliderRange').closest('.wpfFilterWrapper').removeClass('wpfNotActive');
			jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"]').addClass('wpfNotActive');
		});

		//change price range
		jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] input, .wpfFilterWrapper[data-filter-type="wpfPriceRange"] select').on('change', function(e){
			e.preventDefault();
			jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').addClass('wpfNotActive');
		})

	});

	WpfFrontendPage.prototype.QStringWork = (function ($attr, $value, $noWooPage, $filterWrapper, $type) {
		if($type === 'change'){
			var curUrl = changeUrl($attr, $value, $noWooPage, $filterWrapper );
			$filterWrapper.attr('data-hide-url', curUrl);
		}else if($type === 'remove'){
			var curUrl = removeQString($attr, $noWooPage, $filterWrapper);
			$filterWrapper.attr('data-hide-url', curUrl);
		}
	});

	WpfFrontendPage.prototype.eventChangeFilter = (function (e) {
		var _thisObj = this.$obj,
			_this = jQuery(e.target),
			settings = _thisObj.getFilterMainSettings(_this.closest('.wpfMainWrapper'));
		_this.closest('.wpfFilterWrapper').removeClass('wpfNotActive');

		if(settings){
			settings = settings.settings;
			var ajaxModeEnable = settings.enable_ajax === '1';
			if(ajaxModeEnable){
				_thisObj.filtering();
			}
		}
	});


	WpfFrontendPage.prototype.eventsFrontend = (function () {
		var _thisObj = this.$obj;

		if(jQuery('.wpfFilterWrapper[data-filter-type="wpfSortBy"]').length == 0) {
			jQuery('.woocommerce-ordering').css('display', 'block');
		}

		//if no enabled filters hide all html
		if(jQuery('.wpfFilterWrapper').length < 1){
			jQuery('.wpfMainWrapper').addClass('wpfHidden');
		}

		//Start filtering
		jQuery('.wpfFilterButton').on('click', function(e){
			e.preventDefault();
			_thisObj.filtering();
		});

		//Clear filters
		jQuery('.wpfClearButton').on('click', function(e){
			e.preventDefault();
			_thisObj.clearFilters();
		});

		//price range choose only one checkbox
		jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] .wpfFilterContent input').on('change', function (e) {
			e.preventDefault();
			var input = jQuery(this);
			var inputs = input.closest('.wpfFilterWrapper').find('input');
			if(input.is(":checked")){
				inputs.prop('checked', false);
				input.prop('checked', true);
			}
		});

		//category choose only one checkbox
		jQuery('.wpfFilterWrapper[data-filter-type="wpfCategory"] .wpfFilterContent input').on('change', function (e) {
			e.preventDefault();
			var input = jQuery(this);
			var inputs = input.closest('.wpfFilterWrapper').find('input');
			if(input.is(":checked")){
				inputs.prop('checked', false);
				input.prop('checked', true);
			}
		});

		//rating choose only one checkbox
		jQuery('.wpfFilterWrapper[data-filter-type="wpfRating"] .wpfFilterContent input').on('change', function (e) {
			e.preventDefault();
			var input = jQuery(this);
			var inputs = input.closest('.wpfFilterWrapper').find('input');
			if(input.is(":checked")){
				inputs.prop('checked', false);
				input.prop('checked', true);
			}
		});
		
		//after change input or dropdown make this filter active
		//check if ajax mode enable and filtering on filter elements change
		jQuery('.wpfFilterWrapper select, .wpfFilterWrapper input:not(.passiveFilter)').on('change', function (e) {
			e.preventDefault();
			_thisObj.eventChangeFilter(e);
		});

		//check if input change move to top
		jQuery('.wpfFilterWrapper input').on('change', function (e) {
			e.preventDefault();
			var _this = jQuery(this);
			var checkboxWrapper = _this.closest('li');
			var checkboxesWrapper = _this.closest('.wpfFilterVerScroll');
			var selector = _this.closest('.wpfMainWrapper');
			var settings = _thisObj.getFilterMainSettings(selector);
			if(settings){
				settings = settings.settings;
				var checkedItemsTop = settings.checked_items_top === '1';
				if(checkedItemsTop){
					if (_this.is(":checked")) {
						checkboxesWrapper.prepend(checkboxWrapper);
					} else {
						checkboxesWrapper.append(checkboxWrapper);
					}
				}
			}
		});

		//search field work
		jQuery('.wpfFilterWrapper .wpfSearchFieldsFilter').on('keyup', function (e) {
			var _this = jQuery(this);
			var wrapper = _this.closest('.wpfFilterWrapper');
			var searchVal = _this.val().toLowerCase();
			wrapper.find('.wpfFilterContent li').filter(function() {
				jQuery(this).toggle(jQuery(this).find('.wpfValue').text().toLowerCase().indexOf(searchVal) > -1)
			});
		});

		//uncheck one slug
		jQuery('body').on('click', '.wpfSlugDelete', function(){
			var _this = jQuery(this);
			var wrapper = _this.closest('.wpfSlug');
			var filterType = wrapper.attr('data-filter-type');
			var filterWrapper = jQuery('.wpfMainWrapper').first();
			var noWooPage = _thisObj.noWoo;

			if(filterType === 'wpfPrice'){
				var getAttr = wrapper.attr('data-get-attribute');
				if(jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').length > 0){
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').addClass('wpfNotActive');
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').find('input').each(function () {
						jQuery(this).prop('checked',false);

					});
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').find('select option:eq(0)').prop('selected', true);
				}
				if(jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"]').length > 0){
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"]').addClass('wpfNotActive');
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"]').find('input').each(function () {
						jQuery(this).prop('checked',false);

					});
					jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"]').find('select option:eq(0)').prop('selected', true);
				}
				var curUrl = removeQString('min_price', noWooPage, filterWrapper);
				filterWrapper.attr('data-hide-url', curUrl);
				var curUrl = removeQString('max_price', noWooPage, filterWrapper);
				filterWrapper.attr('data-hide-url', curUrl);

				_thisObj.filtering();
			}else{
				var filterType = wrapper.attr('data-filter-type');
				var getAttr = wrapper.attr('data-get-attribute');
				if(jQuery('.wpfFilterWrapper[data-filter-type="'+filterType+'"]').length > 0){
					jQuery('.wpfFilterWrapper[data-filter-type="'+filterType+'"]').addClass('wpfNotActive');
					jQuery('.wpfFilterWrapper[data-filter-type="'+filterType+'"]').find('input').each(function () {
						jQuery(this).prop('checked',false);
					});
					jQuery('.wpfFilterWrapper[data-filter-type="'+filterType+'"]').find('select option:eq(0)').prop('selected', true);
				}

				_thisObj.QStringWork(getAttr, '', noWooPage, filterWrapper, 'remove');
				_thisObj.filtering();
			}
		});

		jQuery('body').on('click', '.wpfFilterWrapper .fa-chevron-up',  function(){
			var _this = jQuery(this);
			var content = _this.closest('.wpfFilterWrapper').find('.wpfFilterContent');
			var i = _this.closest('.wpfFilterWrapper').find('i.fa');
			content.hide();
			i.removeClass('fa-chevron-up');
			i.addClass('fa-chevron-down');

		});

		jQuery('body').on('click','.wpfFilterWrapper .fa-chevron-down', function(){
			var _this = jQuery(this);
			var content = _this.closest('.wpfFilterWrapper').find('.wpfFilterContent');
			var i = _this.closest('.wpfFilterWrapper').find('i.fa');
			content.show();
			i.removeClass('fa-chevron-down');
			i.addClass('fa-chevron-up');

		});

		jQuery('body').on('click', '.wpfFilterWrapper .wpfBlockClear',  function(){
			_thisObj.clearFilters($(this).closest('.wpfFilterWrapper'));
		});

		jQuery('body').on('wpffiltering', function () {
			_thisObj.filtering();
		});

		//click on new pagination link with page number
		jQuery('body').on('click', '.wpfNoWooPage .woocommerce-pagination li .page-numbers', function (e) {
			e.preventDefault();
			var _this = jQuery(this);
			var paginationWrapper = _this.closest('.woocommerce-pagination');
			var currentNumber = paginationWrapper.find('.current').text();
			if(!_this.hasClass('next') && !_this.hasClass('prev') ){
				var number = _this.text();
			}else if(_this.hasClass('next')){
				var number = parseInt(currentNumber) + 1;
			}else if(_this.hasClass('prev')){
				var number = (parseInt(currentNumber) - 1) < 1 ? parseInt(currentNumber) - 1 : 1;
			}
			var $queryVars = jQuery('.wpfMainWrapper').first().attr('data-settings');
			try{
				var settings = JSON.parse($queryVars);
			}catch(e){
				var settings = false;
			}
			if(settings){
				settings.paged = number;
				jQuery('.wpfMainWrapper').first().attr('data-settings', JSON.stringify(settings) )
			}

			_thisObj.filtering();
		});

	});

	WpfFrontendPage.prototype.filtering = (function () {
		var _thisObj = this.$obj;
		_thisObj.createOverlay();

		var $filtersDataBackend = [];
		var $filtersDataFrontend = [];
		jQuery('.wpfFilterWrapper').not('.wpfNotActive').each(function () {
			var $filter = jQuery(this);
			var filterType = $filter.attr('data-filter-type');
			var allSettings = _thisObj.getFilterOptionsByType($filter, filterType);
			var valueToPushBackend = {};
			var valueToPushFrontend = {};
			var logic = $filter.attr('data-query-logic');
			if(typeof logic === 'undefined') {
				logic = 'or';
			}

			valueToPushBackend['id'] = filterType;
			valueToPushBackend['logic'] = logic;
			valueToPushBackend['settings'] = allSettings['backend'];

			valueToPushFrontend['id'] = filterType;
			valueToPushFrontend['delim'] = logic == 'or' ? '|' : ',';
			valueToPushFrontend['settings'] = allSettings['frontend'];

			$filtersDataBackend.push(valueToPushBackend);
			$filtersDataFrontend.push(valueToPushFrontend);
		});
		if(jQuery('.wpfFilterWrapper[data-filter-type="wpfSortBy"]').length == 0) {
			var $wooCommerceSort = jQuery('.woocommerce-ordering select');
			if($wooCommerceSort.length > 0) {
				$filtersDataBackend.push({'id': 'wpfSortBy', 'settings': $wooCommerceSort.eq(0).val()});
			}
		}

		_thisObj.changeUrlByFilterParams($filtersDataFrontend);
		_thisObj.changeSlugByUrl();
		//check if exist products class
		//if not exist try reload page with new filters params
		var $productsContainer = $('ul.products');
		if ( !$productsContainer.length
			&& !jQuery('.wpfMainWrapper').attr('data-nowoo') !== typeof undefined
			&& !jQuery('.wpfMainWrapper').attr('data-nowoo') !== false){
			location.reload();
		}

		//get paged params from html
		var $queryVars = jQuery('.wpfMainWrapper').first().attr('data-settings');
		//ajax call to server
		_thisObj.sendFiltersOptionsByAjax($filtersDataBackend, $queryVars);
	});

	WpfFrontendPage.prototype.createOverlay = (function () {
		jQuery('#wpfOverlay').css({'display':'block'});
	});

	WpfFrontendPage.prototype.removeOverlay = (function () {
		jQuery('#wpfOverlay').css({'display':'none'});
	});

	WpfFrontendPage.prototype.clearFilters = (function (filter) {
		var _thisObj = this.$obj;
		var noWooPage = _thisObj.noWoo;

		(filter ? filter : jQuery('.wpfFilterWrapper')).each(function () {
			var $filter = jQuery(this),
				$filterWrapper = $filter.closest('.wpfMainWrapper'),
				filterAttribute = $filter.attr('data-get-attribute'),
				filterType = $filter.attr('data-display-type');

			filterAttribute = filterAttribute.split(",");
			var count = filterAttribute.length;
			for(var i = 0; i<count; i++){
				_thisObj.QStringWork(filterAttribute[i], '', noWooPage, $filterWrapper, 'remove');
			}

			$filter.find('input').prop('checked', false);
			if(filterType == 'mul_dropdown') {
				$filter.find('select').val('');
			} else if(filterType == 'text') {
				$filter.find('input').val('');
			} else {
				$filter.find('select option:first').attr('selected','selected');
			}
			$filter.addClass('wpfNotActive');

			if($filter.attr('data-filter-type') === 'wpfPrice'){
				var min = $filter.find('#wpfMinPrice').attr('min'),
					max = $filter.find('#wpfMaxPrice').attr('max');
				$filter.find('#wpfMinPrice').val(min);
				$filter.find('#wpfMaxPrice').val(max);

				jQuery( "#wpfSliderRange" ).slider( "option", "values", [ min, max ] );
			}
		});
		_thisObj.filtering();
	});

	WpfFrontendPage.prototype.getFilterMainSettings = (function ($selector) {
		var settingsStr = $selector.attr('data-filter-settings');
		try{
			var settings = JSON.parse(settingsStr);
		}catch(e){
			var settings = false;
		}
		return settings;
	});

	WpfFrontendPage.prototype.checkNoWooPage = (function () {
		var noWooPage = false;
		if(jQuery('.wpfMainWrapper').first().attr('data-nowoo')){
			noWooPage = true;
		}
		return noWooPage;
	});

	WpfFrontendPage.prototype.changeUrlByFilterParams = (function ($filtersDataFrontend) {
		var _thisObj = this.$obj;
		var noWooPage = _thisObj.noWoo;
		if (typeof $filtersDataFrontend !== 'undefined' && $filtersDataFrontend.length > 0) {
			// the array is defined and has at least one element
			// var _thisObj = this.$obj;
			var count = $filtersDataFrontend.length;
			var filterWrapper = jQuery('.wpfMainWrapper');
			var priceFlag = true;
			for(var i = 0; i < count; i++){
				switch ($filtersDataFrontend[i]['id']){
					case 'wpfPrice':
						if(priceFlag){
							var minPrice = $filtersDataFrontend[i]['settings']['min_price'];
							var maxPrice = $filtersDataFrontend[i]['settings']['max_price'];

							if (typeof minPrice !== 'undefined' && minPrice.length > 0) {
								_thisObj.QStringWork('min_price', minPrice, noWooPage, filterWrapper, 'change');
							}else{
								_thisObj.QStringWork('min_price', '', noWooPage, filterWrapper, 'remove');
							}
							if (typeof maxPrice !== 'undefined' && maxPrice.length > 0) {
								_thisObj.QStringWork('max_price', maxPrice, noWooPage, filterWrapper, 'change');
							}else{
								_thisObj.QStringWork('max_price', '', noWooPage, filterWrapper, 'remove');
							}
							priceFlag = false;
						}
						break;
					case 'wpfPriceRange':
						if(priceFlag){
							var minPrice = $filtersDataFrontend[i]['settings']['min_price'];
							var maxPrice = $filtersDataFrontend[i]['settings']['max_price'];
							if (typeof minPrice !== 'undefined' && minPrice.length > 0 ) {
								_thisObj.QStringWork('min_price', minPrice, noWooPage, filterWrapper, 'change');
							}else{
								_thisObj.QStringWork('min_price', '', noWooPage, filterWrapper, 'remove');
							}
							if (typeof maxPrice !== 'undefined' && maxPrice.length > 0) {
								_thisObj.QStringWork('max_price', maxPrice, noWooPage, filterWrapper, 'change');
							}else{
								_thisObj.QStringWork('max_price', '', noWooPage, filterWrapper, 'remove');
							}

							priceFlag = false;
						}
						break;
					case 'wpfSortBy':
						var orderby = $filtersDataFrontend[i]['settings']['orderby'];
						if (typeof orderby !== 'undefined' && orderby.length > 0 ) {
							_thisObj.QStringWork('orderby', orderby, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('orderby', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfCategory':
						var product_cat = $filtersDataFrontend[i]['settings']['settings'];
						product_cat = product_cat.join('+');
						if (typeof product_cat !== 'undefined' && product_cat.length > 0) {
							_thisObj.QStringWork('filter_cat', product_cat, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('filter_cat', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfTags':
						var product_tag = $filtersDataFrontend[i]['settings']['settings'];
						product_tag = product_tag.join('+');
						if (typeof product_tag !== 'undefined' && product_tag.length > 0) {
							_thisObj.QStringWork('product_tag', product_tag, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('product_tag', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfAttribute':
						var product_taxonomy = $filtersDataFrontend[i]['settings']['taxonomy'],
							product_attr = $filtersDataFrontend[i]['settings']['settings'],
							delim = $filtersDataFrontend[i]['delim'];
						product_attr = product_attr.join(delim ? delim : '|');
						if (typeof product_attr !== 'undefined' && product_attr.length > 0) {
							_thisObj.QStringWork(product_taxonomy, product_attr, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork(product_taxonomy, '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfAuthor':
						var authorVal = $filtersDataFrontend[i]['settings']['settings'];
						if (typeof authorVal !== 'undefined' && authorVal.length > 0) {
							_thisObj.QStringWork('pr_author', authorVal, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('pr_author', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfFeatured':
						var featureVal = $filtersDataFrontend[i]['settings']['settings'];
						if (typeof featureVal !== 'undefined' && featureVal.length > 0) {
							_thisObj.QStringWork('pr_featured', featureVal, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('pr_featured', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfOnSale':
						var onSaleVal = $filtersDataFrontend[i]['settings']['settings'];
						if (typeof onSaleVal !== 'undefined' && onSaleVal.length > 0) {
							_thisObj.QStringWork('pr_onsale', onSaleVal, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('pr_onsale', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfInStock':
						var inStockVal = $filtersDataFrontend[i]['settings']['settings'];
						if (typeof inStockVal !== 'undefined' && inStockVal.length > 0) {
							_thisObj.QStringWork('pr_stock', inStockVal, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('pr_stock', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					case 'wpfRating':
						var ratingVal = $filtersDataFrontend[i]['settings']['settings'];
						if (typeof ratingVal !== 'undefined' && checkArray(ratingVal) && ratingVal.length > 0 ) {
							_thisObj.QStringWork('pr_rating', ratingVal, noWooPage, filterWrapper, 'change');
						}else{
							_thisObj.QStringWork('pr_rating', '', noWooPage, filterWrapper, 'remove');
						}
						break;
					default:
						if(typeof(_thisObj.changeUrlByFilterParamsPro) == 'function') {
							_thisObj.changeUrlByFilterParamsPro($filtersDataFrontend[i], noWooPage, filterWrapper);
						}
						break;
				}
			}
		}else{
			return false;
		}

	});

	WpfFrontendPage.prototype.changeSlugByUrl = (function () {
		jQuery('.wpfSlugWrapper .wpfSlug').remove();
		var _thisObj = this.$obj;
		var noWooPage = _thisObj.noWoo;
		if(noWooPage){
			if( jQuery('.wpfMainWrapper').first().attr('data-hide-url')){
				var searchParams =  jQuery.toQueryParams(jQuery('.wpfMainWrapper').first().attr('data-hide-url'));
			}
		}else{
			var searchParams = jQuery.toQueryParams(window.location.search);
		}

		for (var key in searchParams) {
			if(key === 'min_price' || key === 'max_price'){
				key = 'min_price,max_price';
			}

			if(jQuery('.wpfFilterWrapper[data-get-attribute="'+key+'"]').length > 0){
				var $slug = jQuery('.wpfFilterWrapper[data-get-attribute="'+key+'"]').first().attr('data-slug');
				var $getAttr = jQuery('.wpfFilterWrapper[data-get-attribute="'+key+'"]').first().attr('data-get-attribute');
				var $filterType = jQuery('.wpfFilterWrapper[data-get-attribute="'+key+'"]').first().attr('data-filter-type');

				var html = '';
				if(jQuery('.wpfSlugWrapper').length > 0){
					if( !jQuery('.wpfSlugWrapper .wpfSlug[data-slug="'+$slug+'"]').length > 0 ) {
						html += '<div class="wpfSlug" data-slug="'+$slug+'" data-get-attribute="'+$getAttr+'" data-filter-type="'+$filterType+'"><div class="wpfSlugTitle">'+$slug+'</div><div class="wpfSlugDelete">x</div></div>';
						jQuery('.wpfSlugWrapper').append(html);
					}
				}else{
					if( !jQuery('.wpfSlugWrapper .wpfSlug[data-slug="'+$slug+'"]').length > 0 ) {
						html += '<div class="wpfSlugWrapper">';
						html += '<div class="wpfSlug" data-slug="'+$slug+'" data-get-attribute="'+$getAttr+'" data-filter-type="'+$filterType+'"><div class="wpfSlugTitle">'+$slug+'</div><div class="wpfSlugDelete">x</div></div>';
						html += '</div>';
						//jQuery('.woocommerce-result-count').after(html);
						jQuery('.storefront-sorting').append(html);
					}
				}
			}
		}

	});

	WpfFrontendPage.prototype.sendFiltersOptionsByAjax = (function ($filtersData, $queryVars) {
		var _thisObj = this.$obj;

		_thisObj.enableFiltersLoader();

		var $filtersOptions = JSON.stringify($filtersData);

		jQuery.sendFormWpf({
			data: {
				mod: 'woofilters',
				action: 'filtersFrontend',
				options: $filtersOptions,
				queryvars: $queryVars,
				currenturl: window.location.href,
			},
			onSuccess: function(res) {
				if(!res.error) {
					//replace products html
					jQuery('ul.products').html(res.data['productHtml']);

					//replace browser url
					history.pushState(null, '', res.data['resultFullUrl']);

					//replace woocommerce-result-count
					var resultCount = res.data['resultCountHtml'];
					resultCount = jQuery('<div/>').html(resultCount).html();
					jQuery('.woocommerce-result-count').replaceWith(resultCount);//.html(resultCount);

					//replace woocommerce-pagination
					var pagination = res.data['paginationHtml'],
						wooPagination = jQuery('.woocommerce-pagination');

					pagination = jQuery('<div/>').html(pagination).html();
					if(wooPagination.length > 0) {
						wooPagination.replaceWith(pagination);
					} else {
						jQuery('.woocommerce-result-count').after(pagination);
					}
					//jQuery('.woocommerce-pagination').html(pagination);

					//if changed min/max price and we have wpfPrice filter
					//we need change slider
					_thisObj.getUrlParamsChangeFiltersValues();

					_thisObj.disableFiltersLoader();
					_thisObj.removeOverlay();
				}
			}
		});

	});

	WpfFrontendPage.prototype.enableFiltersLoader =(function(){
		var preview = jQuery('.wpfPreviewLoader').first().clone().removeClass('wpfHidden');
		jQuery('ul.products').html(preview);
	});

	WpfFrontendPage.prototype.disableFiltersLoader =(function(){
		jQuery('.wpfPreviewLoader').first().clone().addClass('wpfHidden');
	});

	//after load change price and price range filters params if find get params
	WpfFrontendPage.prototype.getUrlParamsChangeFiltersValues =(function(){
		var _thisObj = this.$obj;
		var noWooPage = _thisObj.noWoo;
		if(noWooPage){
			var curUrl = jQuery('.wpfMainWrapper').first().attr('data-hide-url');
		}else{
			var curUrl = window.location.href;
		}
		if(!curUrl){
			return;
		}
		//get all get params
		var urlParams = _thisObj.findGetParameter(curUrl);
		jQuery('.wpfFilterWrapper').each(function () {
			var $filter = jQuery(this);
			var filterType = $filter.attr('data-filter-type');
			var settings = _thisObj.getFilterMainSettings($filter.closest('.wpfMainWrapper'));

			switch(filterType){
				case 'wpfPrice':
					var minPrice = urlParams.min_price ? urlParams.min_price : $filter.attr('data-minvalue');
					var maxPrice = urlParams.max_price ? urlParams.max_price : $filter.attr('data-maxvalue');

					if(minPrice){
						jQuery('#wpfMinPrice').val(minPrice);
					}
					if(maxPrice){
						jQuery('#wpfMaxPrice').val(maxPrice);
					}
					if(settings){
						settings = settings.settings;
					}
					if(settings.price_filter_skin === 'default'){
						var sliderWrapper = $filter.find("#wpfSliderRange");
					}else if(settings.price_filter_skin === 'skin1'){
						var sliderWrapper = $filter.find("#wpfSlider");
						$filter.addClass('wpfNotActiveSlider');
					}
					if(minPrice && maxPrice){
						if(settings.price_filter_skin === 'default'){
							sliderWrapper.slider({
								values: [minPrice, maxPrice]
							});
						}else if(settings.price_filter_skin === 'skin1'){
							sliderWrapper.slideregor("value", minPrice, maxPrice);
							$filter.removeClass('wpfNotActiveSlider');
						}

					}
					break;
				case 'wpfPriceRange':
					var minPrice = urlParams.min_price ? parseInt(urlParams.min_price) : false;
					var maxPrice = urlParams.max_price ? parseInt(urlParams.max_price) : false;
					$filter.find('li').each(function () {
						var _this = jQuery(this);
						var range = _this.attr('data-range');
						range = range.split(",");
						var minRange = parseFloat(range[0]);
						var maxRange = parseFloat(range[1]);
						if(minPrice === minRange && maxPrice === maxRange ){
							_this.find('input').prop('checked', true);
						}
					});
					break;
			}
		});

	});

	WpfFrontendPage.prototype.findGetParameter =(function(url){
		// This function is anonymous, is executed immediately and
		// the return value is assigned to QueryString!
		var query_string = {};
		var usefulParam = url.split("?")[1] || "";
		var query = usefulParam || "";
		var vars = query.split("&");

		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split("=");

			// If first entry with this name
			if (typeof query_string[pair[0]] === "undefined") {
				query_string[pair[0]] = decodeURIComponent(pair[1]);
				// If second entry with this name
			} else if (typeof query_string[pair[0]] === "string") {
				var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
				query_string[pair[0]] = arr;
				// If third or later entry with this name
			} else {
				query_string[pair[0]].push(decodeURIComponent(pair[1]));
			}
		}
		return query_string;
	});

	WpfFrontendPage.prototype.getFilterOptionsByType = (function($filter, filterType){
		var _thisObj = this.$obj;
		return _thisObj['get' + filterType.replace('wpf', '') + 'FilterOptions']($filter);
	});

	WpfFrontendPage.prototype.getPriceFilterOptions = (function ($filter) {
		var optionsArray = [];

		//options for backend (filtering)
		var options = [];
		var minPrice = $filter.find('#wpfMinPrice').val();
		var maxPrice = $filter.find('#wpfMaxPrice').val();

		var str = minPrice + ',' + maxPrice;
		options.push(str);

		//options for frontend(change url)
		var frontendOptions = [];
		var getParams = $filter.attr('data-get-attribute');
		getParams = getParams.split(",");
		for (var i = 0; i < getParams.length; i++) {
			if(i === 0){
				frontendOptions[getParams[i]] = minPrice;
			}
			if(i === 1){
				frontendOptions[getParams[i]] = maxPrice;
			}
		}
		optionsArray['backend'] = options;
		optionsArray['frontend'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getPriceRangeFilterOptions = (function ($filter) {
		var optionsArray = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			if($filter.find("input:checked")){
				options[i] = $filter.find('input:checked').closest('li').attr('data-range');
			}
		}else if($filter.attr('data-display-type') === 'dropdown'){
			if($filter.find(":selected").attr('data-range')){
				options[i] = $filter.find(":selected").attr('data-range');
			}

		}

		var frontendOptions = [];
		//options for frontend(change url)
		if (typeof options !== 'undefined' && options.length > 0) {
			var getParams = $filter.attr('data-get-attribute');
			getParams = getParams.split(",");
			if (typeof options[0] !== 'undefined' && options[0].length > 0) {
				var prices = options[0].split(',');
				frontendOptions[getParams[0]] = prices[0];
				frontendOptions[getParams[1]] = prices[1];
			}
		}

		optionsArray['backend'] = options;
		optionsArray['frontend'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getSortByFilterOptions = (function ($filter) {
		var optionsArray = [];

		//options for backend (filtering)
		optionsArray['backend'] =  $filter.find('option:selected').val();

		//options for frontend(change url)
		var frontendOptions = [];
		var getParams = $filter.attr('data-get-attribute');
		frontendOptions[getParams] = $filter.find('option:selected').val();
		optionsArray['frontend'] = frontendOptions;
		return optionsArray;
	});

	WpfFrontendPage.prototype.getCategoryFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			var value = $filter.find(":selected").val();
			if(value != '') {
				options[i] = value;
			}
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getTagsFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}else if($filter.attr('data-display-type') === 'mul_dropdown'){
			$filter.find(':selected').each(function () {
				options[i] = jQuery(this).val();
				frontendOptions[i] = jQuery(this).attr('data-slug');
				i++;
			});
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getAttributeFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'colors'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).attr('data-term-id');
				frontendOptions[i] = jQuery(this).attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}else if($filter.attr('data-display-type') === 'mul_dropdown'){
			$filter.find(':selected').each(function () {
				options[i] = jQuery(this).val();
				frontendOptions[i] = jQuery(this).attr('data-slug');
				i++;
			});
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getAuthorFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			if($filter.find(":selected").attr('data-slug').length > 0){
				frontendOptions[i] = $filter.find(":selected").attr('data-slug');
			}
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getFeaturedFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getOnSaleFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			// frontendOptions[i] = $filter.find(":selected").attr('data-slug');
			frontendOptions[i] = $filter.find(":selected").val();
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getInStockFilterOptions = (function ($filter) {
		var optionsArray = [];
		var frontendOptions = [];

		//options for backend (filtering)
		var options = [];
		var i = 0;
		if($filter.attr('data-display-type') === 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if($filter.attr('data-display-type') === 'dropdown'){
			options[i] = $filter.find(":selected").val();
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	WpfFrontendPage.prototype.getRatingFilterOptions = (function ($filter) {
		var optionsArray = [],
			frontendOptions = [];

		//options for backend (filtering)
		var options = [],
			filterType = $filter.attr('data-display-type');
			i = 0;
		if(filterType == 'linestars' || filterType == 'liststars'){
			var rating = $filter.find('input[name=ratingStar]:checked').val();
			options[i] = rating;
			frontendOptions[i] = rating;
		}else if(filterType == 'list'){
			$filter.find('input:checked').each(function () {
				options[i] = jQuery(this).closest('li').attr('data-term-id');
				frontendOptions[i] = jQuery(this).closest('li').attr('data-term-slug');
				i++;
			});
		}else if(filterType == 'dropdown'){
			options[i] = $filter.find(":selected").val();
			frontendOptions[i] = $filter.find(":selected").attr('data-slug');
		}else if(filterType == 'mul_dropdown'){
			$filter.find(':selected').each(function () {
				options[i] = jQuery(this).val();
				frontendOptions[i] = jQuery(this).attr('data-slug');
				i++;
			});
		}
		optionsArray['backend'] = options;

		//options for frontend(change url)
		var getParams = $filter.attr('data-get-attribute');

		optionsArray['frontend'] = [];
		optionsArray['frontend']['taxonomy'] = getParams;
		optionsArray['frontend']['settings'] = frontendOptions;

		return optionsArray;
	});

	jQuery(document).ready(function () {
		window.wpfFrontendPage = new WpfFrontendPage();
		window.wpfFrontendPage.init();
	});

}(window.jQuery));

var objQueryString={};

//Get querystring value
function getParameterByName(name, searchUrl) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(searchUrl);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

//Add or modify querystring
function changeUrl(key, value, $wooPage = false, $filterWrapper) {
	removePageQString();
	//Get query string value
	if(!$wooPage){
		var searchUrl = decodeURIComponent(location.search);
	}else{
		if($filterWrapper.attr('data-hide-url')){
			var searchUrl=$filterWrapper.attr('data-hide-url');
		}else {
			var searchUrl = '';
		}
	}

	if(searchUrl.indexOf("?")== "-1") {
		var urlValue='?'+key+'='+value;
		if(!$wooPage){
			history.pushState({state:1, rand: Math.random()}, '', urlValue);
		}
	} else {
		//Check for key in query string, if not present
		if(searchUrl.indexOf(key)== "-1") {
			var urlValue=searchUrl+'&'+key+'='+value;
		} else {	//If key present in query string
			oldValue = getParameterByName(key, searchUrl);
			var temp = oldValue.split(' ');
			if (typeof temp !== 'undefined' && temp.length > 0) {
				// the array is defined and has at least one element
				oldValue = temp.join('+');
			}
			if(searchUrl.indexOf("?"+key+"=")!= "-1") {
				urlValue = searchUrl.replace('?'+key+'='+oldValue,'?'+key+'='+value);
			}
			else {
				urlValue = searchUrl.replace('&'+key+'='+oldValue,'&'+key+'='+value);
			}
		}
		if(!$wooPage){
			history.pushState({state:1, rand: Math.random()}, '', urlValue);
		}
		//history.pushState function is used to add history state.
		//It takes three parameters: a state object, a title (which is currently ignored), and (optionally) a URL.
	}
	objQueryString.key=value;
	return urlValue;
}

function removePageQString() {
	var path = document.location.pathname,
		page = path.indexOf('/page/');
	if(page != -1 && history.pushState) {
		history.pushState({state:1, rand: Math.random()}, '', path.substr(0, page + 1) + location.search);
	}
}
//Function used to remove querystring
function removeQString(key, $wooPage = false, $filterWrapper) {
	removePageQString();
	//Get query string value
	var urlValue=document.location.href;
	if(!$wooPage){
		var searchUrl=decodeURIComponent(location.search);
	}else{
		if($filterWrapper.attr('data-hide-url')){
			var searchUrl=$filterWrapper.attr('data-hide-url');
		}else {
			var searchUrl = '';
		}
		var urlValue=document.location.href + searchUrl;
	}
	if(key!="") {
		oldValue = getParameterByName(key, searchUrl);
		removeVal=key+"="+oldValue;

		var temp = removeVal.split(' ');
		if (typeof temp !== 'undefined' && temp.length > 0) {
			// the array is defined and has at least one element
			removeVal = temp.join('+');
		}

		if(searchUrl.indexOf('?'+removeVal+'&')!= "-1") {
			urlValue=urlValue.replace('?'+removeVal+'&','?');
		}
		else if(searchUrl.indexOf('&'+removeVal+'&')!= "-1") {
			urlValue=urlValue.replace('&'+removeVal+'&','&');
		}
		else if(searchUrl.indexOf('?'+removeVal)!= "-1") {
			urlValue=urlValue.replace('?'+removeVal,'');
		}
		else if(searchUrl.indexOf('&'+removeVal)!= "-1") {
			urlValue=urlValue.replace('&'+removeVal,'');
		}
		if($wooPage){
			urlValue = urlValue.replace(document.location.href,'');
		}
	} else {
		if(!$wooPage){
			var searchUrl=decodeURIComponent(location.search);
			urlValue=urlValue.replace(searchUrl,'');
		}else{
			var searchUrl=$filterWrapper.attr('data-hide-url');
			urlValue=urlValue.replace(searchUrl,'');
			urlValue = urlValue.replace(document.location.href,'');
		}
	}
	if(!$wooPage){
		history.pushState({state:1, rand: Math.random()}, '', urlValue);
	}

	return urlValue;
}
function checkArray(my_arr){
	for(var i=0;i<my_arr.length;i++){
		if(my_arr[i] === "")
			return false;
	}
	return true;
}
jQuery.toQueryParams = function(str, separator) {
	separator = separator || '&'
	var obj = {}
	if (str.length == 0)
		return obj
	var c = str.substr(0,1)
	var s = c=='?' || c=='#'  ? str.substr(1) : str;

	var a = s.split(separator)
	for (var i=0; i<a.length; i++) {
		var p = a[i].indexOf('=')
		if (p < 0) {
			obj[a[i]] = ''
			continue
		}
		var k = decodeURIComponent(a[i].substr(0,p)),
			v = decodeURIComponent(a[i].substr(p+1))

		var bps = k.indexOf('[')
		if (bps < 0) {
			obj[k] = v
			continue;
		}

		var bpe = k.substr(bps+1).indexOf(']')
		if (bpe < 0) {
			obj[k] = v
			continue;
		}

		var bpv = k.substr(bps+1, bps+bpe-1)
		var k = k.substr(0,bps)
		if (bpv.length <= 0) {
			if (typeof(obj[k]) != 'object') obj[k] = []
			obj[k].push(v)
		} else {
			if (typeof(obj[k]) != 'object') obj[k] = {}
			obj[k][bpv] = v
		}
	}
	return obj;
}