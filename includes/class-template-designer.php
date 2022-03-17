<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

class Haet_TemplateDesigner {

	private $api_base = 'whm/v3';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts_and_styles' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );

		add_action( 'admin_notices', array( $this, 'show_template_designer_update_notice' ) );
	}



	public function admin_page_scripts_and_styles( $page ) {
		if ( strpos( $page, 'wp-html-mail' ) && ( ! array_key_exists( 'tab', $_GET ) || $_GET['tab'] == 'template' ) ) {

			// style our options panel like the block editor
			wp_enqueue_style( 'wp-editor' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );

			// https://developer.wordpress.org/block-editor/packages/packages-dependency-extraction-webpack-plugin/
			$script_path       = HAET_MAIL_PATH . 'js/template-designer/' . ( $this->is_script_debug() ? 'dev' : 'dist' ) . '/main.js';
			$script_asset_path = HAET_MAIL_PATH . 'js/template-designer/' . ( $this->is_script_debug() ? 'dev' : 'dist' ) . '/main.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require $script_asset_path
				: array(
					'dependencies' => array(),
					'version'      => filemtime( $script_path ),
				);
			$script_url        = HAET_MAIL_URL . 'js/template-designer/' . ( $this->is_script_debug() ? 'dev' : 'dist' ) . '/main.js';

			wp_enqueue_script( 
				'wp-html-mail-template-designer',
				$script_url,
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);
			wp_localize_script(
				'wp-html-mail-template-designer',
				'mailTemplateDesigner',
				array(
					'restUrl'             => $this->get_rest_url(),
					'nonce'               => wp_create_nonce( 'wp_rest' ),
					'fonts'               => $this->get_available_fonts(),
					'templateLibraryUrl'  => Haet_Mail()->get_tab_url( 'template-library' ),
					'isMultiLanguageSite' => Haet_Mail()->multilanguage->is_multilanguage_site(),
					'currentLanguage'     => Haet_Mail()->multilanguage->get_current_language(),
					'nonce'               => wp_create_nonce( 'wp_rest' ),
				)
			);
			wp_enqueue_media();
			wp_enqueue_editor();
		}
	}



	public function rest_api_init() {
		register_rest_route(
			$this->api_base,
			'/themesettings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_theme_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/themesettings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_theme_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}


	public function get_theme_settings() {
		$theme_options = Haet_Mail()->get_theme_options( 'default' );

		return new \WP_REST_Response( $theme_options );
	}

	public function save_theme_settings( $request ) {
		if ( $request->get_params() ) {
			$theme_options = $request->get_params();
			update_option( 'haet_mail_theme_options', $theme_options );
		}

		$options        = Haet_Mail()->get_options();
		$plugin_options = Haet_Sender_Plugin::get_plugin_options();

		$preview = Haet_Mail()->get_preview( Haet_Sender_Plugin::get_active_plugins(), 'template', $options, $plugin_options, $theme_options );
		return new \WP_REST_Response( array( 'preview' => $preview ) );
	}

	private function get_available_fonts() {
		$fonts                = Haet_Mail()->get_fonts();
		$fonts_select_options = array();
		foreach ( $fonts as $value => $label ) {
			$fonts_select_options[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
		return $fonts_select_options;
	}



	private function get_rest_url( $endpoint = '' ) {
		return get_rest_url( null, $this->api_base . '/' . $endpoint );
	}


	public function is_script_debug() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;
	}

	public function is_wp_version_compatible() {
		// our new JavaScript based editor relies on some WordPress React components available in 5.4
		return version_compare( get_bloginfo( 'version' ), '5.4', '>=' );
	}


	/**
	 * Show a notice on the backend
	 * either to tell the users to check their settings in the new editor or to tell them to better update WP to see the new editor
	 */
	public function show_template_designer_update_notice() {
		if ( ! $this->is_wp_version_compatible() && array_key_exists( 'page', $_GET ) && $_GET['page'] == 'wp-html-mail' ) {
			?>
			<div class="notice notice-warning">
				<p><?php _e( 'In order to use our <strong>new email editor</strong> you need to upgrade to WordPress 5.4 or higher. In the meanwhile you can still use our classic settings pages.', 'wp-html-mail' ); ?></p>
			</div>
			<?php
		}
	}
}

?>
