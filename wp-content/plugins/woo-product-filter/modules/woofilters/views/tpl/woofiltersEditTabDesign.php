<div class="row row-tab" id="row-tab-design">
	<div class="col-xs-12">
		<table class="form-settings-table">
			<tbody class="col-md-6">
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Filter Width', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you can set the filter width in pixels or percent.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<div class="wpfCombineOptions">
					<?php echo htmlWpf::text('settings[filter_width]', array(
						'value' => isset($this->settings['settings']['filter_width']) ? $this->settings['settings']['filter_width'] : '100',
						'attrs' => 'class="wpfSmallInput"'));
						echo htmlWpf::selectbox('settings[filter_width_in]', array(
						'options' => array('%' => '%', 'px' => 'px'),
						'value' => (isset($this->settings['settings']['filter_width_in']) ? $this->settings['settings']['filter_width_in'] : '%'),
					))
					?>
					</div>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Filter Block Width', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you can set the filter block width in pixels or percent.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<div class="wpfCombineOptions">
					<?php echo htmlWpf::text('settings[filter_block_width]', array(
						'value' => isset($this->settings['settings']['filter_block_width']) ? $this->settings['settings']['filter_block_width'] : '100',
						'attrs' => 'class="wpfSmallInput"'));
						echo htmlWpf::selectbox('settings[filter_block_width_in]', array(
						'options' => array('%' => '%', 'px' => 'px'),
						'value' => (isset($this->settings['settings']['filter_block_width_in']) ? $this->settings['settings']['filter_block_width_in'] : '%'),
					))
					?>
					</div>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Select Filter Buttons Position', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select the position of filter buttons on the page.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[main_buttons_position]', array(
						'options' => array('top' => 'Top', 'bottom' => 'Bottom', 'both' => 'Both'),
						'value' => (isset($this->settings['settings']['main_buttons_position']) ? $this->settings['settings']['main_buttons_position'] : 'bottom'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Enable filter icon on load', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Enable filter icon while page is loading.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[filter_loader_icon_onload_enable]', array(
						'checked' => (isset($this->settings['settings']['filter_loader_icon_onload_enable']) ? (int) $this->settings['settings']['filter_loader_icon_onload_enable'] : '')
					))?>
				</td>
			</tr>
			<?php
			$colorPreview = (isset($this->settings['settings']['filter_loader_icon_color']) ? $this->settings['settings']['filter_loader_icon_color'] : 'black');
			$iconName = (isset($this->settings['settings']['filter_loader_icon_name']) ? $this->settings['settings']['filter_loader_icon_name'] : 'default');
			$iconNumber = (isset($this->settings['settings']['filter_loader_icon_number']) ? $this->settings['settings']['filter_loader_icon_number'] : '0');

			if($iconName === 'default'){
				$htmlPreview = '<div class="supsystic-filter-loader spinner" style="background-color:'.$colorPreview.'"></div>';
			}else{
				$htmlPreview = '<div class="supsystic-filter-loader la-'.$iconName.' la-2x" style="color: '.$colorPreview.'">';
				for($i = 1; $i <= $iconNumber; $i++){
					$htmlPreview .= '<div></div>';
				}
				$htmlPreview .= '</div>';
			}

			?>
			<tr class="col-md-12 wpfLoader">
				<td class="col-md-5">
					<?php _e('Filter Loader Icon', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select the animated loader, which appears when filtering results are loading.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<div class="button chooseLoaderIcon"><?php _e('Choose Icon', WPF_LANG_CODE)?></div>
					<div class="wpfIconPreview"><?php echo $htmlPreview; ?></div>
					<?php echo htmlWpf::hidden('settings[filter_loader_icon_name]', array(
						'value' => (isset($this->settings['settings']['filter_loader_icon_name']) ? $this->settings['settings']['filter_loader_icon_name'] : 'default')
					))?>
					<?php echo htmlWpf::hidden('settings[filter_loader_icon_number]', array(
						'value' => (isset($this->settings['settings']['filter_loader_icon_number']) ? $this->settings['settings']['filter_loader_icon_number'] : '0')
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfLoader wpfColorObserver">
				<td class="col-md-5">
					<?php _e('Filter Loader Color', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select the color of filter loader animation.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::colorpicker('settings[filter_loader_icon_color]', array(
						'value' => (isset($this->settings['settings']['filter_loader_icon_color']) ? $this->settings['settings']['filter_loader_icon_color'] : 'black'),
						'attrs' => 'style="width: 50px"',
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Enable overlay', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Enable overlay.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[enable_overlay]', array(
						'checked' => (isset($this->settings['settings']['enable_overlay']) ? (int) $this->settings['settings']['enable_overlay'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Show Loader Icon on overlay', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Enable filter icon while filtering process ongoing.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[enable_overlay_icon]', array(
						'checked' => (isset($this->settings['settings']['enable_overlay_icon']) ? (int) $this->settings['settings']['enable_overlay_icon'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Show loading word on overlay', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Show search word on overlay', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[enable_overlay_word]', array(
						'checked' => (isset($this->settings['settings']['enable_overlay_word']) ? (int) $this->settings['settings']['enable_overlay_word'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Set loading word for overlay', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select overlay skin for filter', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::text('settings[overlay_word]', array(
						'value' => (isset($this->settings['settings']['overlay_word']) ? $this->settings['settings']['overlay_word'] : ''),
					))?>
				</td>
			</tr>
			<tr class="col-md-12">
				<td class="col-md-5">
					<?php _e('Chose price filter skin', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Here you may select the price filter skin', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<div class="button choosePriceFilterSkin"><?php _e('Choose price filter skin', WPF_LANG_CODE)?></div>
					<?php echo htmlWpf::hidden('settings[price_filter_skin]', array(
						'value' => (isset($this->settings['settings']['price_filter_skin']) ? $this->settings['settings']['price_filter_skin'] : 'default')
					))?>
				</td>
			</tr>


			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Filter style', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Full screen filter - is located over products and occupies the entire width of the screen, with the ability to collapse / expand.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[filter_position]', array(
						'options' => array('left_sidebar' => 'Left Sidebar', 'right_sidebar' => 'Right Sidebar', 'full_screen' => 'Full screen filter'),
						'value' => (isset($this->settings['settings']['filter_position']) ? $this->settings['settings']['filter_position'] : 'left_sidebar'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Max Columns', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('For Full screen filter mode - how many columns will be displayed', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::text('settings[max_columns]', array(
						'value' => (isset($this->settings['settings']['max_columns']) ? $this->settings['settings']['max_columns'] : '4'),
						'attrs' => 'placeholder="4"'
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Max Height in px', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('The maximum height for each of the filters, if the height is greater than display scroll ', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::text('settings[max_columns_height]', array(
						'value' => (isset($this->settings['settings']['max_columns_height']) ? $this->settings['settings']['max_columns_height'] : ''),
						'attrs' => 'placeholder=""'
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Max Height in px (Full screen mode)', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('The maximum height container for Full screen mode filter ', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::text('settings[max_full_screen_height]', array(
						'value' => (isset($this->settings['settings']['max_full_screen_height']) ? $this->settings['settings']['max_full_screen_height'] : ''),
						'attrs' => 'placeholder=""'
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Custom scroll bar', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Enable beautiful scrollbar.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::checkbox('settings[enable_beauty_scrollbar]', array(
						'checked' => (isset($this->settings['settings']['enable_beauty_scrollbar']) ? (int) $this->settings['settings']['enable_beauty_scrollbar'] : '')
					))?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Select checkbox style', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Select checkbox style in frontend.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[checkbox_style]', array(
						'options' => array('checkbox' => 'Checkbox', 'square' => 'Square'),
						'value' => (isset($this->settings['settings']['checkbox_style']) ? $this->settings['settings']['checkbox_style'] : 'checkbox'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Select Hierarchy Style', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Select hierarchy style in frontend.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[hierarchy_style]', array(
						'options' => array('arrow' => 'Arrow', 'line' => 'Line', 'circle' => 'Circle'),
						'value' => (isset($this->settings['settings']['hierarchy_style']) ? $this->settings['settings']['hierarchy_style'] : 'line'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Select Mobile Filter Position', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Select mobile filter location.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::selectbox('settings[mobile_filter_position]', array(
						'options' => array('left' => 'Left', 'right' => 'Right'),
						'value' => (isset($this->settings['settings']['mobile_filter_position']) ? $this->settings['settings']['mobile_filter_position'] : 'left'),
					))
					?>
				</td>
			</tr>
			<tr class="col-md-12 wpfHidden">
				<td class="col-md-5">
					<?php _e('Set mobile resolution px', WPF_LANG_CODE)?>
				</td>
				<td class="col-md-2"><i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('The width of the screen at which the mobile filter is turned on.', WPF_LANG_CODE))?>"></i></td>
				<td class="col-md-5">
					<?php echo htmlWpf::text('settings[mobile_resolution]', array(
						'value' => (isset($this->settings['settings']['max_mobile_resolution']) ? $this->settings['settings']['max_mobile_resolution'] : ''),
						'attrs' => 'placeholder=""'
					))?>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="wpfLoaderIconTemplate wpfHidden">
			<?php
				$loaderSkins = array(
					'timer' => 1, //number means count of div necessary to display loader
					'ball-beat'=> 3,
					'ball-circus'=> 5,
					'ball-atom'=> 4,
					'ball-spin-clockwise-fade-rotating'=> 8,
					'line-scale'=> 5,
					'ball-climbing-dot'=> 4,
					'square-jelly-box'=> 2,
					'ball-rotate'=> 1,
					'ball-clip-rotate-multiple'=> 2,
					'cube-transition'=> 2,
					'square-loader'=> 1,
					'ball-8bits'=> 16,
					'ball-newton-cradle'=> 4,
					'ball-pulse-rise'=> 5,
					'triangle-skew-spin'=> 1,
					'fire'=> 3,
					'ball-zig-zag-deflect'=> 2
				);
				?>
				<div class="items items-list">
					<div class="item">
						<div class="item-inner">
							<div class="item-loader-container">
								<div class="preicon_img" data-name="default" data-items="0">
									<div class="supsystic-filter-loader spinner" style="background-color: black;"></div>
								</div>
							</div>
						</div>
						<div class="item-title">default</div>
					</div>
					<?php
						foreach ($loaderSkins as $name=>$number) {
							?>
							<div class="item">
								<div class="item-inner">
									<div class="item-loader-container">
										<div class="supsystic-filter-loader la-<?php echo $name; ?> la-2x preicon_img" data-name="<?php echo $name; ?>" data-items="<?php echo $number; ?>" style="color: black;">
											<?php
											for($i=0;$i<$number;$i++){
												echo '<div></div>';
											}
											?>

										</div>
									</div>
								</div>
								<div class="item-title"><?php echo $name; ?></div>
							</div>
					<?php }	?>
				</div>
		</div>
	</div>
</div>



