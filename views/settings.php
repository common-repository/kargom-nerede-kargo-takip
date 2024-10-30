<?php
/**
 * View Settings
 *
 * @package for Admin settings
 */

$api_key         = get_option( 'wck_admin_api_key' );
$user_id         = get_option( 'wck_admin_userID' );
$company_page_id = get_option( 'wck_admin_companyPageId' );
$body            = get_option( 'wck_admin_response' );

$user_info = get_userdata( get_current_user_id() );
$user_pass = $user_info->user_pass;
?>
<style>
	.submit-button-plugin {
	display: none;
}
</style>
<?php


$form_fields          = '';
$formname             = 'wpupb_settings';
$plugin_settings_form = new KargomNeredeKargoTakipFields( $formname );
$plugin_settings      = maybe_unserialize( get_option( 'wck_options' ) );

$form_fields       .= $plugin_settings_form->wpub_wp_group(
	array(
		'label'         => esc_html__( 'Kargom Nerede - Markalı Kargo Takip Sayfası', 'automate_hub' ),
		'wrapper_class' => 'form-row',
	)
);
$fpd_page_id        = get_option( 'wck_page_id' );
$tracking_page_link = get_page_link( $fpd_page_id );



$form_fields .= $plugin_settings_form->wpub_wp_html(
	array(
		'name'          => 'description_html',
		'id'            => 'description_html',
		'label'         => 'Name',
		'is_full_width' => true,
		'wrapper_class' => 'form-row',
		'html'          => '
            <div class="instruction">
            <h3> <b>Kargom Nerede - Markalı Kargo Takip Sayfası</b></h3>
            <p> <b>WooCommerce > Siparişleriniz</b> içerisinden "Kargom Nerede" bileşenine kargo bilgilerini (takip numarası ve kargo firması) girerek. <br>
            Müşterilerinize daha iyi bir sipariş takip deneyimi sunabilirsiniz. <br>
            Kargo takip sayfanız otomatik olarak sitenize eklenecektir. <br> 
            Müşterilerinizin tek yapması gereken kargo takip veya sipariş numarasını girerek sorgulamak.
            </p>
            
            
           <h3><b>Bilgiler</b></h3>
            <b>1.</b> Kargom Nerede eklentisini eklediğiniz zaman 10 kargo / ay sorgulayabileceğiniz ücretsiz sürümü deneyebilirsiniz. <br>
            <b>2.</b> Sizin için oluşturduğumuz takip sayfasını bu link üzerinden görüntüleyebilirsiniz. <a href="' . esc_url( $tracking_page_link ) . '" target="_blank">' . $tracking_page_link . '</a>  <br>
            <b>3.</b> Kargolarınız oluştuktan sonra panelimizle eşitlenir. Dashboard, grafikler, kargo listesini görmek için <a href="https://kurumsal.kargomnerede.co/" target="_blank"><b>buraya</b></a> tıklayabilirsiniz. (Aşağıdaki email ve şifrenizle giriş yapabilirsiniz.)<br>
            <b>4.</b> Takip sayınız bittiğinde farklı planlara geçmek için <a href="https://kurumsal.kargomnerede.co/" target="_blank"><b>buraya</b></a> tıklayabilirsiniz. (Aşağıdaki email ve şifrenizle giriş yapabilirsiniz.)<br>
            <b>5.</b> Desteklenen kargo firmaları; Aras Kargo, Yurtiçi Kargo, MNG Kargo, PTT Kargo, UPS Kargo, Sürat Kargo, TNT Kargo, DHL Express, Trendyol Express, HepsiJet, 
            Kolay Gelsin, CanadaPost, Yanwen, AliExpress / Cainiao, İnter Global Kargo, KargoIst, Sendeo Kargo, Jetizz, Aramex, Aras Kurye, China Ems, UPS Global, KargomSende, NetKargo, Purolator, Dhl Global, Poste Italiane, Deutsche Post, Uk Mail.<br>
            <b>6.</b> Kargo durum değişikliklerinde müşteriniz otomatik olarak mail ve sms ile bilgilendirilir. Mail ve sms şablonunu görmek için dashboarda girebilirsiniz.<br>
            <b>7.</b> Netgsm bilgilerinizi girerek kendi markalı numaranızdan müşterilerinize SMS gönderebilirsiniz. Dashboard > Entegrasyonlar > Netgsm sayfasından Netgsm bilgierinizi girebilirsiniz.<br>
            <h3><b> Hakkımızda</b></h3>
            <p>Kargom Nerede Web Sayfası -> <a href="https://kargomnerede.co"  target="_blank" >https://kargomnerede.co</a></p>
            <p> Bizimle iletişime geçmek ve görüşlerinizi belirtmek için <a href="mailto: destek@kargomnerede.co"> destek@kargomnerede.co </a> mail adresimize mail gönderebilirsiniz.
            <br>
            <h4><b>Planınızı yükseltmek için ve dashboard için <a href="https://kurumsal.kargomnerede.co/" target="_blank"><b>buraya</b></a> tıklayabilirsiniz. (Aşağıdaki email ve şifrenizle giriş yapabilirsiniz.)</b></h4>
            </div><br><br>
        ',
	)
);

