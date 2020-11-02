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

			// allow some time for dom changes
			setTimeout( function(){
				$('.addonify-vs-attributes-options-select').each(function(){

					var $variation_options = $(this).siblings( '.addonify-vs-attributes-options').first();

					// mark all variation as disabled by default.
					$variation_options.find('li').addClass('disabled');

					$( 'option', this ).each( function(){

						if( $(this).attr('value').length ){

							// match option value with custom attribute elements
							$( '.addonify-vs-attributes-options li[data-value="'+ $(this).val() +'"]').show().removeClass('disabled');
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