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
					$variation_options.find('li:not(.addonify-vs-item-more)' ).addClass('disabled');

					$( 'option', this ).each( function(){

						if( $(this).attr('value').length ){

							// match option value with custom attribute elements
							$( '.addonify-vs-attributes-options li[data-value="'+ $(this).val() +'"]').show().removeClass('disabled');
						}
					})

				})

			}, 100 );

			toggle_add_to_cart_buttons_in_archives( this );

		})


		function init(){

			// Tooltip.
			$('.addonify-vs-attributes-options li[data-title]').each(function(){
				tippy( this, {
					content: $(this).data('title'),
				});

			})

		}


		function toggle_add_to_cart_buttons_in_archives( sel ){

			if( ! $('body').hasClass('archive') ) return;

			var $parent = $(sel).parents('table.variations');

			// if all options are checked 
			// on archive page

			if ( $parent.find('ul.addonify-vs-attributes-options').length == $parent.find('ul.addonify-vs-attributes-options li.selected').length ){
				$('.product_type_variable.add_to_cart_button').hide();
				$('.addonify_vs-add_to_cart-button').show();
			}
			else{
				$('.product_type_variable.add_to_cart_button').show();
				$('.addonify_vs-add_to_cart-button').hide();
			}

		}

	})

})( jQuery );