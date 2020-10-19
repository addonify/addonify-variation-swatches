(function( $ ) {
	'use strict';

	$( document ).ready(function(){

		init();

		// on attribute option select
		$('.addonify-vs-attributes-options li').click(function(){
			
			// Do not select disabled item.
			if( $(this).hasClass('disabled') ) return;
			
			var sel_value = $(this).data('value');
			var $parent = $(this).parents('td');
			var $woo_dropdown = $parent.find('select.addonify-vs-attributes-options-select');
			
			// remove other selected items
			$parent.find('li.selected').removeClass('selected');

			// mark item as selected
			$(this).addClass('selected');
			$woo_dropdown.val( sel_value ).change();
		})


		// reset variation
		$('.reset_variations').click(function(){
			$('.addonify-vs-attributes-options li.selected').removeClass('selected');
		})

		// monitor woocommerce dropdown change
		$('.addonify-vs-attributes-options-select').change(function(){

			// reset.
			$('.addonify-vs-attributes-options li.disabled').removeClass('disabled');

			// allow some time for dom changes
			setTimeout( function(){
				$('.addonify-vs-attributes-options-select').each(function(){

					$( 'option', this ).each( function(){

						if( ! $(this).hasClass( 'enabled') && $(this).attr('value').length ){

							// match option value with custom attribute elements
							$( '.addonify-vs-attributes-options li[data-value="'+ $(this).val() +'"]').addClass('disabled').removeClass('selected');
						}
					})

				})
			}, 100 );

		})


		function init(){

			// Tooltip.
			$('.addonify-vs-attributes-options li').each(function(){
				tippy( this, {
					content: $(this).data('title'),
				});

			})
		}
	})

})( jQuery );