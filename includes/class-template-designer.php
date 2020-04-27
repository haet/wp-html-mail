<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Haet_TemplateDesigner {
	private $api_base = 'whm/v3';

	function __construct(){
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_page_scripts_and_styles' ] );
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
	
	
	
	public function admin_page_scripts_and_styles($page){
		if(strpos($page, 'wp-html-mail') && ( !$_GET['tab'] || $_GET['tab'] =="template" ) ){
			
			// style out options panel like the block editor
			wp_enqueue_style( 'wp-editor' );
			//wp_enqueue_style( 'wp-block-library' );
			//wp_enqueue_style( 'wp-block-library-theme' );
			//wp_enqueue_style( 'wp-block-library' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );

			wp_enqueue_script('wp-html-mail-template-designer',  HAET_MAIL_URL.'/js/template-designer/' . ( $this->isScriptDebug() ? 'dev' : 'dist' ) . '/main.js', array( 'wp-color-picker','wp-pointer', 'wp-element', 'jquery'));
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
        $theme_options  = Haet_Mail()->get_theme_options('default');
        return new \WP_REST_Response( $theme_options );
	}
	
	public function saveThemeSettings( $request ){
		if( $request->get_params() ){
			$theme_options = $request->get_params();
			update_option('haet_mail_theme_options', $theme_options);
		}
		
		$options = $this->get_options();
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
        return site_url(rest_get_url_prefix()) . '/' . $this->api_base . '/' . $endpoint;
	}
	

	public function isScriptDebug() {
        return defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true;
    }
}

?>