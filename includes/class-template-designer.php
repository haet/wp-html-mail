<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

require trailingslashit( HAET_MAIL_PATH ) . 'includes/class-content-editor.php';
require trailingslashit( HAET_MAIL_PATH ) . 'includes/class-template-library.php';

class Haet_TemplateDesigner {

	private $api_base = 'whm/v3';
	private $contenteditor;
	private $templatelibrary;

	public function __construct() {
		//$this->contenteditor = new Haet_ContentEditor();
		$this->templatelibrary = new Haet_Template_Library();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts_and_styles' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );

		add_filter("haet_mail_enqueue_js_data", function($enqueue_data){
			$enqueue_data['restURL']  = $this->get_rest_url();
			$enqueue_data['nonce']  = wp_create_nonce( 'wp_rest' );

			return $enqueue_data;
		});
	}



	public function admin_page_scripts_and_styles( $page ) {
		if ( strpos( $page, 'wp-html-mail' ) && ( ! array_key_exists( 'tab', $_GET ) || $_GET['tab'] == 'template' ) ) {

			// style our options panel like the block editor.
			wp_enqueue_style( 'wp-editor' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );
			wp_enqueue_media();

			if( $this->contenteditor )
				$this->contenteditor->load();

			$script_path       = trailingslashit( HAET_MAIL_PATH ) . 'template-designer/build/index.js';
			$script_asset_path = trailingslashit( HAET_MAIL_PATH ) . 'template-designer/build/index.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require $script_asset_path
				: array(
					'dependencies' => ['wp-element'],
					'version'      => filemtime( $script_path ),
				);
			$script_url        = trailingslashit( HAET_MAIL_URL ) . 'template-designer/build/index.js';

			$react_component_files = $this->get_react_compontent_files();
			if( is_array( $react_component_files ) && count( $react_component_files ) ){
				foreach( $react_component_files as $plugin_name => $react_component_file ){
					wp_enqueue_script( 
						'wp-html-mail-' . $plugin_name,
						$react_component_file['url'],
						[],
						$react_component_file['version'],
						true
					);

					$script_asset['dependencies'][] = 'wp-html-mail-' . $plugin_name;
				}
			}
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
					'templateLibraryUrl'  => Haet_Mail()->get_tab_url( 'template-library' ),
					'isMultiLanguageSite' => Haet_Mail()->multilanguage->is_multilanguage_site(),
					'currentLanguage'     => Haet_Mail()->multilanguage->get_current_language(),
					'editorSettings'      => (
						$this->contenteditor ?
							[
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
							:
							null
					)
				)
			);
			wp_enqueue_editor();
		}
	}

	/**
	 * Get a list of react components from plugins to be loaded.
	 */
	private function get_react_compontent_files(){
		$plugins = Haet_Sender_Plugin::get_plugins_for_rest();
		$js_files = [ ];
		foreach ( $plugins as $plugin_name => $plugin ){
			if( $plugin['active'] && $plugin['has_addon'] && $plugin['is_addon_active'] && $plugin ['react_component'] ){
				$js_files[$plugin_name] = $plugin ['react_component'];
			}
		}
		$js_files = apply_filters( 'haet_mail_react_components', $js_files );
		return $js_files;
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
				'callback'            => function(){
					return new \WP_REST_Response( array_values( Haet_Sender_Plugin::get_plugins_for_rest() ) );
				},
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

		register_rest_route(
			$this->api_base,
			'/advancedactions',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_advanced_actions' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/fonts',
			array(
				'methods'             => 'GET',
				'callback'            => function(){
					return new \WP_REST_Response( $this->get_available_fonts() );
				},
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/templatelibrary',
			array(
				'methods'             => 'GET',
				'callback'            => function(){
					return new \WP_REST_Response( $this->templatelibrary->get_templates() );
				},
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$this->api_base,
			'/sendtestmail',
			array(
				'methods'             => 'POST',
				'callback'            => array( Haet_Mail(), 'send_test' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		do_action( 'haet_mail_rest_api_init', $this->api_base );
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


	/**
	 * Advanced actions are some additional actions on tab "advanced" which are formerly executed in PHP and called via URL parameters.
	 */
	public function get_advanced_actions() {
		$actions = [
			'reset' => [
				'buttons' => [
					[ 
						'caption'	=> __( 'Delete design settings', 'wp-html-mail' ),
						'href'		=> add_query_arg( 'advanced-action', 'delete-design', Haet_Mail()->get_tab_url() ),
						'disabled'	=> false,
						'confirm'	=> __('Are you sure? This can not be undone!', 'wp-html-mail')
					],
					[ 
						'caption'	=> __( 'Delete ALL settings', 'wp-html-mail' ),
						'href'		=> add_query_arg( 'advanced-action', 'delete-all', Haet_Mail()->get_tab_url() ),
						'disabled'	=> false,
						'confirm'	=> __('Are you sure? This can not be undone!', 'wp-html-mail')
					],
				]
			],
			'debug' => [
				'buttons'	=> [],
				'description' => ''
			],
			'template' => [],
		];

		$template_description = '';
		if ( file_exists( get_stylesheet_directory() . '/wp-html-mail/template.html' ) )
			$template_description .= '<p class="description">' . __( 'You already have a custom template. If you create a new one the existing template will be backed up.', 'wp-html-mail' ) . '</p>'; 
		
		$theme_is_writable = is_writable( get_stylesheet_directory() );
		if ( ! $theme_is_writable )
			$template_description .= '<p class="description">' . __( 'WARNING: Your theme directory is not writable by the server. Please change the permission to allow us to create the mail template.', 'wp-html-mail' ) . '</p>';
		$template_description .= '<p class="description">' . __( 'Customize your mail template as far as you can. Then click this button to export the template to your theme directory for further modifications.<br>The template will be created in <strong>wp-content/YOUR-THEME/wp-html-mail/template.html</strong>', 'wp-html-mail' ) . '</p>';
		$template_description .= '<p class="description">' . __( '<strong>Only use this feature if you know what you are doing!</strong><br>From this point you have to continue your work in HTML and CSS code.', 'wp-html-mail' ) . '</p>'; 
		$template_description .= '<p class="description">' . __( "If you don't use a child theme and need an update save place to store your email template you can also copy the template file from the plugin to <strong>wp-content/uploads/wp-html-mail/template.html</strong>.", 'wp-html-mail' ) . '</p>';

		$actions['template'] = [
			'buttons' => [
				[ 
					'caption'	=> __( 'create template file in my theme folder', 'wp-html-mail' ),
					'href'		=> add_query_arg( 'advanced-action', 'create-template-file', Haet_Mail()->get_tab_url() ),
					'disabled'	=> false,
					'confirm'	=> __('Are you sure you want to write your own code?', 'wp-html-mail')
				],
			],
			'description' => $template_description
		];
		$actions = apply_filters( 'haet_mail_register_advanced_actions', $actions );
		return new \WP_REST_Response( $actions );
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
