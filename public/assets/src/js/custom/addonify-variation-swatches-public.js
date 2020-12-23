(function( $ ) {
	'use strict';

	$( document ).ready(function(){

		var variation_data = {};
		var default_product_images = {};

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

			// show hide add to card buttons, if variation selection changes
			toggle_add_to_cart_buttons_in_archives( this );

			// change thumnbnail of product on variation selection
			change_variation_thumbnail_in_shop( this );

		})


		// reset variation click
		// revert thumbnail into original state
		$('.reset_variations').click(function(){

			// continue only if archive page
			if( ! $('body').hasClass('archive') ) return;

			var $parent = $( this ).parents( 'li.product' );
			var product_id = $parent.find('form.variations_form').data('product_id');
			var $product_image_sel = $parent.find('img.attachment-woocommerce_thumbnail');

			// if key  exists in object
			if ( product_id in default_product_images ) {
				$product_image_sel.attr('srcset', default_product_images[ product_id ] );
			}
		})


		function init(){

			if( addonify_vs_object.enable_tooltip == 1 ) {

				// Tooltip.
				$('.addonify-vs-attributes-options li[data-title]').each(function(){
					tippy( this, {
						content: $(this).data('title'),
					});

				})
			}

		}

		// toggle between "add to cart" button and "select options" button if attributes is selected
		function toggle_add_to_cart_buttons_in_archives( sel ){


			// continue only if is shop page or archive page
			if( ! parseInt( addonify_vs_object.is_shop_page ) ) return;

			// continue if, "show_single_attribute" is not enabled
			if( parseInt( addonify_vs_object.show_single_attribute ) ) return;

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


		function change_variation_thumbnail_in_shop( this_sel ){

			// continue only if archive page
			if( ! $('body').hasClass('archive') ) return;

			var $parent = $( this_sel ).parents( 'li.product' );

			// get default_product_image
			var $product_image_sel = $parent.find('img.attachment-woocommerce_thumbnail');
			var $variation_form = $parent.find('form.variations_form');

			var product_id = $variation_form.data('product_id');
			
			// if key does not exists in object
			if ( ! ( product_id in default_product_images ) ) {
				default_product_images[ product_id ] = $product_image_sel.attr('srcset');
			}

			var attr_key = [];
			$parent.find('select.addonify-vs-attributes-options-select').each( function( index, value ){
				attr_key.push( $(value).val() );
			})

			var variation_thumb = get_variation_thumbnail( attr_key );

			if( variation_thumb ){
				$product_image_sel.attr('srcset', variation_thumb);
			}
			

		}

		
		function get_variation_thumbnail( selected_attributes ){

			// continue only if archive page
			if( ! $('body').hasClass('archive') ) return;
			
			if( ! variation_data.length ) {
				variation_data = $('body.archive form.variations_form').data('product_variations');
			}

			var return_data = false;

			$.each( variation_data, function(key,value) {

				if( value.attributes !== undefined ){

					var i = 0;
					var array_matched = true;

					// check if all selected attributes matches with variations.
					$.each( value.attributes, function( key1, value1 ){

						if( value1 != '' && value1 != selected_attributes[i] ) {
							array_matched = false;
						}
						
						i++;
					})

					if( array_matched ){
						return_data = value.image.srcset;
						return false;
					}

				}
 
			});

			return return_data;
		}

	})

})( jQuery );