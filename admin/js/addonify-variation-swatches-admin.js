(function( $ ) {
	'use strict';

	$(document).ready(function(){

		// ios style switch
		if( $('input.lc_switch').length ){
			$('input.lc_switch').lc_switch();
		}


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