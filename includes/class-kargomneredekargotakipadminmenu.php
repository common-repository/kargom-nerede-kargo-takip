<?php
/**
 * Class KargomNeredeKargoTakipAdminMenu.
 *
 * @package Admin menu class.
 * This file have code for admin menu.
 */

/**
 * Class for Admin Menu.
 */
class KargomNeredeKargoTakipAdminMenu {

	/**
	 * Variable for list table.
	 *
	 * @var \Wollo_Coins_Log_List_Table The coins log list table object
	 */
	private $coins_log_list_table;

	/**
	 * Sesult definition.
	 *
	 * @var \show result after setting save
	 */
	private $result;

	/**
	 * Constructor of class
	 */
	public function __construct() {
		// Css on Settings pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'wck_setting_required_scripts' ) );

		// Register Menu and setting pages.
		add_action( 'admin_menu', array( $this, 'wck_admin_menu' ) );

	}


	/**
	 * Register the admin menu.
	 *
	 * @return void
	 */
	public function wck_admin_menu() {
		global $submenu;

		$wolo_setting = add_menu_page(
			esc_html__( 'Kargom Nerede', 'kargom-nerede-kargo-takip' ),
			esc_html__( 'Kargom Nerede', 'kargom-nerede-kargo-takip' ),
			'manage_options',
			'wck-wp-settings',
			array( $this, 'WCK_settings' )
		);

		add_action( 'load-' . $wolo_setting, array( $this, 'wck_setting_required_scripts' ) );
	}

	/**
	 * Settings Submenu View.
	 */
	public function wck_setting_required_scripts() {
		global $current_screen;
		$dbsettings = maybe_unserialize( get_option( 'wck_options' ) );

		if ( ! empty( $current_screen ) &&
			( 'toplevel_page_wck-wp-settings' === (string) $current_screen->id )

		) {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_scripts( 'wp-color-picker' );
			wp_enqueue_style( 'material_google_fonts ', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons', '100.0.2', true );
			wp_enqueue_style( 'wpic-admin-css', WCK_CSS . '/wpic-admin.css', array(), '100.0.2' );
			wp_enqueue_style( 'select2-min-css', WCK_CSS . '/select2.min.css', '100.0.2', true );
			wp_enqueue_script( 'select2-min-js', WCK_JS . '/select2.min.js', array( 'jquery' ), '100.0.2', false );
			wp_enqueue_script( 'wpic-admin-js', WCK_JS . '/wpic-admin.js', array( 'wp-color-picker' ), '100.0.2', false );
			wp_localize_script( 'wpic-admin-js', 'plugin_settings', $dbsettings );

		}

	}



	/**
	 * Settings Submenu View
	 *
	 * @param string $url for redirect url.
	 */
	public function wol_redirect( $url ) {
		?>
		<script type="text/javascript">
			window.location = "<?php echo esc_url_raw( $url ); ?>";
		</script>
		<?php
	}




	/**
	 * Settings Submenu View
	 *
	 * @param string $value for value.
	 */
	public function wck_settings( $value = '' ) {
		if ( ! empty( $this->result ) && array_key_exists( 'error', $this->result ) ) {
			?>
			<div class='error notice is-dismissible'>
				<p><strong>
					<?php echo esc_html( $this->result['error'] ); ?>
				`	</strong></p>
				<button type='button' class='notice-dismiss'>
					<span class='screen-reader-text'><?php echo esc_html__( 'Dismiss this notice', 'kargom-nerede-kargo-takip' ); ?></span>
				</button>
			</div>
			<?php
		} elseif ( ! empty( $this->result ) && array_key_exists( 'success', $this->result ) ) {
			?>
			<div class='updated published'><p><?php echo esc_html( $this->result['success'] ); ?> </p></div>
			<?php
		}
		include WCK_VIEWS . '/settings.php';
	}

}
