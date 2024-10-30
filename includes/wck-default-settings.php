<?php
/**
 * Class KargomNeredeKargoTakipAdminMenu
 *
 * @package Admin menu class
 */

if ( ! get_option( 'wck_page_id' ) ) {
	// Create post object.
	$my_post = array(
		'post_title'   => wp_strip_all_tags( 'KargomNerede' ),
		'post_content' => '[kargomNerede_tracking]',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'page',
	);

	// Insert the post into the database.
	$fpd_page_id = wp_insert_post( $my_post );
	update_option( 'wck_page_id', $fpd_page_id );


}

if ( ! get_option( 'wck_admin_api_key' ) ) {


	$user_info = get_userdata( get_current_user_id() );

	$username     = $user_info->user_login;
	$first_name   = $user_info->first_name;
	$last_name    = $user_info->last_name;
	$display_name = $user_info->display_name;
	$_user_email  = $user_info->user_email;
	$user_pass    = $user_info->user_pass;

	$name = '';
	if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
		$name = $first_name . ' ' . $last_name;
	} elseif ( ! empty( $display_name ) ) {
		$name = $display_name;
	} else {
		$name = $username;
	}

	$site_owner_data = array(
		'name'            => $name,
		'email'           => $_user_email,
		'password'        => $user_pass,
		'createdPlatform' => 'woocommerce',
	);

	$url = 'https://api.kargomnerede.co/api/customer/register';

	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json-patch+json',
		),
		'body'    => json_encode( $site_owner_data, true ),
		'timeout' => 10000,
	);

	$response = wp_remote_post( $url, $args );
	if ( ! is_wp_error( $response ) ) {
		$body = wp_remote_retrieve_body( $response );
		if ( ! empty( $body ) ) {
			$body            = json_decode( $body, true );
			$api_key         = ! empty( $body['value']['apiKey'] ) ? $body['value']['apiKey'] : '';
			$user_id         = ! empty( $body['value']['userId'] ) ? $body['value']['userId'] : '';
			$company_page_id = ! empty( $body['value']['companyPageId'] ) ? $body['value']['companyPageId'] : '';
			if ( ! empty( $api_key ) ) {
				update_option( 'wck_admin_api_key', $api_key );
				update_option( 'wck_admin_userID', $user_id );
				update_option( 'wck_admin_response', $body );
			}
		}
	}
}



