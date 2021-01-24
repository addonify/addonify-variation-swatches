(function( $ ) {
	'use strict';

	$( document ).ready(function(){

		var default_product_images = {};

		init();

		// on attribute option select
		$( '.addonify-vs-attributes-options li' ).click(function(){
			
			// Do not select disabled item.
			if ( $(this).hasClass( 'disabled' ) ) return;
			
			var sel_value = $(this).data( 'value' );
			var $parent = $(this).parents( 'td' );
			var $woo_dropdown = $parent.find( 'select.addonify-vs-attributes-options-select' );
			
			// remove other selected items
			$parent.find( 'li.selected' ).removeClass( 'selected' );

			// mark item as selected
			$(this).addClass( 'selected' );
			$woo_dropdown.val( sel_value ).change();
		})


		// reset variation
		$( '.reset_variations' ).click(function(){
			$( '.addonify-vs-attributes-options li.selected' ).removeClass( 'selected' );
		})

		// monitor woocommerce dropdown change
		$( '.addonify-vs-attributes-options-select' ).change(function(){

			// allow some time for dom changes
			setTimeout( function(){
				$( '.addonify-vs-attributes-options-select' ).each(function(){

					var $variation_options = $(this).siblings( '.addonify-vs-attributes-options' ).first();

					// mark all variation as disabled by default.
					$variation_options.find( 'li:not(.addonify-vs-item-more)' ).addClass( 'disabled' );

					$( 'option', this ).each( function(){

						if ( $(this).attr( 'value' ).length ){

							// match option value with custom attribute elements
							$( '.addonify-vs-attributes-options li[data-value="'+ $(this).val() +'"]' ).show().removeClass( 'disabled' );
						}
					})

				})

			}, 100 );

			// show hide add to card buttons, if variation selection changes
			toggle_add_to_cart_buttons_in_archives( this );

			// change thumnbnail of product on variation selection
			attributes_selection_change( this );

		})


		// reset variation click
		// revert thumbnail into original state
		$( '.reset_variations' ).click(function(){

			// continue only if archive page
			if ( ! $( 'body' ).hasClass( 'archive' ) ) return;

			var $parent = $( this ).parents( 'li.product' );
			var product_id = $parent.find( 'form.variations_form' ).data( 'product_id' );
			var $product_image_sel = $parent.find( 'img.attachment-woocommerce_thumbnail' );

			// if key  exists in object
			if ( product_id in default_product_images ) {
				$product_image_sel.attr( 'srcset', default_product_images[ product_id ] );
			}
		})


		function init(){

			if ( addonify_vs_object.enable_tooltip == 1 ) {

				// Tooltip.
				$( '.addonify-vs-attributes-options li[data-title]' ).each(function(){
					tippy( this, {
						content: $(this).data( 'title' ),
					});

				})
			}

		}

		// toggle between "add to cart" button and "select options" button if attributes is selected
		function toggle_add_to_cart_buttons_in_archives( sel ){

			// continue only if is shop page or archive page
			if ( ! parseInt( addonify_vs_object.is_shop_page ) ) return;

			// continue if, "show_single_attribute" is not enabled
			if ( parseInt( addonify_vs_object.show_single_attribute ) ) return;

			var $parent = $(sel).parents( 'table.variations' );

			// if all options are checked 
			// on archive page

			if ( $parent.find( 'ul.addonify-vs-attributes-options' ).length == $parent.find( 'ul.addonify-vs-attributes-options li.selected' ).length ){
				$( '.product_type_variable.add_to_cart_button' ).hide();
				$( '.addonify_vs-add_to_cart-button' ).show();
			}
			else{
				$( '.product_type_variable.add_to_cart_button' ).show();
				$( '.addonify_vs-add_to_cart-button' ).hide();
			}

		}


		function attributes_selection_change( this_sel ) {

			// continue only if archive page
			if ( ! $( 'body' ).hasClass( 'archive' ) ) return;

			let $parent = $( this_sel ).parents( 'li.product' );
			
			let attr_names = [];
			let attr_values = [];
			$parent.find( 'select.addonify-vs-attributes-options-select' ).each( function( index, value ){
				attr_names.push( $(value).attr('name') );
				attr_values.push( $(value).val() );
			})

			change_featured_image_on_attributes_selection( $parent, attr_values );

			// get variation id from selected variations and append it into "add to cart" button.
			update_add_to_cart_btn_property( $parent, attr_names, attr_values );


		}

		function change_featured_image_on_attributes_selection( $parent, attr_values ){
			// get default_product_image
			let $product_image_sel = $parent.find( 'img.attachment-woocommerce_thumbnail' );
			let $variation_form = $parent.find( 'form.variations_form' );

			let product_id = $variation_form.data( 'product_id' );
			
			// if key does not exists in object
			if ( ! ( product_id in default_product_images ) ) {
				default_product_images[ product_id ] = $product_image_sel.attr( 'srcset' );
			}

			var variation_thumb = get_variation_thumbnail( product_id, attr_values );

			if ( variation_thumb ){
				$product_image_sel.attr( 'srcset', variation_thumb);
			}

		}

		
		function get_variation_thumbnail( product_id, selected_attributes ){

			// continue only if archive page
			if ( ! $( 'body' ).hasClass( 'archive' ) ) return;

			let variation_data = $( 'form.variations_form[data-product_id="' + product_id + '"]' ).data( 'product_variations' );
			if ( ! variation_data.length ) return;

			let return_data = false;

			$.each( variation_data, function(key,value) {

				if ( value.attributes !== undefined ){

					let i = 0;
					let array_matched = true;

					// check if all selected attributes matches with variations.
					$.each( value.attributes, function( key1, value1 ){

						if ( value1 != '' && value1 != selected_attributes[i] ) {
							array_matched = false;
						}
						
						i++;
					})

					if ( array_matched ){
						return_data = value.image.srcset;
						return false;
					}

				}
 
			});

			return return_data;
		}



		// get variation id from selection variation options.
		function update_add_to_cart_btn_property( $parent, attr_names, attr_values ) {

			// continue only if archive page
			if ( ! $( 'body' ).hasClass( 'archive' ) ) return;

			let $variation_form = $parent.find( 'form.variations_form' );
			if ( ! $variation_form ) return;

			let product_id = $variation_form.data( 'product_id' );
			if ( ! product_id ) return;

			let variation_data = $( 'form.variations_form[data-product_id="' + product_id + '"]' ).data( 'product_variations' );
			if ( ! variation_data.length ) return;

			$.each( variation_data, function( key, value ) {

				if ( value.attributes !== undefined ) {

					let cond_passed = 0;

					// check if all selected attributes matches with variations.
					$.each( attr_values, function( index, attr_val ) {

						cond_passed++;

						if ( attr_val != value.attributes[ attr_names[index] ] ) {
							cond_passed = 0;
						}

						if ( cond_passed === attr_values.length ) {
							return false;
						}

					})

					if ( cond_passed === attr_values.length ) {
						let new_url = addonify_vs_object.shop_page_url + '?add-to-cart=' + value.variation_id;
						$( '.addonify_vs-add_to_cart-button[data-product_id="' + product_id + '"]' ).attr( 'data-product_id', value.variation_id ).attr('href', new_url );
						return false;
					}

				}
 
			});

		}

	})

})( jQuery );