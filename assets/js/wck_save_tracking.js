/**
 * JS File for this plugin.
 *
 * @package for js fle.
 * js file for wck save tracking.
 */

jQuery(
	function( $ ) {

		var wck_woocommerce_tracking_items = {

			  // init Class.
			init: function() {
				$( '#woocommerce-wck' )
				.on( 'click', 'a.wck-delete-tracking', this.delete_tracking )
				.on( 'click', 'button.kargomnerede-save', this.save_form );
			},

			// When a user enters a new tracking item.
			save_form: function () {
				var error;
				var tracking_number   = jQuery( "#wck_tracking_number" );
				var tracking_provider = jQuery( "#wck_carrier_list" );
				if ( tracking_number.val() === '' ) {
					 tracking_number.css( "border","1px solid red" );
					 error = true;
				} else {
					var pattern = /^[0-9a-zA-Z \b]+$/;
					if ( ! pattern.test( tracking_number.val() )) {
						tracking_number.css( "border","1px solid red" );
								 error = true;
					} else {
						tracking_number.css( "border","1px solid #ddd" );
					}
				}
				if ( tracking_provider.val() === '' ) {
					jQuery( "#wck_carrier_list" ).siblings( '.select2-container' ).find( '.select2-selection' ).css( 'border-color','red' );
					error = true;
				} else {
					jQuery( "#wck_carrier_list" ).siblings( '.select2-container' ).find( '.select2-selection' ).css( 'border-color','#ddd' );
				}
				if (error == true) {
					return false;
				}
				if ( ! $( 'input#wck_tracking_number' ).val() ) {
					return false;
				}

				var data = {
					action: 'wck_tracking_save_form',
					order_id: woocommerce_admin_meta_boxes.post_id,
					tracking_provider: $( '#wck_carrier_list' ).val(),
					tracking_provider_name: $( '#wck_tracking_provider_name' ).val(),
					tracking_number: $( 'input#wck_tracking_number' ).val()
				};

				$.post(
					woocommerce_admin_meta_boxes.ajax_url,
					data,
					function( response ) {
								jQuery( "#post" ).submit();
					}
				);

				return false;
			},

			// Delete a tracking item.
			delete_tracking: function() {

				var tracking_number = $( this ).attr( 'rel' );

				var data = {
					action:      'wck_tracking_delete_item',
					order_id:    woocommerce_admin_meta_boxes.post_id,
					tracking_number: tracking_number
				};

				$.post(
					woocommerce_admin_meta_boxes.ajax_url,
					data,
					function( response ) {
						console.log( response );
						$( '#tracking-item-' + tracking_number ).unblock();
						if ( response != '-1' ) {
							$( '#tracking-item-' + tracking_number ).remove();
						}

					}
				);

				return false;
			}
		}

		wck_woocommerce_tracking_items.init();

		set_yqtrack_tracking_provider();
		jQuery( '#wck_carrier_list' ).trigger( 'change' );
		var providers;
		function set_yqtrack_tracking_provider() {
			jQuery( '#wck_carrier_list' ).on(
				'change',
				function () {
					var key = jQuery( this ).find( ':selected' ).data( 'option-name' );
					if (key) {
						 jQuery( '#wck_tracking_provider_name' ).val( key );
					} else {
						jQuery( '#wck_tracking_provider_name' ).val( '' );
					}
				}
			);
		}

	}
);
