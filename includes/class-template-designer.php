<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

require HAET_MAIL_PATH . 'includes/class-content-editor.php';

class Haet_TemplateDesigner {

	private $api_base = 'whm/v3';
	private $contenteditor;

	public function __construct() {
		$this->contenteditor = new Haet_ContentEditor();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts_and_styles' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}



	public function admin_page_scripts_and_styles( $page ) {
		if ( strpos( $page, 'wp-html-mail' ) && ( ! array_key_exists( 'tab', $_GET ) || $_GET['tab'] == 'template' ) ) {

			// style our options panel like the block editor.
			wp_enqueue_style( 'wp-editor' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );
			wp_enqueue_media();

			$this->contenteditor->load();

			$script_path       = HAET_MAIL_PATH . 'template-designer/build/index.js';
			$script_asset_path = HAET_MAIL_PATH . 'template-designer/build/index.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require $script_asset_path
				: array(
					'dependencies' => array(),
					'version'      => filemtime( $script_path ),
				);
			$script_url        = HAET_MAIL_URL . 'template-designer/build/index.js';

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
					'editorSettings'      => [
						'editor' => $this->contenteditor->get_editor_settings(),
						'iso' => [
							'blocks' => [
								'allowBlocks' => $this->contenteditor->get_allowed_blocks(),
							],
							'sidebar' => ['inspector' => true, 'inserter' => true],
							'toolbar' => [
								'inspector' => true, 
								'navigation' => true,
							],
							'moreMenu' => [
								'editor' => true,
								'topToolbar' => true,
							],
						],
					]
				)
			);
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

		register_rest_route(
			$this->api_base,
			'/themecss/(?P<post_id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this->contenteditor, 'output_template_styles_for_contenteditor' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/plugins',
			array(
				'methods'             => 'GET',
				'callback'            => 'Haet_Sender_Plugin::get_plugins_for_rest',
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);


		register_rest_route(
			$this->api_base,
			'/pluginsettings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_pluginsettings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/pluginsettings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_pluginsettings' ),
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


	/**
	 * Get non-visual settings like sender, test mode...
	 */
	public function get_settings() {
		$options = Haet_Mail()->get_options();

		return new \WP_REST_Response( $options );
	}



	/**
	 * Save non-visual settings like sender, test mode...
	 *
	 * @param object $request Rest Request object.
	 */
	public function save_settings( $request ) {
		if ( $request->get_params() ) {
			$new_options = $request->get_params();
		} else {
			$new_options = [ ];
		}

		$old_options = Haet_Mail()->get_options();
		$new_options = Haet_Mail()->validate_options( $new_options );

		$options = array_merge( $old_options, $new_options );

		update_option( 'haet_mail_options', $options );

		return new \WP_REST_Response( $options );
	}



	/**
	 * Get plugin settings.
	 */
	public function get_pluginsettings() {
		return new \WP_REST_Response( Haet_Sender_Plugin::get_plugin_options() );
	}



	/**
	 * Save plugin settings.
	 *
	 * @param object $request Rest Request object.
	 */
	public function save_pluginsettings( $request ) {
		if ( $request->get_params() ) {
			$new_options = $request->get_params();
			$old_options = Haet_Sender_Plugin::get_plugin_options();
			$options = Haet_Sender_Plugin::save_plugin_options( $old_options, $new_options );
		} else {
			$options = Haet_Sender_Plugin::get_plugin_options();
		}

		return new \WP_REST_Response( $options );
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
}

?>
