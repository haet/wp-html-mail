<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Haet_TemplateDesigner {
	private $api_base = 'whm/v3';

	function __construct(){
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_page_scripts_and_styles' ] );
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );

		add_action( 'admin_notices', [ $this, 'showTemplateDesignerUpdateNotice' ] );
	}
	
	
	
	public function admin_page_scripts_and_styles($page){
		if(strpos($page, 'wp-html-mail') && ( !array_key_exists( 'tab', $_GET ) || $_GET['tab'] =="template" ) ){
			
			// style our options panel like the block editor
			wp_enqueue_style( 'wp-editor' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );

			// https://developer.wordpress.org/block-editor/packages/packages-dependency-extraction-webpack-plugin/
			$script_path = HAET_MAIL_PATH . 'js/template-designer/' . ( $this->isScriptDebug() ? 'dev' : 'dist' ) . '/main.js';
			$script_asset_path = HAET_MAIL_PATH . 'js/template-designer/' . ( $this->isScriptDebug() ? 'dev' : 'dist' ) . '/main.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require( $script_asset_path )
				: array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
			$script_url = HAET_MAIL_URL . 'js/template-designer/' . ( $this->isScriptDebug() ? 'dev' : 'dist' ) . '/main.js';

			wp_enqueue_script('wp-html-mail-template-designer', $script_url, $script_asset['dependencies'], $script_asset['version']);
			wp_localize_script('wp-html-mail-template-designer', 'mailTemplateDesigner', [
				'restUrl' 				=> $this->getRestUrl(),
				'nonce' 				=> wp_create_nonce( 'wp_rest' ),
				'fonts' 				=> $this->getAvailableFonts(),
				'templateLibraryUrl' 	=> Haet_Mail()->get_tab_url('template-library'),
				'isMultiLanguageSite' 	=> Haet_Mail()->multilanguage->is_multilanguage_site(),
				'currentLanguage' 		=> Haet_Mail()->multilanguage->get_current_language()
			]);
			wp_enqueue_media();
			wp_enqueue_editor();
		} 
	}



	public function rest_api_init() {
		register_rest_route( $this->api_base, '/themesettings', array(
            'methods' => 'GET',
            'callback' => [ $this, 'getThemeSettings' ]
		));
		
		register_rest_route( $this->api_base, '/themesettings', array(
            'methods' => 'POST',
            'callback' => [ $this, 'saveThemeSettings' ]
		));
	}


	public function getThemeSettings(){
		$theme_options = Haet_Mail()->get_theme_options('default');

        return new \WP_REST_Response( $theme_options );
	}
	
	public function saveThemeSettings( $request ){
		if( $request->get_params() ){
			$theme_options = $request->get_params();
			update_option('haet_mail_theme_options', $theme_options);
		}
		
		$options = Haet_Mail()->get_options();
		$plugin_options = Haet_Sender_Plugin::get_plugin_options();

		$preview = Haet_Mail()->get_preview( Haet_Sender_Plugin::get_active_plugins(), 'template', $options, $plugin_options, $theme_options );
		return new \WP_REST_Response( ['preview' => $preview] );
	}

	private function getAvailableFonts(){
		$fonts = Haet_Mail()->get_fonts();
		$fonts_select_options = [];
		foreach( $fonts as $value => $label ){
			$fonts_select_options[] = [
				'value'	=> $value,
				'label' => $label
			];
		}
        return $fonts_select_options;
	}



	private function getRestUrl( $endpoint = '' ) {
        return get_rest_url( null, $this->api_base . '/' . $endpoint );
	}
	

	public function isScriptDebug() {
        return defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true;
	}
	
	public function isWPVersionCompatible(){
		// our new JavaScript based editor relies on some WordPress React components available in 5.4
		return version_compare( get_bloginfo( 'version' ), '5.4', '>=' );
	}


	/**
	 * Show a notice on the backend
	 * either to tell the users to check their settings in the new editor or to tell them to better update WP to see the new editor
	 */
	public function showTemplateDesignerUpdateNotice(){
		if( $this->isWPVersionCompatible() ){
			$options = Haet_Mail()->get_options();
			if( !array_key_exists( 'user_checked_settings_in_v3', $options ) || !$options['user_checked_settings_in_v3'] ){
				?>
				<div class="notice notice-success">
					<p><?php echo sprintf( __( 'You successfully upgraded to our <strong>new email editor</strong>! Please <a href="%1$s">review your settings</a> to make sure everything still looks as expected.', 'wp-html-mail' ), get_admin_url(null,'options-general.php?page=wp-html-mail') ); ?></p>
				</div>
				<?php
			}
		}elseif( array_key_exists( 'page', $_GET ) && $_GET['page'] == "wp-html-mail" ){
			?>
			<div class="notice notice-warning">
				<p><?php _e( 'In order to use our <strong>new email editor</strong> you need to upgrade to WordPress 5.4 or higher. In the meanwhile you can still use our classic settings pages.', 'wp-html-mail' ); ?></p>
			</div>
			<?php
		}
	}
}

?>