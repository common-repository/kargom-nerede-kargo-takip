/**
 * JS File for this plugin.
 *
 * @package for js fle.
 * js file for wck save tracking.
 */

jQuery( document ).ready(
	function(){

		var wck_kn_tracking_items = {

			// init Class.
			init: function() {
				console.log( wck_ajax_obj );
				jQuery( '#wck_track' ).on( 'click', '.wck_tracking_with_number', this.tracking_with_tracking_number );
				jQuery( '#wck_track' ).on( 'click', '.wck_tracking_with_order', this.tracking_with_order_number );

				jQuery( '#wck_track' ).on(
					'keyup',
					'input',
					function(){
						if (jQuery( this ).val()) {
							jQuery( this ).css( "border","1px solid #ddd" );
						}

					}
				);
			},
			tracking_with_tracking_number:function(){
				var error;
				var tracking_number = jQuery( "#wckTrackNumber" );
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

				if (error == true) {
					return false;
				}
				if ( ! jQuery( 'input#wckTrackNumber' ).val() ) {
					return false;
				}

				var data = {
					action: 'wck_ajax_req',
					ajax_handler:'wck_tracking_package_with_number',
					tracking_number: jQuery( 'input#wckTrackNumber' ).val(),
					nonce:wck_ajax_obj.nonce
				};

				jQuery( '#track_with_tracking_number' ).submit();

			},
			tracking_with_order_number:function(){
				console.log( 'submit' );

				var error;
				var order_number = jQuery( "#OrderNumber" );
				var order_email  = jQuery( "#order_email" );

				if ( order_number.val() === '' ) {
					order_number.css( "border","1px solid red" );
					error = true;
				} else {
					var pattern = /^[0-9a-zA-Z \b]+$/;
					if ( ! pattern.test( order_number.val() )) {
						order_number.css( "border","1px solid red" );
						error = true;
					} else {
						order_number.css( "border","1px solid #ddd" );
					}
				}
				if ( order_email.val() === '' ) {
					order_email.css( "border","1px solid red" );
					error = true;
				}

				if (error == true) {
					return false;
				}
				if ( ! jQuery( 'input#OrderNumber' ).val() ) {
					return false;
				}
				if ( ! jQuery( 'input#order_email' ).val() ) {
					return false;
				}
				jQuery( '#track_with_email_order' ).submit();

			},
		}

		wck_kn_tracking_items.init();

	}
);
