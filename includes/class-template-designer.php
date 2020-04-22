<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Haet_TemplateDesigner {
	private $api_base = 'whm/v3';

	function __construct(){
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_page_scripts_and_styles' ] );
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
	
	
	
	public function admin_page_scripts_and_styles($page){
		if(strpos($page, 'wp-html-mail')){
			//wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script('haet_mail_admin_script',  HAET_MAIL_URL.'/js/admin_script.js', array( 'wp-color-picker','jquery-ui-dialog','wp-pointer','jquery'));
			//wp_enqueue_style('haet_mail_admin_style',  HAET_MAIL_URL.'/css/style.css');
			//wp_enqueue_style (  'wp-jquery-ui-dialog');
			
			// style out options panel like the block editor
			wp_enqueue_style( 'wp-editor' );
			//wp_enqueue_style( 'wp-block-library' );
			//wp_enqueue_style( 'wp-block-library-theme' );
			//wp_enqueue_style( 'wp-block-library' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'forms' );

			wp_enqueue_script('wp-html-mail-template-designer',  HAET_MAIL_URL.'/js/template-designer/dist/bundle.js', array( 'wp-color-picker','wp-pointer', 'wp-element', 'jquery'));
			wp_localize_script('wp-html-mail-template-designer', 'mailTemplateDesigner', [
				'restUrl' => $this->getRestUrl(),
				'nonce' => wp_create_nonce( 'wp_rest' )
			]);
			wp_enqueue_media();
		} 
	}



	public function rest_api_init() {
		register_rest_route( $this->api_base, '/themesettings', array(
            'methods' => 'GET',
            'callback' => [ $this, 'getThemeSettings' ]
		));
		
		register_rest_route( $this->api_base, '/fonts', array(
            'methods' => 'GET',
            'callback' => [ $this, 'getAvailableFonts' ]
        ));
	}


	public function getThemeSettings(){
        $theme_options  = Haet_Mail()->get_theme_options('default');
        return new \WP_REST_Response( $theme_options );
	}
	

	public function getAvailableFonts(){
        return new \WP_REST_Response( Haet_Mail()->get_fonts() );
	}



	private function getRestUrl( $endpoint = '' ) {
        return site_url(rest_get_url_prefix()) . '/' . $this->api_base . '/' . $endpoint;
    }
}

?>