$form_fields .= $plugin_settings_form->wpub_wp_text_input(
	array(
		'name'              => 'name',
		'id'                => 'name',
		'label'             => 'Kullanıcı Adı',
		'value'             => $body['value']['name'],
		'wrapper_class'     => 'form-row',
		'custom_attributes' => array(
			'disabled' => 'disabled',
		),
	)
);

$form_fields .= $plugin_settings_form->wpub_wp_text_input(
	array(
		'name'              => 'email',
		'id'                => 'email',
		'label'             => 'Email',
		'value'             => $body['value']['email'],
		'wrapper_class'     => 'form-row',
		'custom_attributes' => array(
			'disabled' => 'disabled',
		),
	)
);

$form_fields .= $plugin_settings_form->wpub_wp_text_input(
	array(
		'name'              => 'password',
		'id'                => 'password',
		'label'             => 'Şifre',
		'value'             => $user_pass,
		'wrapper_class'     => 'form-row',
		'custom_attributes' => array(
			'disabled' => 'disabled',
		),
	)
);

$form_fields .= $plugin_settings_form->wpub_wp_text_input(
	array(
		'name'              => 'phoneno',
		'id'                => 'phoneno',
		'label'             => 'Telefon Numarası',
		'value'             => $body['value']['phoneNumber'],
		'wrapper_class'     => 'form-row',
		'custom_attributes' => array(
			'disabled' => 'disabled',
		),
	)
);
if ( ! empty( $body['value']['companyPageId'] ) ) {

	$url = 'https://api.kargomnerede.co/api/customer/detailByPageId';

	$company_data = array(
		'companyPageId' => $body['value']['companyPageId'],
	);

	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json-patch+json',
		),
		'body'    => wp_json_encode( $company_data, true ),
		'timeout' => 10000,
	);

	$response = wp_remote_post( $url, $args );
	if ( ! is_wp_error( $response ) ) {
		$querybody = wp_remote_retrieve_body( $response );
		if ( ! empty( $body ) ) {
			$querybody = json_decode( $querybody, true );


			$form_fields .= $plugin_settings_form->wpub_wp_text_input(
				array(
					'name'              => 'remainingquery',
					'id'                => 'remaining_query_limit',
					'label'             => 'Kalan Takip Sayısı',
					'value'             => $querybody['value']['premiumUser']['queryQuantity'],
					'wrapper_class'     => 'form-row',
					'custom_attributes' => array(
						'disabled' => 'disabled',
					),
				)
			);

			$form_fields .= $plugin_settings_form->wpub_wp_text_input(
				array(
					'name'              => 'phoneno',
					'id'                => 'total_query_limit',
					'label'             => 'Toplam Takip Sayısı',
					'value'             => $querybody['value']['premiumUser']['totalQueryQuantity'],
					'wrapper_class'     => 'form-row',
					'custom_attributes' => array(
						'disabled' => 'disabled',
					),
				)
			);

		}
	}
}




$form_fields .= $plugin_settings_form->wpub_wp_hidden_input(
	array(
		'name'  => 'action',
		'value' => 'wck_save_otp_verification_settings',
	)
);


$form_fields .= $plugin_settings_form->wpub_wp_hidden_input(
	array(
		'name'  => '_nonce',
		'value' => wp_create_nonce( 'awp_otp_settings' ),
	)
);

$plugin_settings_form->render( $form_fields );

?>
