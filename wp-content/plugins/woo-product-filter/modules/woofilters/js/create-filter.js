(function($) {

	$(document).ready(function () {

		jQuery('a[href$="admin.php?page=wpf-filters&tab=woofilters#wpfadd"]').attr('href', '#wpfadd');

			if( jQuery('#wpfAddDialog').length ) {
			var $createBtn = jQuery('.create-table'),
				$error = jQuery('#formError'),
				$input = jQuery('#addDialog_title'),
				$dialog = jQuery('#wpfAddDialog').dialog({
					width: 480,
					modal: true,
					autoOpen: false,
					open: function () {
						jQuery('#wpfAddDialog').keypress(function(e) {
							if (e.keyCode == $.ui.keyCode.ENTER) {
								e.preventDefault();
								jQuery('.wpfDialogSave').click();
							}
						});
					},
					close: function () {
						window.location.hash = '';
					},
					buttons: {
						Save: function (event) {
							$error.fadeOut();

							jQuery.sendFormWpf({
								data: {
									mod: 'woofilters',
									action: 'save',
									title: $input.val()
								},
								onSuccess: function(res) {
									if(!res.error) {
										var currentUrl = window.location.href;
										if (res.data.edit_link && currentUrl !== res.data.edit_link) {
											toeRedirect(res.data.edit_link);
										}
									}else{
										$error.find('p').text(res.errors.title);
										$error.fadeIn();
									}
								}
							});
						}
					},
					create:function () {
						jQuery(this).closest(".ui-dialog").find(".ui-dialog-buttonset button").first().addClass("wpfDialogSave");
					}
				});

			$input.on('focus', function () {
				$error.fadeOut();
			});

			$createBtn.on('click', function () {
				$dialog.dialog('open');
			});
		}

		jQuery(window).on('hashchange', function () {
			if (window.location.hash === '#wpfadd') {
				// To prevent error if data not loaded completely
				setTimeout(function() {
					if(typeof $dialog !== 'undefined'){
						$dialog.dialog('open');
					}
				}, 500);
			}
		}).trigger('hashchange');

		jQuery('#toplevel_page_wpf-filters .wp-submenu-wrap li:has(a[href$="admin.php?page=wpf-filters"])').on('click', function(e){
			e.preventDefault();
			showAddDialog();
		});

		jQuery(document.body).off('click', '.supsystic-navigation li:has(a[href$="admin.php?page=wpf-filters&tab=woofilters#wpfadd"])');
		jQuery(document.body).on('click', '.supsystic-navigation li:has(a[href$="admin.php?page=wpf-filters&tab=woofilters#wpfadd"])', function(e){
			e.preventDefault();
			showAddDialog();
		});

		function showAddDialog(){
			setTimeout(function() {
				$dialog.dialog('open');
			}, 500);
		}
	});
})(jQuery);