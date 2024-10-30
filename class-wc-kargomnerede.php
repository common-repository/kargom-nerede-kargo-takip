<?php
/**
 * Plugin Name: Kargom Nerede - Kargo Takip
 * Description: A WooCommerce Extension to track package with KargomNerede
 * Version: 1.0.0
 * Author: Kargom Nerede
 * Author URI: http://anmolwebworld.com
 * Text Domain: kargomNerede
 * WC tested up to: 9.0.1
 * WC requires at least: 4.9
 *
 * @package kargomNerede
 * @author    Kargom Nerede
 * @category  Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_KargomNerede' ) ) :

	/**
	 * WC_KargomNerede main plugin class
	 *
	 *  @since 1.0.0
	 */
	class WC_KargomNerede {



		/**
		 * WC_KargomNerede instance
		 *
		 * @var $instance
		 */
		protected static $instance;
		/**
		 * Current plugin version
		 *
		 * @var $version
		 */
		protected $version = '1.0.0';
		/**
		 * Hold plugin settings instead of confidential keys
		 *
		 * @var $dbsettings
		 */
		public $dbsettings;

		/**
		 * Load default settings and plugin's initialization
		 *
		 *  @since 1.0.0
		 */
		public function __construct() {
			 global $wpdb;
			$this->dbsettings = maybe_unserialize( get_option( 'wck_options' ) );
			$this->wck_init_plugin();
		}

		/**
		 * Fire on Activation
		 *
		 * @since 1.0.0
		 * @param boolean $network_wide for network wide.
		 */
		public static function wck_activate( $network_wide ) {
			self::wck_activation_task();
		}



		/**
		 *  Placeholder for creating tables while activationg and save default plugin data
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private static function wck_activation_task() {
			 require_once dirname( __FILE__ ) . '/includes/wck-default-settings.php';
		}


		/**
		 * Initialize plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wck_init_plugin() {
			 $plugin_settings = $this->dbsettings;
			$this->wck_define_constants();              /* Define constats */
			$this->wck_includes();                      /* Include files */
			if ( is_admin() ) {
				$this->wck_admin_includes();
			}
			$this->wck_hooks();                      /* Include files */

			// add_filter('wp_footer', array($this,'wcov_get_order_details'));.
		}

		/**
		 * Define Add-on constants
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wck_define_constants() {
			if ( ! defined( 'WCK_VERSION' ) ) {
				define( 'WCK_VERSION', $this->version );           // Plugin Version.
			}

			if ( ! defined( 'WCK_FILE' ) ) {
				define( 'WCK_FILE', __FILE__ );           // Plugin Main Folder Path.
			}

			if ( ! defined( 'WCK_PATH' ) ) {
				define( 'WCK_PATH', dirname( WCK_FILE ) );           // Parent Directory Path.
			}

			if ( ! defined( 'WCK_INCLUDES' ) ) {
				define( 'WCK_INCLUDES', WCK_PATH . '/includes' );           // Include Folder Path.
			}
			if ( ! defined( 'WCK_URL' ) ) {
				define( 'WCK_URL', plugins_url( '', WCK_FILE ) );           // URL Path.
			}

			if ( ! defined( 'WCK_ASSETS' ) ) {
				define( 'WCK_ASSETS', WCK_URL . '/assets' );           // Asset Folder Path.
			}

			if ( ! defined( 'WCK_VIEWS' ) ) {
				define( 'WCK_VIEWS', WCK_PATH . '/views' );           // View Folder Path.
			}

			if ( ! defined( 'WCK_TEMPLATES' ) ) {
				define( 'WCK_TEMPLATES', WCK_PATH . '/templates/' );           // View Folder Path.
			}

			if ( ! defined( 'WCK_CSS' ) ) {
				define( 'WCK_CSS', WCK_URL . '/assets/css/' );
			}

			if ( ! defined( 'WCK_JS' ) ) {
				define( 'WCK_JS', WCK_URL . '/assets/js/' );
			}

			if ( ! defined( 'WCK_IMAGES' ) ) {
				define( 'WCK_IMAGES', WCK_URL . '/assets/images/' );
			}
		}

		/**
		 * Include the required files.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wck_includes() {        }
		/**
		 * Includes required files for admin.
		 *
		 * @since 1.0.0
		 */
		public function wck_admin_includes() {
			require_once WCK_INCLUDES . '/class-kargomneredekargotakipadminmenu.php';
			$this->wck_admin = new KargomNeredeKargoTakipAdminMenu();

			include_once WCK_INCLUDES . '/class-kargomneredekargotakipfields.php';
		}

		/**
		 * Plugin's action and filter
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wck_hooks() {
			// Load script for tab switch into frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'wck_frontend_css' ) );

			add_action( 'admin_print_scripts', array( $this, 'wck_admin_script' ) );

			add_shortcode( 'kargomNerede_tracking', array( $this, 'wck_kargom_nerede_tracking_markup' ) );

			add_action( 'add_meta_boxes', array( $this, 'wck_add_meta_box' ) );

			add_action( 'wp_ajax_wck_tracking_save_form', array( $this, 'wck_ajax_save_meta_box' ) );

			add_action( 'wp_ajax_wck_tracking_delete_item', array( $this, 'wck_meta_box_delete_tracking' ) );

			add_action( 'wp_ajax_wck_ajax_req', array( $this, 'wck_ajax_req' ) );
			
			add_action( 'wp_ajax_nopriv_wck_ajax_req', array( $this, 'wck_ajax_req' ) );

			add_filter('sanitize_post_meta_tracking_number', array( $this, 'wck_sanitize_tracking_number'));

			add_filter('sanitize_post_meta_order_id', array( $this, 'wck_sanitize_order_id'));

			add_filter('sanitize_post_meta_ajax_handler', array( $this, 'wck_sanitize_ajax_handler'));

			add_filter('sanitize_post_meta_tracking_provider', array( $this, 'wck_sanitize_tracking_provider'));
			
			add_filter('sanitize_post_meta_tracking_provider_name', array( $this, 'wck_sanitize_tracking_provider_name'));

			add_filter('sanitize_post_meta_OrderNumber', array( $this, 'wck_sanitize_OrderNumber'));
		}

		function wck_sanitize_tracking_number( $tracking_number ) {
			$tracking_number = sanitize_text_field( $tracking_number );
			return $tracking_number;
		}

		function wck_sanitize_order_id( $order_id ) {
			$order_id = sanitize_text_field( $order_id );
			return $order_id;
		}

		function wck_sanitize_ajax_handler( $ajax_handler ) {
			$ajax_handler = sanitize_text_field( $ajax_handler );
			return $ajax_handler;
		}

		function wck_sanitize_tracking_provider( $tracking_provider ) {
			$tracking_provider = sanitize_text_field( $tracking_provider );
			return $tracking_provider;
		}

		function wck_sanitize_tracking_provider_name( $tracking_provider_name ) {
			$tracking_provider_name = sanitize_text_field( $tracking_provider_name );
			return $tracking_provider_name;
		}
		
		function wck_sanitize_OrderNumber( $OrderNumber ) {
			$OrderNumber = sanitize_text_field( $OrderNumber );
			return $OrderNumber;
		}

		/**
		 * Function for ajax requirements.
		 */
		public function wck_ajax_req() {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wck_nonce_value' ) ) {
					die( 'Busted!' );
				}
				if ( isset( $_POST['ajax_handler'] ) ) {
					$ajax_handler = sanitize_meta( 'ajax_handler', wp_unslash( $_POST['ajax_handler'] ), 'post' );
					$response     = $this->$ajax_handler( $_POST );
					if ( ! empty( $_POST['wck_ajax_req'] ) ) {
						if ( is_array( $response ) ) {
							echo wp_kses_post( htmlspecialchars( wp_json_encode( $response ) ) );
						} else {
							echo wp_kses_post( htmlspecialchars( wp_json_encode( array( $response ) ), ENT_QUOTES, 'UTF-8' ) );
						}
					} else {
						echo wp_kses_post( stripslashes( wp_json_encode( $response ) ) );
					}
				}
				exit;
			}
		}

		/**
		 * Function for package number.
		 *
		 * @param array $posted_data for posted data.
		 * @param bool  $skip_for_order_match for order match.
		 */
		public function wck_tracking_package_with_number( $posted_data, $skip_for_order_match = false ) {

			if ( $skip_for_order_match ) {
				$match_order_id = $skip_for_order_match;
			} else {
				$match_order_id = $this->wcov_get_order_details( $posted_data['tracking_number'] );
			}
			$response = array();

			if ( ! ( $match_order_id ) ) {
				$response = array(
					'error' => true,
					'msg'   => $posted_data['tracking_number'] . ' takip numarası hiç bir siparişle uyuşmuyor.',
				);
			} else {

				$url     = 'https://api.kargomnerede.co/api/customer/cargo/query';
				$api_key = get_option( 'wck_admin_api_key' );

				$body = array(
					'barcodes' => array(
						array(
							'code'      => $posted_data['tracking_number'],
							'companyId' => 0,
						),
					),
				);
				$body = wp_json_encode( $body, true );

				$args = array(
					'headers' => array(
						'Content-Type' => 'application/json',
						'ApiKey'       => $api_key,
					),
					'body'    => $body,
					'timeout' => 10000,

				);
				$api_response = wp_remote_post( $url, $args );

				if ( ! is_wp_error( $api_response ) ) {
					$response_body = wp_remote_retrieve_body( $api_response );
					if ( ! empty( $response_body ) ) {
						$response_body = json_decode( $response_body, true );
						if ( ! empty( $response_body['success'] ) ) {

							$html     = $this->prepare_markup_breakpoints( $response_body, $match_order_id );
							$response = array(
								'success' => true,
								'msg'     => $html,
							);

							return $response;
						} else {
							$response = array(
								'error' => true,
								'msg'   => $response_body['message'],
							);
						}
					} else {
						$response = array(
							'error' => true,
							'msg'   => $response_body,
						);
					}
				} else {
					$response = array(
						'error' => true,
						'msg'   => 'Something Went Wrong',
					);
				}
			}

			return $response;
		}

		/**
		 * Function for break points.
		 *
		 * @param object $response for response.
		 * @param int    $order_id for order_id.
		 */
		public function prepare_markup_breakpoints( $response, $order_id ) {

			$all_items_markup = '';
			if ( ! empty( $response['value'] ) ) {

				foreach ( $response['value'] as $one_item_tracking ) {
					$one_item_tracking_markup = '';

					$tracking_data = $one_item_tracking['value'];
					if ( ! empty( $tracking_data ) ) {
						$product_name    = $tracking_data['name'];
						$tracking_number = $tracking_data['barcode'];
						$company_name    = $tracking_data['companyName'];
						$company_image   = $tracking_data['companyImage'];
						$movements       = $tracking_data['movement'];

						$package_status       = $tracking_data['status'];
						$tracking_heading     = '';
						$tracking_status_date = '';
						$banner_width         = '0';
						if ( ! empty( $package_status ) && '4' === $package_status ) {

							$tracking_heading = 'Siparişiniz teslim edildi.';
							$banner_width     = 'full';
						}
						if ( ! empty( $package_status ) && '5' === $package_status ) {

							$tracking_heading = 'Siparişiniz çıkış biriminde.';
							$banner_width     = 'start';
						}
						if ( ! empty( $package_status ) && '3' === $package_status ) {

							$tracking_heading = 'Siparişiniz teslimat şubesine ulaştı.';
							$banner_width     = 'onethrid';
						}
						if ( ! empty( $package_status ) && '2' === $package_status ) {

							$tracking_heading = 'Siparişiniz dağıtıma çıktı.';
							$banner_width     = 'half';
						}
						if ( ! empty( $package_status ) && '1' === $package_status ) {

							$tracking_heading = 'Siparişiniz yolda.';
							$banner_width     = 'onefourth';
						}
						$delivered_date = '';
						$pickup_date    = '';
						// Tracking Status date.
						if ( ! empty( $movements ) ) {
							$latest_status          = end( $movements );
							$last_status_of_package = ! empty( $latest_status ) && ! empty( $latest_status['status'] ) ? $latest_status['status'] : 5;
							$package_status_date    = gmdate( 'd/m/Y', strtotime( $latest_status['date'] ) );

							$tracking_status_date = 'Son Güncelleme:' . $package_status_date;
							if ( '4' === $last_status_of_package ) {
								$delivered_date = $package_status_date;
							}
							if ( '2' === $last_status_of_package ) {
								$pickup_date = $package_status_date;
							}
							if ( '1' === $last_status_of_package ) {
								$section_variable_extra = 0;
							}
						}

						$one_item_tracking_markup  = '<div style="width: 100%; max-width: 1000px; margin: 0 auto;"><p id="trackResultContainer" class="track_no"><span class="track_type">Kargo Takip Numarası:</span> ' . $tracking_number . '</p>';
						$one_item_tracking_markup .= '<div><div class="SlotLayout_slot3__pDrqN"><div id="OrderStatus" class="OrderStatus_container__z_C2n">';
						$one_item_tracking_markup .= '<div class="OrderStatus_track_info__o2T_X"><p class="OrderStatus_status_title__bT2BE" >' . $tracking_heading . '</p>';
						$one_item_tracking_markup .= '<p class="OrderStatus_status_time__sLxSa">' . $tracking_status_date . '</p></div>';

						if ( ! empty( $movements ) ) {
							// For mobile menu.

							$one_item_tracking_markup .= '<div class="OrderStatus_track_status_mobile__BSOmE OrderStatus_track_status_mobile">
						<div class="OrderStatus_progress_bar_mobile__3Tf7A">
							<span class="OrderStatus_progress_active_mobile__9A191 OrderStatus_progress_active_mobile_1000__PUVEn ' . $banner_width . '" >
							</span>
						</div>';

							$is_order_created = in_array( $package_status, array( 1, 2, 3, 4, 5 ) ) ? 'reached' : '';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_mobile__LB7sP" style="">
							<span class="OrderStatus_progress_node_mobile__eNl15 ' . $is_order_created . '"></span>
							<span class="OrderStatus_status_icon_mobile__cLUCI">
								<i class="fa fa-shopping-cart fa-2x" style=" color: #f59342; "></i>
							</span>
							<p class="OrderStatus_status_info_mobile__7XrtY">
								<span class="OrderStatus_status_desc_mobile__1YR_O " style="color: rgb(97, 97, 97);">
									Sipariş Edildi             </span>
							</p>
						</div>';
							$is_info_recieved          = in_array( $package_status, array( 1, 2, 3, 4 ) ) ? 'reached' : '';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_mobile__LB7sP" style="">
							<span class="OrderStatus_progress_node_mobile__eNl15 ' . $is_info_recieved . '" ></span>
							<span class="OrderStatus_status_icon_mobile__cLUCI">
								<i class="fa fa-road fa-2x" style=" color: #f59342; "></i>
							</span>
							<p class="OrderStatus_status_info_mobile__7XrtY">
								<span class="OrderStatus_status_desc_mobile__1YR_O  status_desc_active " style="color: rgb(97, 97, 97);">
									Yolda             </span>
							</p>
						</div>';

							$is_intransit = in_array( $package_status, array( 2, 3, 4 ) ) ? 'reached' : '';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_mobile__LB7sP" style="">
							<span class="OrderStatus_progress_node_mobile__eNl15 ' . $is_intransit . '"></span>
							<span class="OrderStatus_status_icon_mobile__cLUCI">
								<i class="fa fa-bezier-curve fa-2x" style=" color: #f59342; "></i>
							</span>
							<p class="OrderStatus_status_info_mobile__7XrtY">
								<span class="OrderStatus_status_desc_mobile__1YR_O  status_desc_active " style="color: rgb(97, 97, 97);">
									Dağıtımda             </span>
							</p>
						</div>';
							$is_pickup                 = in_array( $package_status, array( 3, 4 ) ) ? 'reached' : '';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_mobile__LB7sP" style="">
							<span class="OrderStatus_progress_node_mobile__eNl15 ' . $is_pickup . '" ></span>
							<span class="OrderStatus_status_icon_mobile__cLUCI">
								<i class="fa fa-box-open fa-2x" style=" color: #f59342; "></i>
							</span>
							<p class="OrderStatus_status_info_mobile__7XrtY">
								<span class="OrderStatus_status_desc_mobile__1YR_O  status_desc_active " style="color: rgb(97, 97, 97);">
									Teslimat Şubesinde             </span>
							</p>
						</div>';
							$is_delivered              = ( '4' === $package_status ) ? 'reached' : '';
							$one_item_tracking_markup .= '<div class="OrderStatus_progress_mobile__LB7sP" style="">
							<span class="OrderStatus_progress_node_mobile__eNl15 ' . $is_delivered . '" ></span>
							<span class="OrderStatus_status_icon_mobile__cLUCI">
								<i class="fa fa-thumbs-up fa-2x" style=" color: #f59342; "></i>
							</span>
							<p class="OrderStatus_status_info_mobile__7XrtY">
								<span class="OrderStatus_status_desc_mobile__1YR_O  status_desc_active " style="color: rgb(97, 97, 97);">
									Teslim Edildi             </span>
							</p>
						</div>';
							$one_item_tracking_markup .= '</div>';

							// For Desktop Version.
							$one_item_tracking_markup .= '<div class="OrderStatus_track_status__iAUKP OrderStatus_track_status"><div class="OrderStatus_progress_bar__hrwHk"><span class="OrderStatus_progress_active__T8Mun OrderStatus_progress_active_1000__TOkWF ' . $banner_width . '"></span></div>';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_node__Snj5a OrderStatus_left_0__9fLLX ' . $is_order_created . '" ><span class="OrderStatus_status_icon__KAlyM"><i class="fa fa-shopping-cart fa-2x" style=" color: #f59342; "></i></span><p class="OrderStatus_status_info__id76W"><span class="OrderStatus_status_desc__KWLmy" >Sipariş Edildi</span><span class="OrderStatus_trigger_time__aVe9l"></span></p></div>';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_node__Snj5a OrderStatus_left_25__HXOBg ' . $is_info_recieved . '" ><span class="OrderStatus_status_icon__KAlyM"><i class="fa fa-road fa-2x" style=" color: #f59342; "></i></span><p class="OrderStatus_status_info__id76W"><span class="OrderStatus_status_desc__KWLmy" >Yolda</span><span class="OrderStatus_trigger_time__aVe9l"></span></p></div>';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_node__Snj5a OrderStatus_left_50__Bk0m0 ' . $is_intransit . '" ><span class="OrderStatus_status_icon__KAlyM"><i class="fa fa-bezier-curve fa-2x" style=" color: #f59342; "></i></span><p class="OrderStatus_status_info__id76W"><span class="OrderStatus_status_desc__KWLmy" >
							Dağıtımda</span><span class="OrderStatus_trigger_time__aVe9l"></span></p></div>';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_node__Snj5a OrderStatus_left_75__HBN1i ' . $is_pickup . '" ><span class="OrderStatus_status_icon__KAlyM"><i class="fa fa-box-open fa-2x" style=" color: #f59342; "></i></span><p class="OrderStatus_status_info__id76W"><span class="OrderStatus_status_desc__KWLmy" >Teslimat Şubesinde</span><span class="OrderStatus_trigger_time__aVe9l">' . $pickup_date . '</span></p></div>';

							$one_item_tracking_markup .= '<div class="OrderStatus_progress_node__Snj5a OrderStatus_left_100__OYU47 ' . $is_delivered . '" ><span class="OrderStatus_status_icon__KAlyM"><i class="fa fa-thumbs-up fa-2x" style=" color: #f59342; "></i></span><p class="OrderStatus_status_info__id76W"><span class="OrderStatus_status_desc__KWLmy status_desc_active" >Teslim Edildi</span><span class="OrderStatus_trigger_time__aVe9l">' . $delivered_date . '</span></p></div>';

							$one_item_tracking_markup .= '</div>';
						}

						$one_item_tracking_markup .= '</div></div>';

						$tracking_save_data = get_post_meta( $order_id, '_wck_tracking_items', true );

						$latest_status = end( $movements );

						$one_item_tracking_markup .= '<div class="SlotLayout_track_container__fS8Jk">
					<div class="SlotLayout_slot4___Ww2O SlotLayout_slot4_track_information">
					<div id="LogisticsTrack" class="LogisticsTrack_container__UI7K7">
						<div id="TRANSLATOR">
							<div class="Translator_container__d4OQT">
								<div class="Translator_title__1IoXD">
								Kargo Hareketleri</div>
							</div>
						</div>
								
						<div class="LogisticsTrack_provider__FcZwo">
							<div class="LogisticsTrack_provider_info__HjyPH LogisticsTrack_provider_info_box">
							<img src="' . $company_image . '" class="LogisticsTrack_provider_logo__ujUbN num-carrier-38x38-13141 spr-carrier-38x38-13141">
								<div class="LogisticsTrack_provider_content__C2QGv"><a
										class="LogisticsTrack_provider_name__TjxEO text-underline" href="https://kargomnerede.co/"
										target="_blank" >' . $tracking_save_data['tracking_provider_name'] . '</a>';

						$one_item_tracking_markup .= '</div>
							</div>
							<div class="LogisticsTrack_event_list__c4_AQ LogisticsTrack_event_list">';

						if ( ! empty( $movements ) ) {

							$rmovements = array_reverse( $movements );

							$first_movement = $rmovements['0'];
							$first_mov_date = gmdate( 'd/m/Y h:m:s', strtotime( $first_movement['date'] ) );
							$first_mov_desc = $first_movement['description'];

							$one_item_tracking_markup .= '<div class="LogisticsTrack_event_item__pnVai">
										<div class="LogisticsTrack_event_icon__0P46w">
											<div class="LogisticsTrack_icon_border__hzOT2">
												<span class="LogisticsTrack_icon_box__icHG6" style="border: 2px solid #f59342;"></span></div>
										</div>
										<div class="LogisticsTrack_event_info__kgAs6 LogisticsTrack_event_info_background">
										<span class="LogisticsTrack_event_time__oZKqO" style="color: rgb(33, 33, 33);">' . $first_mov_date . '</span>
										<span class="LogisticsTrack_event_content__xEjwS" style="color: rgb(33, 33, 33);">' . $first_mov_desc . '</span>
										</div>
									</div>';

							foreach ( $rmovements as $move_key => $mov ) {
								if ( 0 === $move_key ) {
									continue;
								}
								$mov_date = gmdate( 'd/m/Y h:m:s', strtotime( $mov['date'] ) );
								$mov_desc = $mov['description'];

								$one_item_tracking_markup .= '<div class="LogisticsTrack_event_item__pnVai">
											<i class="LogisticsTrack_triangle_icon__ujMra"></i>
											<div class="LogisticsTrack_event_info__kgAs6 LogisticsTrack_event_info_background">
											<span class="LogisticsTrack_event_time__oZKqO" style="color: rgb(109, 113, 117);">' . $mov_date . '</span>
											<span class="LogisticsTrack_event_content__xEjwS" style="color: rgb(109, 113, 117);">' . $mov_desc . '</span>
											</div>
										</div>';
							}
						}

						$one_item_tracking_markup .= '</div>
						</div>
					</div>
					
					</div>';

						$one_item_tracking_markup .= '<div class="SlotLayout_slot5__mCTfs SlotLayout_slot5_order_information">
						<div id="OrderDetail" class="OrderDetail_container__gsERW">
							<div class="OrderDetail_title__tN2Xt">
							Sipariş Bilgisi
							</div>
							<p class="OrderDetail_info_item__zCQOn"><span class="OrderDetail_info_key__iJxdk">Sipariş Numarası</span><span
									class="OrderDetail_info_content__8VCJO">#' . $order_id . '</span></p>
							<p class="OrderDetail_info_item__zCQOn"><span class="OrderDetail_info_key__iJxdk">Kargo Takip Numarası</span><span class="OrderDetail_info_content__8VCJO">' . $tracking_number . '</span></p>';

						$one_item_tracking_markup .= '</div>';
						$tracking_order            = wc_get_order( $order_id );

						$one_item_tracking_markup .= '
					<div class="OrderProducts_container__HMibE PackageContentContainer">
						<h5 class="OrderProducts_head_title__aphhE">Ürünler</h5>';

						foreach ( $tracking_order->get_items() as $item_id => $item ) {
							$product       = $item->get_product();
							$active_price  = $product->get_price(); // The product active raw price.
							$product_name  = $item->get_name(); // Get the item name (product name).
							$item_quantity = $item->get_quantity(); // Get the item quantity.
							$item_total    = $item->get_total(); // Get the item line total discounted.

							$pid = $product->get_id();

							$one_item_tracking_markup .= '
							<div class="OrderProducts_flex-box__3NO9a">
								<div class="OrderProducts_img-box__IYBBa">
								<img src="' . get_the_post_thumbnail_url( $pid ) . '">
								</div>
								<div class="OrderProducts_product-info__RAQWl"><a class="OrderProducts_product-name__rJPpw">' . $product_name . '</a>
									<div class="OrderProducts_flex-box__3NO9a">
										<div class="OrderProducts_product-count__9kXfN">' . $item_quantity . ' Adet</div>
									</div>
								</div>
							</div>';
						}

						$one_item_tracking_markup .= '</div></div></div>';

						$one_item_tracking_markup .= '</div></div>';
						$all_items_markup         .= $one_item_tracking_markup;
					}
				}
			}
			return $all_items_markup;
		}

		/**
		 * Function for order detail.
		 *
		 * @param int $tracking_number for tracking number.
		 */
		public function wcov_get_order_details( $tracking_number ) {

			$orders         = wc_get_orders(
				array(
					'limit'        => -1, // Query all orders.
					'orderby'      => 'date',
					'order'        => 'DESC',
					'meta_key'     => '__track_number', // The postmeta key field.
					'meta_value'   => $tracking_number,
					'meta_compare' => '=', // The comparison argument.
				)
			);
			$match_order_id = false;
			if ( ! empty( $orders ) ) {
				foreach ( $orders as $order ) {
					$match_order_id = $order->get_id();
				}
			}

			return $match_order_id;
		}

		/**
		 * Function for delete track item.
		 */
		public function wck_meta_box_delete_tracking() {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( isset( $_POST['nonce'] ) ? $_POST['nonce'] : '' ) ), 'ajax-nonce' ) ) {
				echo ( 'Destroy!' );
			}

			$order_id = 0;
			if ( isset( $_POST['order_id'] ) ) {
				$order_id = sanitize_meta( 'order_id', wp_unslash( $_POST['order_id'] ), 'post' );
			}

			$tracking_number = 0;
			if ( isset( $_POST['tracking_number'] ) ) {
				$tracking_number = sanitize_meta( 'tracking_number', wp_unslash( $_POST['tracking_number'] ), 'post' );
			}
			$this->wck_delete_tracking_item( $order_id, $tracking_number );
		}

		/**
		 * Function for delete track item.
		 *
		 * @param int $order_id for order id.
		 * @param int $tracking_number for tracking number.
		 */
		public function wck_delete_tracking_item( $order_id, $tracking_number ) {
			$tracking_items = $this->wck_get_tracking_items( $order_id );
			$is_deleted     = false;

			if ( count( $tracking_items ) > 0 ) {
				foreach ( $tracking_items as $key => $item ) {
					if ( $item['tracking_number'] == $tracking_number ) {
						unset( $tracking_items[ $key ] );
						$is_deleted = true;
						break;
					}
				}
				$this->wck_save_tracking_items( $order_id, $tracking_items, true );
			}

			return $is_deleted;
		}

		/**
		 * Function for a metabox.
		 */
		public function wck_ajax_save_meta_box() {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( isset( $_POST['nonce'] ) ? $_POST['nonce'] : '' ) ), 'ajax-nonce' ) ) {
				echo ( 'Destroy!' );
			}
			$tracking_number = false;
			if ( isset( $_POST['tracking_number'] ) ) {
				$tracking_number = sanitize_meta( 'tracking_number', wp_unslash( $_POST['tracking_number'] ), 'post' );
			}
			$tracking_provider = false;
			if ( isset( $_POST['tracking_provider'] ) ) {
				$tracking_provider = sanitize_meta( 'tracking_provider', wp_unslash( $_POST['tracking_provider'] ), 'post' );
			}
			$tracking_provider_name = false;
			if ( isset( $_POST['tracking_provider_name'] ) ) {
				$tracking_provider_name = sanitize_meta( 'tracking_provider_name', wp_unslash( $_POST['tracking_provider_name'] ), 'post' );
			}
			if ( ( $tracking_number ) && '' != $tracking_provider && ( $tracking_provider ) && strlen( $tracking_number ) > 0 ) {

				if ( isset( $_POST['order_id'] ) ) {
					$order_id = sanitize_meta( 'order_id', wp_unslash( $_POST['order_id'] ), 'post' );
					$args     = array(
						'tracking_provider'      => $tracking_provider,
						'tracking_provider_name' => $tracking_provider_name,
						'tracking_number'        => $tracking_number,
					);

					$api_worked = $this->add_tracking_number_to_cargo( $order_id, $args );

					update_post_meta( $order_id, '__track_number', $tracking_number );
					update_post_meta( $order_id, '__tracking_provider', $tracking_provider );
					update_post_meta( $order_id, '_wck_tracking_items', $args );
					$this->wck_display_html_tracking_item_for_meta_box( $order_id, $args );
				}
			}

			die();
		}

		/**
		 * Function for order tracking.
		 *
		 * @param int   $order_id for order is.
		 * @param array $args for arguments.
		 */
		public function add_tracking_number_to_cargo( $order_id, $args ) {
			if ( ! $order_id ) {
				return;
			}
			$order = wc_get_order( $order_id );

			$api_key = get_option( 'wck_admin_api_key' );

			// Get the Customer billing email.
			$customer_email = $order->get_billing_email();

			// Get the Customer billing phone.
			$customer_phone_number = $order->get_billing_phone();

			// Customer billing information details.
			$billing_first_name = $order->get_billing_first_name();
			$billing_last_name  = $order->get_billing_last_name();

			$fullname     = $billing_first_name . ' ' . $billing_last_name;
			$items        = $order->get_items();
			$product_name = '';
			foreach ( $items as $item ) {
				$product_name = $item->get_name();
				break;
			}
			$raw_code = $billing_first_name . $order_id;

			$query_data = array(
				'barcodes'     => array(
					array(
						'code'                => $args['tracking_number'],
						'companyId'           => $args['tracking_provider'],
						'name'                => $product_name,
						'isShareCodeCreated'  => true,
						'orderNumber'         => $order_id,
						'customerName'        => $fullname,
						'customerPhoneNumber' => $customer_phone_number,
						'customerEmail'       => $customer_email,
						'notificationEnable'  => true,
					),
				),
				'isSubscriber' => true,
			);

			$url = 'https://api.kargomnerede.co/api/customer/cargo/query';

			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json-patch+json',
					'ApiKey'       => $api_key,
				),
				'body'    => json_encode( $query_data, true ),
				'timeout' => 10000,
			);

			$response = wp_remote_post( $url, $args );
			if ( ! is_wp_error( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( ! empty( $body ) ) {
					$body          = json_decode( $body, true );
					$is_saved      = ! empty( $body['value']['0']['value']['isSaved'] ) ? $body['value']['0']['value']['isSaved'] : '';
					$is_first_save = ! empty( $body['value']['0']['value']['isFirstSave'] ) ? $body['value']['0']['value']['isFirstSave'] : '';
					$id            = ! empty( $body['value']['0']['value']['id'] ) ? $body['value']['0']['value']['id'] : '';
					$barcode       = ! empty( $body['value']['0']['value']['barcode'] ) ? $body['value']['0']['value']['barcode'] : '';
					$company_id    = ! empty( $body['value']['0']['value']['companyId'] ) ? $body['value']['0']['value']['companyId'] : '';

					if ( ! empty( $is_saved ) && ! empty( $barcode ) && ! empty( $id ) && ! empty( $company_id ) ) {
						update_post_meta( $order_id, '__track_id', $id );
						update_post_meta( $order_id, '__track_bar_code', $barcode );
						update_post_meta( $order_id, '__track_company_id', $company_id );
						update_post_meta( $order_id, '__track_response', $body );
					}
				}

				return true;
			} else {
				return false;
			}
		}


		/**
		 * Function  for order tracking.
		 *
		 * @param int  $order_id for order id.
		 * @param int  $tracking_items for order array.
		 * @param bool $is_update_date for bool.
		 */
		public function wck_save_tracking_items( $order_id, $tracking_items, $is_update_date = false ) {
			update_post_meta( $order_id, '_wck_tracking_items', $tracking_items );
			if ( $is_update_date ) {
				$date    = new DateTime();
				$my_post = array(
					'ID'                => $order_id,
					'post_modified'     => $date->format( 'Y-m-dH:i:s' ),
					'post_modified_gmt' => $date->format( 'Y-m-d\TH:i:s\Z' ),
				);
				wp_update_post( $my_post );
			}
		}


		/**
		 * Function to add scripts.
		 */
		public function wck_admin_script() {
			wp_enqueue_script( 'wpic-save-tacking-js', WCK_JS . '/wck_save_tracking.js', array( 'jquery' ), '100.0.0', true );
		}

		/**
		 * Function to add meta box.
		 */
		public function wck_add_meta_box() {
			add_meta_box( 'woocommerce-wck', __( 'Kargom Nerede', 'kargomnerede' ), array( &$this, 'wck_meta_box' ), 'shop_order', 'side', 'high' );
		}

		/**
		 * Function for order item.
		 *
		 * @param int $order_id for order id.
		 */
		public function wck_get_tracking_items( $order_id ) {

			$tracking_items = get_post_meta( $order_id, '_wck_tracking_items', true );
			return $tracking_items;
		}

		/**
		 * Function for order tracking
		 *
		 * @param int   $order_id for order id.
		 * @param array $tracking_item for item array.
		 */
		public function wck_get_formatted_tracking_item( $order_id, $tracking_item ) {
			$formatted                                = array();
			$formatted['formatted_tracking_provider'] = ! empty( $tracking_item['tracking_provider_name'] ) ? $tracking_item['tracking_provider_name'] : '';
			$formatted['formatted_tracking_link']     = '/kargomnerede/?tracking_number=' . $tracking_item['tracking_number'];
			return $formatted;
		}

		/**
		 * Function for tracking id
		 *
		 * @param int   $order_id for order is.
		 * @param array $item for item data.
		 * Item for item data.
		 */
		public function wck_display_html_tracking_item_for_meta_box( $order_id, $item ) {

			$formatted = $this->wck_get_formatted_tracking_item( $order_id, $item );

			?>
			<li>
				<div id="tracking-item-<?php echo esc_attr( $item['tracking_number'] ); ?>">
					<p class="note_content">
						<strong><?php echo esc_html( $formatted['formatted_tracking_provider'] ); ?></strong>
						<br />
						<em><?php echo esc_html( $item['tracking_number'] ); ?></em>
					</p>
					<p class="meta">
						<?php if ( strlen( $formatted['formatted_tracking_link'] ) > 0 ) : ?>
							<?php
							$url = str_replace( '%number%', $item['tracking_number'], $formatted['formatted_tracking_link'] );
							echo sprintf( '<a href="%s" target="_blank" title="' . esc_attr( __( 'Kargo detaylarını görmek için tıklayınız.', 'woocommerce-wck' ) ) . '">' . esc_html__( 'Takip Et', 'woocommerce-wck' ) . '</a>', esc_url( $url ) );
							echo sprintf( '<a href="#" rel="%s" style="margin-left: 4px;" class="wck-delete-tracking" title="' . esc_attr( __( 'Kargo takibini kaldırmak için tıklayınız.', 'woocommerce-wck' ) ) . '">' . esc_html__( 'Sil', 'woocommerce-wck' ) . '</a>', esc_attr( $item['tracking_number'] ) );
							?>
						<?php endif; ?>
					</p>
				</div>
			</li>
			<?php
		}

		/**
		 * Function for getting all countries.
		 */
		public function get_all_couries() {
			 $couries_list = array();
			$url           = 'https://api.kargomnerede.co/api/Company/getall';
			$api_key       = get_option( 'wck_admin_api_key' );

			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json-patch+json',
					'ApiKey'       => $api_key,
				),
			);

			$response = wp_remote_request( $url, $args );

			if ( ! is_wp_error( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( ! empty( $body ) ) {
					$body         = json_decode( $body, true );
					$couries_list = wp_list_pluck( $body['value'], 'name', 'id' );
				}
			}
			return $couries_list;
		}

		/**
		 * Function for meta box
		 */
		public function wck_meta_box() {
			global $post;
			$couries_list = $this->get_all_couries();

			$tracking_item = $this->wck_get_tracking_items( $post->ID );

			echo '<div id="wck-tracking-items">';
			if ( ! empty( $tracking_item ) && count( $tracking_item ) > 0 ) {
				echo '<ul class="order_notes">';
				$this->wck_display_html_tracking_item_for_meta_box( $post->ID, $tracking_item );
				echo '</ul>';
			}
			echo '</div>';
			?>
			<div class="form-field wck-form-field">
				<p class="form-field">
					<label>Kargo Firması</label><br>
					<select name="wck_carrier_list" id="wck_carrier_list">
						<option value="">Seçiniz</option>
						<?php
						if ( ! empty( $couries_list ) ) {
							foreach ( $couries_list as $key => $list ) {
								?>
								<option value="<?php echo esc_attr( $key ); ?>" data-option-name="<?php echo esc_attr( $list ); ?>"><?php echo esc_attr( $list ); ?></option>
								<?php
							}
						}

						?>
					</select>
				</p>
				<p class="form-field  ">
					<label>Kargo Takip Numarası</label>
					<input type="text" name="wck_tracking_number" id="wck_tracking_number" value="" placeholder="Lütfen takip numarası giriniz">
					<?php
					$date = new DateTime();
					$date = $date->format( 'Y-m-d\TH:i:s\Z' );
					echo '<input type="hidden" id="wck_tracking_provider_name" name="wck_tracking_provider_name" value=""/>';

					?>
				</p>
			</div>
			<?php
			echo wp_kses_post( '<button class="button button-primary kargomnerede-save ">' . __( ' Kaydet ', 'kargom-nerede-kargo-takip' ) . '</button>' );
		}


		/**
		 * Function for atts
		 *
		 * @param array $atts for atts.
		 */
		public function wck_kargom_nerede_tracking_markup( $atts ) {
			?>
			<div class="SlotLayout_c_layout__yh7fb SlotLayout_layout_customer">
				<div class="SlotLayout_slot1__AKzDi">
					<h1 id="Heading" class="Heading_container__91kKJ">
						Kargo Detayı</h1>
				</div>
				<div>
					<div id="wck_track">
						<div class="Track_container__X6cb5 Track_Container_Box ">
							<div class="Track_track_number__6qhh3  ">
								<form action="" method="post" name="track_with_tracking_number" id="track_with_tracking_number">
									<div class="Track_track_item__BWmlk">
										<div class="Track_input_label__sd_Y8">
											Kargo Takip Numarası</div>
										<div class="Polaris-Labelled--hidden">
											<div class="Polaris-Connected">
												<div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
													<div class="Polaris-TextField Polaris-TextField--hasValue">
														<input id="wckTrackNumber" name="tracking_number" class="Polaris-TextField__Input" maxlength="50" value="">
														<div class="Polaris-TextField__Backdrop"></div>
														<input type="hidden" name="tracking_with_number" value="tracking_with_number" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="Polaris-Button CustomButton wck_tracking_with_number">Sorgula</div>
								</form>
							</div>
							<div class="Track_or_line__gWxiv "><span class="Track_line__odIMM "></span><span class="Track_or__HAOiA Track_or ">Veya</span><span class="Track_line__odIMM "></span></div>
							<div class="Track_track_number__6qhh3 ">
								<form action="" method="post" name="track_with_email_order" id="track_with_email_order">

									<div class="Track_track_item__BWmlk">
										<div class="Track_input_label__sd_Y8">
											Sipariş Numarası</div>
										<div class="Polaris-Labelled--hidden">
											<div class="Polaris-Connected">
												<div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
													<div class="Polaris-TextField Polaris-TextField--hasValue">
														<input id="OrderNumber" name="OrderNumber" class="Polaris-TextField__Input">
														<div class="Polaris-TextField__Backdrop"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="Track_inline_error__LFNbf"></div>
									</div>
									<div class="Track_track_item__BWmlk">
										<div class="Track_input_label__sd_Y8">
											Email</div>
										<div>
											<div class="Polaris-Labelled--hidden">
												<div class="Polaris-Connected">
													<div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
														<div class="Polaris-TextField Polaris-TextField--hasValue">
															<input id="order_email" name="order_email" class="Polaris-TextField__Input" type="email">
															<div class="Polaris-TextField__Backdrop"></div>
														</div>
													</div>
												</div>
											</div>
											<div class="Track_inline_error__LFNbf"></div>
										</div>
									</div>
									<input type="hidden" name="tracking_with_order_email" value="tracking_with_order_email" />

									<div class="Polaris-Button CustomButton wck_tracking_with_order">Sorgula</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( isset( $_POST['nonce'] ) ? $_POST['nonce'] : '' ) ), 'ajax-nonce' ) ) {
				echo '';
			}

			if ( ! empty( $_POST['tracking_with_number'] ) ) {
				$posted_data = array();
				if ( isset( $_POST ) ) {
					$all_post   = sanitize_meta( 'all_submit_post', wp_unslash( $_POST ), 'post' );
					$order_html = $this->wck_tracking_package_with_number( $all_post, false );
					echo wp_kses_post( $order_html['msg'] );
				}
			}

			if ( ! empty( $_POST['tracking_with_order_email'] ) ) {
				if ( isset( $_POST['OrderNumber'] ) ) {
					$order_number    = sanitize_meta( 'OrderNumber', wp_unslash( $_POST['OrderNumber'] ), 'post' );
					$tracking_number = get_post_meta( $order_number, '__track_number', true );
					$posted_data     = array(
						'tracking_number' => $tracking_number,
					);
					$order_html      = $this->wck_tracking_package_with_number( $posted_data, $order_number );
					echo wp_kses_post( $order_html['msg'] );
				}
			}

			if (!empty($_GET['tracking_number']) && empty($_POST)) {
				
				$tracking_number = $_GET['tracking_number'];
				$tracking_number = sanitize_meta( 'tracking_number', wp_unslash( $tracking_number ), 'post' );
				$posted_data = array(
					'tracking_number' => $tracking_number
				);
				$order_html = $this->wck_tracking_package_with_number($posted_data, false);
				echo $order_html['msg'];
			}
		}




		/**
		 * Enqueue Frontend CSS
		 *
		 * @since 1.0.0
		 */
		public function wck_frontend_css() {
			wp_enqueue_style( 'wck-frontend', WCK_CSS . '/wck-frontend.css', '100.0.2', true );
			wp_enqueue_script( 'wck-frontend-jsd', WCK_JS . '/wck-frontend.js', array(), '100.0.2', false );
			wp_localize_script(
				'wck-frontend-jsd',
				'wck_ajax_obj',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'wck_nonce_value' ),
				)
			);

			$fpd_page_id = get_option( 'wck_page_id' );
			if ( $fpd_page_id ) {
				global $post;
				if ( ! empty( $post->ID ) === $fpd_page_id ) {
					wp_enqueue_style( 'wck-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', '100.0.2', true );
				}
			}
		}



		/**
		 * Instantiate classes
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wck_init_classes() {
			new KargomNeredeKargoTakipAdminMenu();   // Admin Menu Class.
		}

		/**
		 * Main wcovlo_Rewards Instance
		 *
		 * @since 1.0.0
		 * @see instance()
		 * @return instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Checking if woocommerce have installed
		 *
		 * @since 1.0.0
		 */
		public static function wck_missing_wc_notice() {
			?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'WooCommerce is required for wcovlo - The Coins Monster plugin to work. Please install and configure woocommerce first.', 'kargom-nerede-kargo-takip' ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Function for init.
		 */
		public static function wck_init() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', array( __CLASS__, 'wck_missing_wc_notice' ) );
				return;
			}
			$GLOBALS['wc_wcovlo'] = self::instance();
		}


		/**
		 * Entry coin of plugin
		 *
		 * @since 1.0.0
		 */
		public static function init() {
			 add_action( 'plugins_loaded', array( __CLASS__, 'wck_init' ) );
			register_activation_hook( __FILE__, array( __CLASS__, 'wck_activate' ) );
		}
	}

endif;



WC_KargomNerede::init();
