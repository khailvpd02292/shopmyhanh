<div class="row row-tab active" id="row-tab-filters">

	<div class="col-lg-12 col-md-12 wpfFiltersBlock"></div>

	<div class="col-lg-12 col-md-12 wpfChooseBlock">

		<button id="wpfAttribute" class="button"><?php echo __('Add Attribute filter', WPF_LANG_CODE); ?></button>

		<button id="wpfBrand" class="button wpfHidden"><?php echo __('Brand', WPF_LANG_CODE); ?></button>
		<button id="wpfCustomMeta" class="button wpfHidden"><?php echo __('Custom meta', WPF_LANG_CODE); ?></button>

	</div>

	<div class="wpfTemplates wpfHidden">

		<?php
		$priceFilterSkins = array(
			'default' => $this->getModule()->getModPath(). 'img/default.png',
			'skin1' => $this->getModule()->getModPath(). 'img/skin1.png',
		);
		?>
		<div class="wpfPriceFilterSkin" data-title="<?php _e('Chose skin', WPF_LANG_CODE)?>">
			<?php foreach($priceFilterSkins as $name => $imgSrc){?>
				<div class="wpfSkinWrapper" data-skin-name="<?php echo $name; ?>">
					<div class="wpfSkin">
						<img src="<?php echo $imgSrc;?>">
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="wpfRangeByHandTemplate">
			<div class="wpfRangeByHand">
				<div class="wpfRangeByHandHandlerFrom">
					<?php _e('From', WPF_LANG_CODE)?>
					<input type="text" name="from" value="">
				</div>
				<div class="wpfRangeByHandHandlerTo">
					<?php _e('To', WPF_LANG_CODE)?>
					<input type="text" name="to" value="">
				</div>
				<div class="wpfRangeByHandHandler">
					<i class="fa fa-arrows-v"></i>
				</div>
				<div class="wpfRangeByHandRemove">
					<i class="fa fa-trash-o"></i>
				</div>
			</div>
		</div>

		<div class="wpfRangeByHandTemplateAddButton">
			<div class="wpfRangeByHandAddButton">
				<button class="button wpfAddPriceRange"><?php _e('Add', WPF_LANG_CODE)?></button>
			</div>
		</div>

		<div class="wpfFilter wpfFiltersBlockTemplate">

			<a href="#" class="wpfMove"><i class="fa fa-arrows-v"></i></a>
			<div class="wpfEnable"><?php echo htmlWpf::checkbox('f_enable', array())?><?php _e('Enable', WPF_LANG_CODE)?></div>
			<div class="wpfFilterTitle"></div>
			<a href="#" class="wpfDelete wpfVisibilityHidden button button-small"><i class="fa fa-fw fa-trash-o"></i></a>
			<a href="#" class="wpfToggle"><span class="wpfTextSt_1" data-title-close="<?php _e('Show options', WPF_LANG_CODE)?>" data-title-open="<?php _e('Hide options', WPF_LANG_CODE)?>"><?php _e('Show options', WPF_LANG_CODE)?></span> <i class="fa fa-chevron-down"></i></a>
			<div class="wpfFilterFrontDescOpt">
				<?php echo htmlWpf::text('f_description', array(
					'placeholder' => __('Description', WPF_LANG_CODE),
				))?>
			</div>
			<div class="wpfFilterFrontTitleOpt">
				<?php echo htmlWpf::text('f_title', array(
					'placeholder' => __('Title', WPF_LANG_CODE),
				))?>
			</div>
			<div class="wpfOptions wpfHidden"></div>
		</div>

		<div class="wpfOptionsTemplate wpfHidden">
			<table class="form-settings-table" data-filter="wpfPrice">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Price', WPF_LANG_CODE)
				))?>
				</tbody>
			</table>

			<table class="form-settings-table" data-filter="wpfPriceRange">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Depending on whether you need one or several categories to be available at the same time, you may show your categories list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => array('list' => 'Checkbox list', 'dropdown' => 'Dropdown'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE))))?>
					</td>
				</tr>
				<tr id="wpfRangeAuthomatic" class="col-md-12 wpfAutomaticOrByHand">
					<td class="col-md-5">
						<?php _e('Set range automatically', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('If this option is enabled, you may set the price range settings automatically.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_range_automatic', array(
							'checked' => 1
						))?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Set start price', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set the minimum price. If field live empty  will be set the price of the cheapest product from the store.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_min', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Set end price', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set the maximum price. If field live empty  will be set the price of the dearest product from the store.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Set the range between prices', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set the price range between the filter options.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_range', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Set maximum range count', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Evenly calculate the range for a given number of positions.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max_options_count', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Price range step', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may set the value of prise increase step. The default value is set to 20. All the steps are equal.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_step', array(
							'value' => 20,
						))?>
					</td>
				</tr>
				<tr id="wpfRangeByHand" class="col-md-12 wpfAutomaticOrByHand">
					<td class="col-md-5">
						<?php _e('Set range manually', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('If this option is enabled, you may press the "Setup" button and customize your price range settings. You may increase or decrease the number of steps and set different values for each step.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_range_by_hands', array()) ?>
						<button class="button wpfRangeByHandSetup"><?php echo esc_html(__('Setup', WPF_LANG_CODE))?></button>
					</td>
				</tr>
				<?php echo htmlWpf::hidden( 'f_range_by_hands_values', array()) ?>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Price range', WPF_LANG_CODE)
				))?>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Maximum height in frontend', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set maximum displayed height in frontend.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max_height', array('value'=>'200'))?> px
					</td>
				</tr>
				</tbody>
			</table>

			<table class="form-settings-table" data-filter="wpfSortBy">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Sort options', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select the sorting options available for your site users (min two options).', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkboxlist('f_options', array(
							'options' => array(
								array( 'id' => 'default', 'checked' => true, 'text' => esc_html(__('Default', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'popularity', 'checked' => true, 'text' => esc_html(__('Popularity', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'rating', 'checked' => true, 'text' => esc_html(__('Rating', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'date', 'checked' => true, 'text' => esc_html(__('Newness', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'price', 'checked' => true, 'text' => esc_html(__('Price: low to high', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'price-desc', 'checked' => true, 'text' => esc_html(__('Price: high to low', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'rand', 'checked' => true, 'text' => esc_html(__('Random', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
								array( 'id' => 'title', 'checked' => true, 'text' => esc_html(__('Name', WPF_LANG_CODE)), 'usetable' => false, 'attrs' => false),
							)
						))?>
					</td>
				</tr>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Sort by', WPF_LANG_CODE)
				))?>
				</tbody>
			</table>

			<table class="form-settings-table" data-filter="wpfCategory">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Depending on whether you need one or several categories to be available at the same time, you may show your categories list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => array('list' => 'Checkbox list', 'dropdown' => 'Dropdown'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE))))?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Sort by', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may set categories sorting by ascendance or descendance.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_sort_by', array(
							'options' => array('asc' => 'ASC', 'desc' => 'DESC'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Show search field', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show fast search field.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_search_field', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show hierarchical', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show paternal and subsidiary categories (for checkbox list).', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_hierarchical', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Hierarchical limit', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Hierarchical limit .', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_hierarchical_limit', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show count', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show count.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_count', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Product categories', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select product categories to be displayed on your site from the list. If you want to select several categories, hold the "Shift" button and click on category names. Or you can hold "Ctrl" and click on category names. Press "Ctrl" + "a" for checking all categories.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectlist('f_mlist', array(
							'options' => $categoryDisplay,
						))?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Exclude terms ids', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may exclude category terms from filter by ids. Example input: 1,2,3 ', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_exclude_terms', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show search', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show search.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_search_input', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Maximum height in frontend', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set maximum displayed height in frontend.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max_height', array('value'=>'200'))?> px
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Hide child', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Hide child taxonomy.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_hide_taxonomy', array())?>
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Product categories', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfTags">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Depending on whether you need one or several tags to be available at the same time, you may show your tags list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => array('list' => 'Checkbox list', 'dropdown' => 'Dropdown', 'mul_dropdown' => 'Multiple Dropdown'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE))))?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Sort by', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may set tags sorting by ascendance or descendance.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_sort_by', array(
							'options' => array('asc' => 'ASC', 'desc' => 'DESC'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Show search field', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show fast search field.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_search_field', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Show hierarchical', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show hierarchical.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_hierarchical', array())?>
					</td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-5">
						<?php _e('Hierarchical limit', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Hierarchical limit .', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_hierarchical_limit', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show count', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show count.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_count', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Product tags', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select product tags to be displayed on your site from the list. If you want to select several tags, hold the "Shift" button and click on category names. Or you can hold "Ctrl" and click on category names. Press "Ctrl" + "a" for checking all tags. ', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php
						if(!empty($tagsDisplay)){
							echo htmlWpf::selectlist('f_mlist', array(
								'options' => $tagsDisplay,
							));
						}else{
							_e('No tags', WPF_LANG_CODE);
						}
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Exclude terms ids', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may exclude tags terms from filter by ids. Example input: 1,2,3 ', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_exclude_terms', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show search', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show search.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_search_input', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Maximum height in frontend', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set maximum displayed height in frontend.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max_height', array('value'=>'200'))?> px
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Product tags', WPF_LANG_CODE)
				))?>
			</table>

			<table class="form-settings-table" data-filter="wpfAttribute" data-title="Attribute">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Select attribute', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select attribute to be displayed on your site from the list.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php
						if(!empty($attrDisplay)){
							echo htmlWpf::selectbox('f_list', array(
								'options' => $attrDisplay,
							));
						}else{
							_e('No attribute', WPF_LANG_CODE);
						}
						?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Depending on whether you need one or several attributes to be available at the same time, you may show your attributes list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => dispatcherWpf::applyFilters('getAttributeFrontendTypes', array('list' => 'Checkbox list', 'dropdown' => 'Dropdown', 'mul_dropdown' => 'Multiple Dropdown'))
						))
						?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE)), 'attrs' => 'class="wpfSmallInput"'))?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<?php dispatcherWpf::doAction('addEditTabFilters', 'partEditTabFiltersAttributeColors');?>
				<!--<tr class="col-md-12 wpfHidden">
					<td class="col-md-3">
						<?php //_e('Show search field', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php //echo esc_html(__('Show fast search field.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php //echo htmlWpf::checkbox('f_search_field', array())?>
					</td>
				</tr>-->
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-3">
						<?php _e('Show hierarchical', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show hierarchical.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::checkbox('f_show_hierarchical', array())?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12 wpfHidden">
					<td class="col-md-3">
						<?php _e('Hierarchical limit', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Hierarchical limit .', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::checkbox('f_hierarchical_limit', array())?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show count', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show count.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::checkbox('f_show_count', array())?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Logic', WPF_LANG_CODE)?>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_query_logic', array('options' => array('and' => 'And', 'or' => 'Or'), 'value' => 'or'))?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Sort by', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may set attributes sorting by ascendance or descendance.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::selectbox('f_sort_by', array(
							'options' => array('' => __('Don\'t sort', WPF_LANG_CODE), 'asc' => 'ASC', 'desc' => 'DESC'),
						))
						?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show search', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show search.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::checkbox('f_show_search_input', array())?>
					</td>
					<td class="col-md-3"></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Maximum height in frontend', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set maximum displayed height in frontend.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-6">
						<?php echo htmlWpf::text('f_max_height', array('value'=>'200', 'attrs' => 'class="wpfSmallInput"'))?> px
					</td>
					<td class="col-md-3"></td>
				</tr>
				<!--										<tr class="col-md-12">-->
				<!--											<td class="col-md-5">-->
				<!--												--><?php //_e('Hide child', WPF_LANG_CODE)?>
				<!--												<i class="fa fa-question supsystic-tooltip" title="--><?php //echo esc_html(__('Hide child taxonomy.', WPF_LANG_CODE))?><!--"></i>-->
				<!--											</td>-->
				<!--											<td class="col-md-5">-->
				<!--												--><?php //echo htmlWpf::checkbox('f_hide_taxonomy', array())?>
				<!--											</td>-->
				<!--										</tr>-->
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Attribute', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfAuthor">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('You can define which role show users in the drop down', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You can define which role show users in the drop down', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectlist('f_mlist', array(
							'options' => $roles,
						))?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You may show your roles list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => array('list' => 'Checkbox list', 'dropdown' => 'Dropdown'),
						))
						?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE))))?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show search', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show search.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::checkbox('f_show_search_input', array())?>
					</td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Maximum height in frontend', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Set maximum displayed height in frontend.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_max_height', array('value'=>'200'))?> px
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Author', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfFeatured">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Featured', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfOnSale">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('On sale', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfInStock">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-5">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
				</tr>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('In Stock', WPF_LANG_CODE)
				))?>
			</table>
			<table class="form-settings-table" data-filter="wpfRating">
				<tbody class="col-md-12">
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show title label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show title label', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_enable_title', array(
							'options' => array('no' => 'No', 'yes_close' => 'Yes, show as close', 'yes_open' => 'Yes, show as opened'),
						));
						?>
					</td>
					<td></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Show in frontend as', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Depending on whether you need one or several attributes to be available at the same time, you may show your attributes list as checkbox or dropdown.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::selectbox('f_frontend_type', array(
							'options' => dispatcherWpf::applyFilters('getRatingFrontendTypes', array('list' => 'Checkbox list', 'dropdown' => 'Dropdown'))
						))
						?>
					</td>
					<td></td>
				</tr>
				<tr class="col-md-12">
					<td class="col-md-3">
						<?php _e('Dropdown label', WPF_LANG_CODE)?>
						<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Dropdown first option text.', WPF_LANG_CODE))?>"></i>
					</td>
					<td class="col-md-5">
						<?php echo htmlWpf::text('f_dropdown_first_option_text', array('placeholder' => esc_html(__('Select all', WPF_LANG_CODE)), 'attrs' => 'class="wpfSmallInput"'))?>
					</td>
					<td></td>
				</tr>
				<?php dispatcherWpf::doAction('addEditTabFilters', 'partEditTabFiltersRatingStars');?>
				</tbody>
				<?php echo htmlWpf::hidden('f_name', array(
					'value' => __('Rating', WPF_LANG_CODE)
				))?>
			</table>
			<?php dispatcherWpf::doAction('addEditTabFilters', 'woofiltersEditTabFilters');?>
		</div>
	</div>
</div>