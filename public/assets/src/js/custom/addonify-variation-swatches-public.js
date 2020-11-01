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


		// ----------------------------------------------------
		// add to cart feature in shop page 
		// ----------------------------------------------------

		// disable default button click
		$( document ).on( 'click', '.single_add_to_cart_button', function(e) {
			e.preventDefault();
		});


		$thisbutton = $(this),
		$form = $thisbutton.closest('form.cart'),
		id = $thisbutton.val(),
		product_qty = $form.find('input[name=quantity]').val() || 1,
		product_id = $form.find('input[name=product_id]').val() || id,
		variation_id = $form.find('input[name=variation_id]').val() || 0;

		var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

		$(document.body).trigger('adding_to_cart', [$thisbutton, data]);


		$.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {

                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });


		

		// end add to cart features ----------------------


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