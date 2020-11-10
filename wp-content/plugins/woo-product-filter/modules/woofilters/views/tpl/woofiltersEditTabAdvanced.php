<div class="row row-tab" id="row-tab-advanced">
	<div class="col-xs-12">
		<table class="form-settings-table">
			<tbody class="col-md-6">
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('CSS editor', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Write you oun css style here', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php
					echo htmlWpf::textarea('settings[css_editor]', array(
						'value' => (isset($this->settings['settings']['css_editor']) ? stripslashes(base64_decode($this->settings['settings']['css_editor'])) : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('JS editor', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Write you oun js code here', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::textarea('settings[js_editor]', array(
						'value' => (isset($this->settings['settings']['js_editor']) ? stripslashes(base64_decode($this->settings['settings']['js_editor'])) : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Display only on page', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Chose page for filter', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[display_on_page]', array(
						'options' => array('shop' => 'Shop', 'category' => 'Product Category', 'both' => 'Both'),
						'value' => (isset($this->settings['settings']['display_on_page']) ? $this->settings['settings']['display_on_page'] : 'both'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Hide filter by title click', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Hide filter by title click.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[hide_filter_icon]', array(
						'checked' => (isset($this->settings['settings']['hide_filter_icon']) ? (int) $this->settings['settings']['hide_filter_icon'] : 1)
					))?>
				</td>
			</tr>
			<!--									<tr class="col-md-12">-->
			<!--										<td class="col-md-5">-->
			<!--											--><?php //_e('Load js in footer', WPF_LANG_CODE)?>
			<!--										</td>-->
			<!--										<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="--><?php //echo esc_html(__('Load js files in footer to increase speed of site loading.', WPF_LANG_CODE))?><!--"></i></td>-->
			<!--										<td class="col-md-5">-->
			<!--											--><?php //echo htmlWpf::checkbox('settings[load_js_in_footer]', array(
			//												'checked' => (isset($this->settings['settings']['load_js_in_footer']) ? (int) $this->settings['settings']['load_js_in_footer'] : '')
			//											))?>
			<!--										</td>-->
			<!--									</tr>-->
			</tbody>

		</table>
	</div>
</div>