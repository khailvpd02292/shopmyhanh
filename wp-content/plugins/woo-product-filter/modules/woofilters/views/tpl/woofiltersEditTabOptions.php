<div class="row row-tab" id="row-tab-options">
	<div class="col-xs-12">
		<table class="form-settings-table">
			<tbody class="col-md-6">
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Show Term Products Count', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show the number of products next to the title.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[show_products_count]', array(
						'checked' => (isset($this->settings['settings']['show_products_count']) ? (int) $this->settings['settings']['show_products_count'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Show Term Search Fields', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('show the search field in supported filters.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[show_products_search]', array(
						'checked' => (isset($this->settings['settings']['show_products_search']) ? (int) $this->settings['settings']['show_products_search'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Stepped Filter Selection', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('After selection filter options, you can confirm filtering in "Show result" popup.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[stepped_selection]', array(
						'checked' => (isset($this->settings['settings']['stepped_selection']) ? (int) $this->settings['settings']['stepped_selection'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Enable ajax', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This option enables Ajax search. Filtering starts as soon as filter elements change and the page reloads automatically.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[enable_ajax]', array(
						'checked' => (isset($this->settings['settings']['enable_ajax']) ? (int) $this->settings['settings']['enable_ajax'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Selected Terms Collector', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show the selected filter elements above the products, with the ability to remove them from the filter.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[selected_terms_collector]', array(
						'checked' => (isset($this->settings['settings']['selected_terms_collector']) ? (int) $this->settings['settings']['selected_terms_collector'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Show Clear all button', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('If this option is enabled, the "Clear" button appears at the page. All filter presets will be removed after pressing the button.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[show_clean_button]', array(
						'checked' => (isset($this->settings['settings']['show_clean_button']) ? (int) $this->settings['settings']['show_clean_button'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Show Clear block', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('If this option is enabled, the "< clear" links appears at the page next to the filter block titles. The presets of this filter block will be deleted after clicking on the link.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[show_clean_block]', array(
						'checked' => (isset($this->settings['settings']['show_clean_block']) ? (int) $this->settings['settings']['show_clean_block'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Show Filtering button', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This button is necessary, when ajax mode is disabled. It allows users to set all necessary filter parameters before starting the filtering.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[show_filtering_button]', array(
						'checked' => (isset($this->settings['settings']['show_filtering_button']) ? (int) $this->settings['settings']['show_filtering_button'] : 1)
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Display filter on', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Chose where display filter', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[display_for]', array(
						'options' => array('mobile' => 'Yes, only for mobile', 'desktop' => 'Yes, only for desktop', 'both' => 'Yes, for all device'),
						'value' => (isset($this->settings['settings']['display_for']) ? $this->settings['settings']['display_for'] : 'both'),
					))
					?>
				</td>
			</tr>
			<!--									<tr class="col-md-12">-->
			<!--										<td class="col-md-5">-->
			<!--											--><?php //_e('Show count', WPF_LANG_CODE)?>
			<!--										</td>-->
			<!--										<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="--><?php //echo esc_html(__('Show count near the terms.', WPF_LANG_CODE))?><!--"></i></td>-->
			<!--										<td class="col-md-5">-->
			<!--											--><?php //echo htmlWpf::checkbox('settings[show_count]', array(
			//												'checked' => (isset($this->settings['settings']['show_count']) ? (int) $this->settings['settings']['show_count'] : 1)
			//											))?>
			<!--										</td>-->
			<!--									</tr>-->
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Checked items to the top', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Lets checked terms will be on the top', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[checked_items_top]', array(
						'checked' => (isset($this->settings['settings']['checked_items_top']) ? (int) $this->settings['settings']['checked_items_top'] : '')
					))?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>