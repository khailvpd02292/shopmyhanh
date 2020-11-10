(function ($, app) {

	function AdminPage() {
		this.$obj = this;
		this.$allowMultipleFilters = ['wpfAttribute', 'wpfBrand', 'wpfCustomMeta'];
		this.$multiSelectFields = ['f_mlist[]'];
		//this.$noOptionsFilters = ['wpfPrice', 'wpfFeatured', 'wpfOnSale', 'wpfInStock'];
		this.$noOptionsFilters = [''];
		return this.$obj;
	}

	AdminPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		_thisObj.eventsAdminPage();
		_thisObj.eventsFilters();
		_thisObj.setupPriceByHands();
	});

	AdminPage.prototype.choosePriceFilterSkinPopup = (function () {

		jQuery('body').on('click', '.wpfSkinWrapper', function (e) {
			e.preventDefault();
			var el = jQuery(this)
			,	skin = el.attr('data-skin-name');

			jQuery('input[name="settings[price_filter_skin]"]').val(skin);

			$container.empty();
			$container.dialog('close');
		});

		var $container = jQuery('<div id="choosePriceFilterSkinPopup" style="display: none;" title="" /></div>').dialog({
			modal: true
			, autoOpen: false
			, width: 900
			, height: 750
			, buttons: {
				OK: function () {
					$container.empty();
					$container.dialog('close');
				}
				, Cancel: function () {
					$container.empty();
					$container.dialog('close');
				}
			}
		});

		var title = jQuery('.wpfPriceFilterSkin').attr('data-title');
		var contenthtml = jQuery('.wpfPriceFilterSkin').html();
		$container.append(contenthtml);

		$container.dialog("option", "title", title);
		$container.dialog('open');
		return false;
	});

	AdminPage.prototype.chooseIconPopup = (function () {
		var color = jQuery('input[name="settings[filter_loader_icon_color]"]').val();

		jQuery('body').on('click', '#chooseIconPopup .item-inner', function (e) {
			e.preventDefault();
			var el = jQuery(this)
			,	name = el.find('.preicon_img').attr('data-name')
			,	countDiv = el.find('.preicon_img').attr('data-items');

			jQuery('input[name="settings[filter_loader_icon_name]"]').val(name);
			jQuery('input[name="settings[filter_loader_icon_number]"]').val(countDiv);

			if (name === 'default') {
				jQuery('.wpfIconPreview').html('');
				jQuery('.wpfIconPreview').html('<div class="supsystic-filter-loader spinner" style="background-color:' + color + '"></div>');
			} else {
				jQuery('.wpfIconPreview').html('');
				var htmlIcon = ' <div class="supsystic-filter-loader la-' + name + ' la-2x" style="color: ' + color + ';">';
					//to display active elements for loader icon we need add div tags
					for (var i = 0; i < countDiv; i++) {
						htmlIcon += '<div></div>';
					}
				htmlIcon += '</div>';
				jQuery('.wpfIconPreview').html(htmlIcon);
			}
			$container.empty();
			$container.dialog('close');
		});

		var $container = jQuery('<div id="chooseIconPopup" style="display: none;" title="" /></div>').dialog({
			modal: true
			, autoOpen: false
			, width: 900
			, height: 750
			, buttons: {
				OK: function () {
					$container.empty();
					$container.dialog('close');
				}
				, Cancel: function () {
					$container.empty();
					$container.dialog('close');
				}
			}
		});

		var contentHtml = jQuery('.wpfLoaderIconTemplate').clone().removeClass('wpfHidden');
		contentHtml.find('div.preicon_img[data-name="default"] div').css({'backgroundColor': color});
		contentHtml.find('div.preicon_img').not('[data-name="default"]').css({'color':color});
		$container.append(contentHtml);

		var title = jQuery('.chooseLoaderIcon').text();
		$container.dialog("option", "title", title);
		$container.dialog('open');
		return false;
	});

	AdminPage.prototype.setupPriceByHands = (function () {
		var _this = this.$obj;
		var options = {
			modal: true
			, autoOpen: false
			, width: 600
			, height: 400
			, buttons: {
				OK: function () {
					var emptyInput = false;
					var options = '';
					var range = jQuery('#wpfSetupPriceRangeByHand .wpfRangeByHand');

					//check if input is empty
					range.find('input').each(function () {
						if(!jQuery(this).val()) {
							jQuery(this).addClass('wpfWarning');
							emptyInput = true;
						}
					});

					if(!emptyInput){
						var rangeCount = range.length;
						var i = 1;
						range.each(function () {
							var el = jQuery(this);
							options += el.find('.wpfRangeByHandHandlerFrom input').val() + ',';
							if(i === rangeCount){
								options += el.find('.wpfRangeByHandHandlerTo input').val();
							}else{
								options += el.find('.wpfRangeByHandHandlerTo input').val() + ',';
							}

							i++;
						});

						jQuery('input[name="f_range_by_hands_values"]').val(options);
						$container.empty();
						$container.dialog('close');
						_this.saveFilters();
					}

				}
				, Cancel: function () {
					$container.empty();
					$container.dialog('close');

				}
			}
		};
		var $container = jQuery('<div id="wpfSetupPriceRangeByHand"></div>').dialog( options );

		jQuery('body').on('click', '.wpfRangeByHandSetup', function (e) {
			e.preventDefault();
			var appendTemplate = '';
			var priceRange = jQuery('input[name="f_range_by_hands_values"]').val();
			var template = jQuery('.wpfRangeByHandTemplate').clone().html();
			var templAddButton = jQuery('.wpfRangeByHandTemplateAddButton').clone().html();
			$container.empty();

			if(priceRange.length <= 0){
				for(var i = 1; i < 2; i++ ){
					appendTemplate += template;
				}
				appendTemplate += templAddButton;
				$container.append(appendTemplate);
				$container.dialog("option", "title", 'Price Range');
				$container.dialog('open');
			}else{
				var priceRangeArray = priceRange.split(",");
				for(var i = 0; i < priceRangeArray.length/2; i++ ){
					appendTemplate += template;
				}

				appendTemplate += templAddButton;
				$container.append(appendTemplate);
				$container.dialog("option", "title", 'Price Range');
				$container.dialog('open');

				var k = 0;
				jQuery('#wpfSetupPriceRangeByHand input').each(function(){
					var input = jQuery(this);
					if(k < priceRangeArray.length){
						input.val(priceRangeArray[k]);
						k++;
					}else{
						input.closest('.wpfRangeByHand').remove();
					}
				});
			}

		});

		jQuery('body').on('click', '.wpfAddPriceRange', function (e) {
			e.preventDefault();
			var templates = jQuery('.wpfRangeByHandTemplate').clone().html();
			jQuery(templates).insertBefore('.wpfRangeByHandAddButton');
			sortablePrice();
		});

		jQuery('body').on('click', '.wpfRangeByHandRemove', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			_this.closest('.wpfRangeByHand').remove();
		});

		//make properties sortable
		function sortablePrice(){
			jQuery("#wpfSetupPriceRangeByHand").sortable({
				//containment: "parent",
				cursor: "move",
				axis: "y",
				handle: ".wpfRangeByHandHandler"
			});
		}
		sortablePrice();

	});

	AdminPage.prototype.eventsAdminPage = (function () {
		var _thisObj = this.$obj;
		// Initialize Main Tabs
		var $mainTabsContent = jQuery('.row-tab'),
			$mainTabs = jQuery('.wpfSub.tabs-wrapper.wpfMainTabs .button'),
			$currentTab = $mainTabs.filter('.current').attr('href');

		$mainTabsContent.filter($currentTab).addClass('active');

		$mainTabs.on('click', function (e) {
			e.preventDefault();
			var $this = jQuery(this),
				$curTab = $this.attr('href');

			$mainTabsContent.removeClass('active');
			$mainTabs.filter('.current').removeClass('current');
			$this.addClass('current');
			$mainTabsContent.filter($curTab).addClass('active');
		});

		//change Border color in preview and ajax save
		jQuery('.wpfColorObserver .wp-color-result').attr('id', 'wp-color-result-border');

		var observer = new MutationObserver(styleChangedCallback);
		observer.observe(document.getElementById('wp-color-result-border'), {
			attributes: true,
			attributeFilter: ['style'],
		});
		var oldIndex = document.getElementById('wp-color-result-border').style.backgroundColor;

		function styleChangedCallback(mutations) {
			var newIndex = mutations[0].target.style.backgroundColor;
			if (newIndex !== oldIndex) {
				jQuery('.supsystic-filter-loader.spinner').css('background-color', newIndex);
				jQuery('.supsystic-filter-loader').not('.spinner').css('color', newIndex);
			}
		}

		jQuery('.chooseLoaderIcon').on('click', function(e){
			e.preventDefault();
			_thisObj.chooseIconPopup();
		});

		$('body').on('click', '.choosePriceFilterSkin', function (e) {
			e.preventDefault();
			_thisObj.choosePriceFilterSkinPopup();
		});

		jQuery('#wpfFiltersEditForm').submit(function (e) {
			e.preventDefault();
			_thisObj.saveFilters();
			var _this = jQuery(this);
			setTimeout(function() {
				_this.sendFormWpf({
					btn: jQuery('#buttonSave')
					, onSuccess: function (res) {
						var currentUrl = window.location.href;
						if (!res.error && res.data.edit_link && currentUrl !== res.data.edit_link) {
							toeRedirect(res.data.edit_link);
						}
					}
				});
			}, 200);

			return false;

		});

		jQuery('body').on('click', '#buttonDelete', function (e) {
			e.preventDefault();
			var id = jQuery('#wpfFiltersEditForm').attr('data-table-id');

			if (id) {
				var data = {
					mod: 'woofilters',
					action: 'deleteByID',
					id: id,
					pl: 'wpf',
					reqType: "ajax"
				};
				jQuery.ajax({
					url: url,
					data: data,
					type: 'POST',
					success: function (res) {
						var redirectUrl = jQuery('#wpfFiltersEditForm').attr('data-href');
						if (!res.error) {
							toeRedirect(redirectUrl);
						}
					}
				});
			}
		});

		// Work with shortcode copy text
		jQuery('#wpfCopyTextCodeExamples').on('change', function (e) {
			var optName = jQuery(this).val();
			switch (optName) {
				case 'shortcode' :
					jQuery('.wpfCopyTextCodeShowBlock').hide();
					jQuery('.wpfCopyTextCodeShowBlock.shortcode').show();
					break;
				case 'phpcode' :
					jQuery('.wpfCopyTextCodeShowBlock').hide();
					jQuery('.wpfCopyTextCodeShowBlock.phpcode').show();
					break;
			}
		});

		//-- Work with title --//
		$('#wpfFilterTitleShell').on('click', function(){
			$('#wpfFilterTitleLabel').hide();
			$('#wpfFilterTitleTxt').show();
		});

		$('#wpfFilterTitleTxt').on('focusout', function(){
			var filterTitle = $(this).val();
			$('#wpfFilterTitleLabel').text(filterTitle);
			$('#wpfFilterTitleTxt').hide();
			$('#wpfFilterTitleLabel').show();
			$('#buttonSave').trigger('click');
		});
		//-- Work with title --//

		jQuery('body').on('focus', '.wpfFilter div > input', function() {
			if( typeof jQuery(this).attr('placeholder') !== 'undefined' && jQuery(this).attr('placeholder').length > 0){
				jQuery(this).attr('data-placeholder', jQuery(this).attr('placeholder') );
				jQuery(this).attr('placeholder', '');
			}
		});
		jQuery('body').on('blur', '.wpfFilter div > input', function() {
			jQuery(this).attr('placeholder', jQuery(this).attr('data-placeholder'));
		});


	});

	AdminPage.prototype.eventsFilters = (function () {
		var _this = this.$obj;
		var _noOptionsFilters = this.$noOptionsFilters;

		//add new filter
		jQuery('.wpfChooseBlock button').on('click', function(e){
			e.preventDefault();
			var text = jQuery(this).text();
			var id = jQuery(this).attr('id');

			//check if filter exist
			if( jQuery('.wpfFilter[data-filter="'+id+'"]').length ){
				//check if allows multiple filters
				if( _this.$allowMultipleFilters.includes(id) ){
					//add more filter for this type
					wpfAddFilter(id, text);
					//check if current filter already exist if yes make visible delete icon
					if(jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').length > 1){
						jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').find('.wpfDelete').removeClass('wpfVisibilityHidden');
					}
				}
			}else{
				//add filter
				wpfAddFilter(id, text);
			}

			function wpfAddFilter(id, text){
				var optionsTemplate = jQuery('.wpfTemplates .wpfOptionsTemplate table[data-filter="'+id+'"]').clone();
				var blockTemplate = jQuery('.wpfTemplates .wpfFiltersBlockTemplate')
					.clone()
					.removeClass('wpfFiltersBlockTemplate')
					.attr('data-filter', id)
					.attr('data-title', optionsTemplate.attr('data-title'));
				if(_noOptionsFilters.includes(id)){
					blockTemplate.find('.wpfToggle').css({'visibility':'hidden'});
				}
				blockTemplate.find('.wpfOptions').html(optionsTemplate);
				blockTemplate.find('.wpfFilterTitle').text(text);

				jQuery('.wpfFiltersBlock').append(blockTemplate);
				_this.initAttributeFilter(blockTemplate);

				//refresh data in ['settings']['filters']
			}
			jQuery(document.body).trigger('changeTooltips');
		});

		//remove existing filter
		jQuery('.wpfFiltersBlock').on('click', '.wpfFilter a.wpfDelete', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			var id = _this.attr('data-filter');

			jQuery(this).closest('.wpfFilter').remove();
			if(jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').length < 1){
				jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').find('.wpfDelete').addClass('wpfVisibilityHidden');
			}
			//refresh data in ['settings']['filters']
		});

		//show / hide filter options
		jQuery('.wpfFiltersBlock').on('click', '.wpfFilter a.wpfToggle', function(e){
			e.preventDefault();
			var el = jQuery(this);
			var i = el.find('i');
			var span = el.find('.wpfTextSt_1');

			if (i.hasClass('fa-chevron-down')){
				i.removeClass('fa-chevron-down').addClass('fa-chevron-up');
				span.text(span.attr('data-title-open'));
				el.closest('.wpfFilter').find('.wpfOptions').removeClass('wpfHidden');
			}else{
				i.removeClass('fa-chevron-up').addClass('fa-chevron-down');
				span.text(span.attr('data-title-close'));
				el.closest('.wpfFilter').find('.wpfOptions').addClass('wpfHidden');
			}

			//refresh data in ['settings']['filters']
		});

		//make properties sortable
		jQuery(".wpfFiltersBlock").sortable({
			cursor: "move",
			axis: "y",
			handle: ".wpfMove",
			stop: function () {
				jQuery('#buttonSave').trigger('click');
			}

		});

		//after click on tab general build filters
		jQuery('.wpfFilters').on('click', function(){
			displayFiltersTab();
		});

		//after load page display filters tab
		displayFiltersTab();

		function displayFiltersTab(){
			jQuery('.wpfFiltersBlock').html('');
			var defFilters = [
					{'id':'wpfPrice'},
					{'id':'wpfPriceRange'},
					{'id':'wpfSortBy'},
					{'id':'wpfCategory'},
					{'id':'wpfTags'},
					{'id':'wpfAuthor'},
					{'id':'wpfFeatured'},
					{'id':'wpfOnSale'},
					{'id':'wpfInStock'},
					{'id':'wpfRating'},
					{'id':'wpfSearchText'},
				];
			try{
				var filters = JSON.parse(jQuery('input[name="settings[filters][order]"]').val()),
					cntFilters = filters.length;
				defFilters.forEach(function(value){
					var found = false,
						id = value.id;
					for(var i = 0; i < cntFilters; i++) {
						if(filters[i].id == id) {
							found = true;
							break;
						}
					}
					if(!found) {
						filters.push(Object.assign({}, value));
					}
				});
			}catch(e){
				var filters = defFilters;
			}

			filters.forEach(function (value) {
				var id = value.id,
					template = jQuery('.wpfTemplates .wpfOptionsTemplate table[data-filter="'+id+'"]');
				if(template.length == 0) return true;

				var settings = value.settings,
					optionsTemplate = template.clone(),
					text = optionsTemplate.find('input[name=f_name]').val();

				if( typeof settings !== 'undefined' ) {
					optionsTemplate.find('input, select').map(function (index, elm) {
						var name = elm.name;
						if (elm.type === 'checkbox') {

							if (elm.name === 'f_options[]') {
								var checkedArr = settings[name].split(",");
								if (checkedArr.includes(elm.value)) {
									jQuery(elm).prop("checked", true);
								}
							} else {
								jQuery(elm).prop("checked", settings[name]);
							}

						} else if (elm.type === 'select-multiple') {
							if (_this.$multiSelectFields.includes(elm.name)) {
								if (settings[name]) {
									var selectedArr = settings[name].split(",");
									jQuery.each(selectedArr, function (i, e) {
										jQuery(elm).find("option[value='" + e + "']").prop("selected", true);
									});
								}
							}
						} else {
							if(typeof settings[name] !== 'undefined') {
								elm.value = settings[name];
							}
						}
					});
				}
				var blockTemplate = jQuery('.wpfTemplates .wpfFiltersBlockTemplate')
					.clone()
					.removeClass('wpfFiltersBlockTemplate')
					.attr('data-filter', id)
					.attr('data-title', text),
					title = text;
				blockTemplate.find('.wpfOptions').html(optionsTemplate);
				if( id === 'wpfAttribute' ){
					title = blockTemplate.find('select[name="f_list"] option:selected').text();
					text = text + ' - ' + title;
				}
				if(_noOptionsFilters.includes(id)){
					blockTemplate.find('.wpfToggle').css({'visibility':'hidden'});
				}
				blockTemplate.find('.wpfFilterTitle').text(text);
				if( typeof settings !== 'undefined' ){
					blockTemplate.find('.wpfFilterFrontDescOpt input').val(settings['f_description']);
					if(settings['f_enable'] == true){
						blockTemplate.find('.wpfEnable input').prop( "checked", true );
					}
					if(typeof settings['f_title'] !== 'undefined' && settings['f_title'].length > 0) {
						title = settings['f_title'];
					}
				}
				blockTemplate.find('.wpfFilterFrontTitleOpt input').val(title);
				jQuery('.wpfFiltersBlock').append(blockTemplate);

				if(jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').length > 1){
					jQuery('.wpfFiltersBlock .wpfFilter[data-filter="' + id + '"]').find('.wpfDelete').removeClass('wpfVisibilityHidden');
				}
				if(id === 'wpfAttribute'){
					setTimeout(function() {
						_this.initAttributeFilter(blockTemplate, settings);
					}, 200);
				}
			});
			jQuery('#wpfFiltersEditForm select[name="f_mlist[]"]').chosen({ width:"95%" });
			jQuery(document.body).trigger('changeTooltips');

			//filter Price Range - options
			var filterPriceRange = jQuery('.wpfFiltersBlock .wpfFilter[data-filter="wpfPriceRange"]');
			if(filterPriceRange.length) {
				filterPriceRange.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					if($(this).val() == 'dropdown') {
						filterPriceRange.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else {
						filterPriceRange.find('input[name="f_dropdown_first_option_text"]').closest('tr').hide();
					}
				}).trigger('change');
			}

			//filter Category - options
			var filterCategory = jQuery('.wpfFiltersBlock .wpfFilter[data-filter="wpfCategory"]');
			if(filterCategory.length) {
				filterCategory.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					var frontendType = $(this).val();
					filterCategory.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
					if(frontendType == 'dropdown') {
						filterCategory.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterCategory.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Tags - options
			var filterTags = jQuery('.wpfFiltersBlock .wpfFilter[data-filter="wpfTags"]');
			if(filterTags.length) {
				filterTags.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					filterTags.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
					var frontendType = $(this).val();
					if(frontendType == 'dropdown') {
						filterTags.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterTags.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Author - options
			var filterAuthor = jQuery('.wpfFiltersBlock .wpfFilter[data-filter="wpfAuthor"]');
			if(filterAuthor.length) {
				filterAuthor.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					filterAuthor.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
					var frontendType = $(this).val();
					if(frontendType == 'dropdown') {
						filterAuthor.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterAuthor.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Rating - options
			var filterRating = jQuery('.wpfFiltersBlock .wpfFilter[data-filter="wpfRating"]');
			if(filterRating.length) {
				filterRating.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					filterRating.find('input[name^="f_dropdown_"], input[name^="f_stars_"]').closest('tr').hide();
					var type = $(this).val();
					if(type == 'linestars' || type == 'liststars') {
						filterRating.find('input[name^="f_stars_"]').closest('tr').css('display', 'table-row');	
						filterRating.find('.wpfLineStarsRating, .wpfListStarsRating').hide();
						filterRating.find('.'+type+'Block').css('display', 'inline-grid');					
					} else {
						filterRating.find('input[name^="f_'+type+'_"]').closest('tr').css('display', 'table-row');
					}

				}).trigger('change');
			}
		}

		jQuery("body").on('change', 'select[name="f_list"]', function (e) {
			e.preventDefault();
			var _this = jQuery(this),
				changedName = _this.val() == 0 ? '' : ' - ' + _this.find('option:selected').text(),
				startName = _this.closest('.wpfFilter').attr('data-title'),
				fullTitle = startName + changedName;
			_this.closest('.wpfFilter').find('.wpfFilterTitle').text(fullTitle);

		});

		jQuery('.wpfFiltersBlock').on('change', '.wpfAutomaticOrByHand input', function(){
			var _this = jQuery(this);
			var id = _this.closest('.wpfAutomaticOrByHand').attr('id');
			var checked = _this.prop('checked');

			jQuery('.wpfAutomaticOrByHand').not('#'+id).find('input').prop('checked', !checked );
		});

		//Sort by filter. Disable unchecking last two checkbox.
		jQuery('body').on('click', '.wpfFilter[data-filter="wpfSortBy"] input[name="f_options[]"]', function (e) {
			var countCheckedCheckbox = jQuery('.wpfFilter[data-filter="wpfSortBy"]').find('input[name="f_options[]"]:checked').length;
			if(countCheckedCheckbox < 2){
				e.preventDefault();
				return false;
			}
		});

	});

	AdminPage.prototype.initAttributeFilter = (function(filter, settings) {
		var _thisObj = this.$obj,
			proSettings = typeof(_thisObj.initAttributeColorFilter) == 'function' ? _thisObj.initAttributeColorFilter(filter, settings) : null;

		filter.find('select[name="f_frontend_type"]').on('change', function(e){
			e.preventDefault();
			var frType = $(this).val();
			filter.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
			if(proSettings) {
				proSettings.hide();
			}

			if (frType == 'dropdown') {
				filter.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
			} else if (frType == 'list') {
				filter.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
			} else if(proSettings) {
				proSettings.css('display', 'table-row');
			}
		}).trigger('change');
	});

	AdminPage.prototype.saveFilters = (function () {
		var _this = this.$obj;
		var filtersArr = [];
		var i = 0;
		if( jQuery('.wpfFiltersBlock .wpfFilter').length <=0 ){
			return;
		}
		jQuery('.wpfFilter').not('.wpfFiltersBlockTemplate').each(function () {
			var valueToPush = {},
				el = jQuery(this),
				id = 'wpfFilter'+i,
				items = {},
				title = el.find('input[name="f_title"]');
			el.attr('id', id);

			if(title.val() == '') {
				title.val(el.find('.wpfFilterTitle').text());
			}

			jQuery("#" + id +" input, #" + id +" select").map(function(index, elm) {

				if(elm.type === 'checkbox'){
					//for multi checkbox
					if(elm.name === 'f_options[]'){
						if(elm.checked){
							if(typeof items[elm.name] !== 'undefined'){
								var temp = items[elm.name];
								temp = temp + ',' + jQuery(elm).val();
								items[elm.name] = temp;
							}else{
								items[elm.name] = jQuery(elm).val();
							}
						}
					}else{
						items[elm.name] = elm.checked;
					}
				}else if(elm.type === 'select-multiple'){
					if( _this.$multiSelectFields.includes(elm.name) ){
						//add more filter for this type
						var arrayValues = jQuery(elm).val();
						if(arrayValues){
							items[elm.name] = arrayValues.toString();
						}
					}
				}else{
					items[elm.name] = jQuery(elm).val();
				}
			});
			valueToPush['id'] = el.attr('data-filter');
			valueToPush['settings'] = items;
			filtersArr.push(valueToPush);
			i++;
		});

		var filtersJson = JSON.stringify(filtersArr);
		jQuery('input[name="settings[filters][order]"]').val(filtersJson);

	});

	jQuery(document).ready(function () {
		window.wpfAdminPage = new AdminPage();
		window.wpfAdminPage.init();
	});

}(window.jQuery, window.supsystic));

