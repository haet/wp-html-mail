<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

class Haet_ContentEditor {

	private $api_base = 'whm/v3';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts_and_styles' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}



	public function admin_page_scripts_and_styles( $page ) {
		if ( strpos( $page, 'wp-html-mail' ) && ( ! array_key_exists( 'tab', $_GET ) || 'content-editor' === $_GET['tab'] ) ) {
			wp_enqueue_style( 'haet_mail_admin_style', HAET_MAIL_URL . '/css/style.css', array(), '3.0' );
			// Editor default styles.
			wp_enqueue_style( 'wp-format-library' );
			
			// Custom styles.
			wp_enqueue_style(
				'wp-html-mail-content-editor-styles', // Handle.
				HAET_MAIL_URL . '/css/content-editor.css', // Block editor CSS.
				array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
				filemtime( HAET_MAIL_PATH . '/css/content-editor.css' )
			);

			$script_path       = HAET_MAIL_PATH . 'js/content-editor/build/index.js';
			$script_asset_path = HAET_MAIL_PATH . 'js/content-editor/build/index.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require $script_asset_path
				: array(
					'dependencies' => array(),
					'version'      => filemtime( $script_path ),
				);
			$script_url        = HAET_MAIL_URL . 'js/content-editor/build/index.js';

			wp_enqueue_script( 
				'wp-html-mail-content-editor',
				$script_url,
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);

			$enqueue_data = array(
				'translations'     => array(),
				'placeholders'     => array(),
				'placeholder_menu' => array(),
			);
			$enqueue_data = apply_filters( 'haet_mail_enqueue_js_data', $enqueue_data );

			wp_localize_script(
				'wp-html-mail-content-editor',
				'haet_mb_data',
				$enqueue_data
			);

			// Inline the Editor Settings.
			$settings = $this->get_block_editor_settings();
			wp_add_inline_script( 'wp-html-mail-content-editor', 'window.mailContentEditorSettings = ' . wp_json_encode( $settings ) . ';' );
		}
	}


	private function get_block_editor_settings(){
		return [];
	}


	public function rest_api_init() {
		// register_rest_route(
		// 	$this->api_base,
		// 	'/themesettings',
		// 	array(
		// 		'methods'             => 'GET',
		// 		'callback'            => array( $this, 'get_theme_settings' ),
		// 		'permission_callback' => function () {
		// 			return current_user_can( 'manage_options' );
		// 		},
		// 	)
		// );

		// register_rest_route(
		// 	$this->api_base,
		// 	'/themesettings',
		// 	array(
		// 		'methods'             => 'POST',
		// 		'callback'            => array( $this, 'save_theme_settings' ),
		// 		'permission_callback' => function () {
		// 			return current_user_can( 'manage_options' );
		// 		},
		// 	)
		// );
	}
}

?>
