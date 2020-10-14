(function( $ ) {
	'use strict';

	$(document).ready(function(){

		// ios style switch
		if( $('input.lc_switch').length ){
			$('input.lc_switch').lc_switch();
		}


		// wp media uploader
		var wp_media_obj;
 
		$('.addonify-vs_select_image_button').click(function(e) {
			e.preventDefault();

			// If the upload object has already been created, reopen the dialog
			if (wp_media_obj) {
				wp_media_obj.open();
				return;
			}

			// Extend the wp.media object
			wp_media_obj = wp.media.frames.file_frame = wp.media(
				{
					title: 'Select an Image',
					button: {
						text: 'Use Image'
					},
					library: {
						type: [ 'image' ]
					},
					multiple: false 
				}
			);
		
			// When a file is selected, grab the URL and set it as the text field's value
			wp_media_obj.on('select', function() {
				var attachment = wp_media_obj.state().get('selection').first().toJSON();
				$('#addonify-vs-term-image-id').val(attachment.id);
				$('.image-preview img').attr('src', attachment.url);

				$('.addonify-vs_remove_image_button').show();
			});

			// Open the upload dialog
			wp_media_obj.open();

		});

		$('.addonify-vs_remove_image_button').click(function(){
			$('#addonify-vs-term-image-url').val('');
			
			var placeholder_img = $( '.image-preview img').data('placeholder');
			$('.image-preview img').attr('src', placeholder_img);
		})

		// end wp media uploader


		// var $require_login_btn = $('#addonify_wishlist_require_login');
		// var $redirect_to_login_btn = $('#addonify_wishlist_redirect_to_login');

		// auto diable "redirect to login" if "require login" is disabled
		// $('body').delegate( '#addonify_wishlist_require_login', 'lcs-off', function() {
		// 	$redirect_to_login_btn.lcs_off();
		// });

		// $('body').delegate( '#addonify_wishlist_redirect_to_login', 'lcs-on', function() {

		// 	if( ! $require_login_btn.is(':checked') ){
		// 		setTimeout( function(){
		// 			$redirect_to_login_btn.lcs_off();
		// 		}, 400 );
		// 	}


		// });


		// initiate wp color picker

		if( $('.color-picker').length ){
			$('.color-picker').wpColorPicker();
		}


		// code editor
		// if( $('#addonify_wishlist_custom_css').length ) {
		// 	var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
		// 	editorSettings.codemirror = _.extend(
		// 		{},
		// 		editorSettings.codemirror,
		// 		{
		// 			indentUnit: 2,
		// 			tabSize: 2
		// 		}
		// 	);
		// 	var editor = wp.codeEditor.initialize( $('#addonify_wishlist_custom_css'), editorSettings );
		// }



		// show hide content colors ------------------------------

		// let $style_options_sel = $('#addonify_wishlist_load_styles_from_plugin');
		// let $content_colors_sel = $('#addonify-content-colors-container');

		// // self activate
		// show_hide_content_colors();

		// // detect state change
		// $('body').delegate('#addonify_wishlist_load_styles_from_plugin', 'lcs-statuschange', function() {
		// 	show_hide_content_colors();
		// });

		
		// function show_hide_content_colors(){

		// 	let state = $style_options_sel.is(":checked") 

		// 	if( state ){
		// 		$content_colors_sel.slideDown();
		// 	}
		// 	else{
		// 		$content_colors_sel.slideUp();
		// 	}
		// }
	
	})

})( jQuery );