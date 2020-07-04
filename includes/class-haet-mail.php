<?php if ( ! defined( 'ABSPATH' ) ) exit;

require HAET_MAIL_PATH . 'includes/class-multilanguage.php';
require HAET_MAIL_PATH . 'includes/class-template-designer.php';
require HAET_MAIL_PATH . 'includes/class-template-library.php';

final class Haet_Mail {
	
	private static $instance;
	public $multilanguage;
	private $templatedesigner;
	
	public static function instance(){
		if (!isset(self::$instance) && !(self::$instance instanceof Haet_Mail)) {
			self::$instance = new Haet_Mail();
		}

		return self::$instance;
	}


	public function __construct(){
		$this->multilanguage = new Haet_Multilanguage();
		$this->templatedesigner = new Haet_TemplateDesigner();
		add_action( 'plugins_loaded', 'Haet_Sender_Plugin::hook_plugins', 30 );

		add_action( 'admin_notices', array( $this, 'maybe_show_testmode_warning' ) );
	}
	
	
	public function get_default_options() {
		return array(
			'fromname' 				=> 	get_bloginfo('name'),
			'fromaddress'			=> 	get_bloginfo('admin_email'),
			'testmode'				=>	false,
			'testmode_recipient'	=>	'',
			'use_classic_template_editor' => false
		);
	}

	/**
	 * send a test message to the given email address
	 * TODO: return status
	 */
	function send_test() {
		$email = $_POST['email'];
		echo $email;		
		wp_mail( 
			$email, 
			'WP HTML mail - TEST', 
			$this->get_demo_content(),
			'Content-Type: text/html'
		);
		wp_die();
	}

	function get_default_theme_options() {	
		return json_decode('{
			"background": "#ffffff",
			"contentbackground": "#f3f3f3",
			"headertext": "' . get_bloginfo('name') . '",
			"headerfont": "Trebuchet, sans-serif",
			"headeralign": "center",
			"headerfontsize": "21",
			"headerbold": "0",
			"headeritalic": 0,
			"headertexttransform": "uppercase",
			"headerbackground": "#ffffff",
			"headercolor": "#595959",
			"headerpaddingtop": "50",
			"headerpaddingright": 0,
			"headerpaddingbottom": 31,
			"headerpaddingleft": 0,
			"header_spacer": 10,
			"headerimg_placement": "just_text",
			"headerimg": "",
			"headerimg_width": "600",
			"headerimg_height": "1",
			"headerimg_alt": "",
			"headerimg_align": "",
			"headlinefont": "Trebuchet, sans-serif",
			"headlinealign": "left",
			"headlinefontsize": "19",
			"headlinebold": 0,
			"headlineitalic": 0,
			"headlinetexttransform": "none",
			"headlinecolor": "#343434",
			"subheadlinefont": "Trebuchet, sans-serif",
			"subheadlinealign": "left",
			"subheadlinefontsize": "18",
			"subheadlinebold": 0,
			"subheadlineitalic": 0,
			"subheadlinetexttransform": "none",
			"subheadlinecolor": "#343434",
			"textfont": "Helvetica, Arial, sans-serif",
			"textalign": "left",
			"textfontsize": 14,
			"textbold": 0,
			"textitalic": 0,
			"textcolor": "#878787",
			"linkcolor": "#777777",
			"linkbold": 0,
			"linkitalic": 0,
			"linktexttransform": "none",
			"linkunderline": 1,
			"footer": "<p><span style=\"color: #595959;\">\u00a0<\/span><\/p>\n<p style=\"text-align: center;\"><span style=\"color: #808080; font-family: Helvetica, Arial, sans-serif;\"><span style=\"font-size: 12px;\">WordPress email template created with <a style=\"color: #808080;\" href=\"https:\/\/wordpress.org\/plugins\/wp-html-mail\/\" target=\"_blank\" rel=\"noopener\">WP HTML Mail<\/a><\/span><\/span><br \/><span style=\"font-size: 12px; font-family: Helvetica, Arial, sans-serif; color: #808080;\"><a style=\"color: #808080;\" href=\"' . get_home_url() . '\">' . str_replace( 'http://', '', str_replace( 'https://', '', untrailingslashit( get_home_url() ) ) ) . '<\/a><\/span><\/p>",
			"footerbackground": "#ffffff",
			"headertexttranform": "none",
			"headlinetexttranform": "none",
			"subheadlinetexttranform": "none",
			"linktexttranform": "none"
		}',true);
	}



	function get_options() {
		$options = $this->get_default_options();
		 
		$haet_mail_options = get_option('haet_mail_options');
		if (!empty($haet_mail_options)) {
			foreach ($haet_mail_options as $key => $option)
				$options[$key] = $option;
		}				
		update_option('haet_mail_options', $options);
		return $options;
	}

	function get_theme_options($theme) {
		$options = $this->get_default_theme_options();
		 
		$haet_mail_options = get_option('haet_mail_theme_options');

		$haet_mail_options = $this->init_headerimg_placement( $haet_mail_options );
		if (!empty($haet_mail_options)) {
			foreach ($haet_mail_options as $key => $option)
				$options[$key] = $option;
		}				
		update_option('haet_mail_theme_options', $options);
		return $options;
	}
	
	function admin_page_scripts_and_styles($page){
		if(strpos($page, 'wp-html-mail')){
			$plugin_data = get_plugin_data( HAET_MAIL_PATH.'/wp-html-mail.php' );
            
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'haet_mail_admin_script',  HAET_MAIL_URL.'/js/admin_script.js', [ 'wp-color-picker','jquery-ui-dialog','wp-pointer','jquery' ], $plugin_data['Version'] );
			wp_enqueue_style( 'haet_mail_admin_style',  HAET_MAIL_URL.'/css/style.css', [], $plugin_data['Version'] );
			wp_enqueue_style ( 'wp-jquery-ui-dialog' );
			wp_enqueue_media();
		}
	}


	function admin_page() {
		add_options_page( __('Email','wp-html-mail'), __('Email template','wp-html-mail'), 'manage_options', 'wp-html-mail', array(&$this, 'print_admin_page') );
	}




	private function process_admin_page_actions(){
		do_action( 'haet_mail_plugin_reset_actions' );

		if( array_key_exists( 'advanced-action', $_GET ) ){
			switch ($_GET['advanced-action']) {
				case 'delete-design':
					delete_option('haet_mail_theme_options');
					echo '<div class="updated"><p><strong>';
							_e('Settings deleted.', 'wp-html-mail');
					echo '</strong></p></div>';	
					break;

				case 'delete-all':
					delete_option('haet_mail_theme_options');
					delete_option('haet_mail_options');
					delete_option('haet_mail_plugin_options');
					echo '<div class="updated"><p><strong>';
							_e('Settings deleted.', 'wp-html-mail');
					echo '</strong></p></div>';	
					break;
			}
		}
	}



	function print_admin_page(){
		$options = $this->get_options();
		$this->process_admin_page_actions();
		
		$theme_options = $this->get_theme_options('default');

		$plugin_options = Haet_Sender_Plugin::get_plugin_options();
		if( isset($_POST['enable_import_theme_options']) && $_POST['enable_import_theme_options'] && isset($_POST['import_theme_options']) ){
			$theme_options = $this->import_theme_options( $_POST['import_theme_options'], $theme_options );
		}else{
			if(isset($_POST['haet_mail']) )
				$options = $this->save_options($options);
			if(isset($_POST['haet_mail_theme']) )
				$theme_options = $this->save_theme_options($theme_options);
			if(isset($_POST['haet_mail_plugins']))
				$plugin_options = Haet_Sender_Plugin::save_plugin_options($plugin_options);
		}
		if(isset($_POST['haet_mail']) || isset($_POST['haet_mail_theme']) || isset($_POST['haet_mail_plugins'])){
			echo '<div class="updated"><p><strong>';
					_e('Settings Updated.', 'wp-html-mail');
			echo '</strong></p></div>';	
		} 

		$is_able_to_use_new_editor = $this->templatedesigner->isWPVersionCompatible();

		// we show a notice to the users until they have called the settings at least once in order to make sure they checked the updated template
		if( $is_able_to_use_new_editor 
			&& ( 
				!array_key_exists( 'user_checked_settings_in_v3', $options ) 
				|| !$options['user_checked_settings_in_v3'] 
			)){
			$options['user_checked_settings_in_v3'] = true;
			update_option('haet_mail_options', $options);
		}

		$use_old_editor = !$is_able_to_use_new_editor || ( isset( $options['use_classic_template_editor'] ) && $options['use_classic_template_editor'] );

		if(array_key_exists('tab', $_GET))
			$tab = $_GET['tab'];
		else
			$tab = $use_old_editor ? 'general' : 'template';

		// template library
		if( $tab == 'template-library' ){
			$template_library = new Haet_Template_Library();
		
			if( isset($_POST['haet_mail_import_template_url']) && filter_var($_POST['haet_mail_import_template_url'], FILTER_VALIDATE_URL) !== FALSE ){
				$theme_options = $this->get_theme_options('default');
				$theme_options = $template_library->import_template( $_POST['haet_mail_import_template_url'], $theme_options, $this->multilanguage );
				if( $theme_options )
					wp_redirect( remove_query_arg( 'tab' ) );
			}

			include HAET_MAIL_PATH.'views/admin/template-library.php';
			return; // not further execution if we are on template library
		}
		
		$active_plugins = Haet_Sender_Plugin::get_active_plugins();
		$available_plugins = Haet_Sender_Plugin::get_available_plugins();
		$plugin_options = Haet_Sender_Plugin::get_plugin_options();
		
	
		add_filter( 'tiny_mce_before_init', array(&$this,'customize_editor'),1000 );  

		$fonts = $this->get_fonts();
		
		if(isset($_POST['haet_mail_create_template']) && $_POST['haet_mail_create_template']==1 )
			$this->create_custom_template();

		$template = $this->get_preview( $active_plugins, $tab, $options, $plugin_options, $theme_options );

		if( $use_old_editor ){
			$tabs = array(
				'general' 	=>  __('General','wp-html-mail'),
				'header' 	=>  __('Header','wp-html-mail'),
				'content' 	=>  __('Content','wp-html-mail')
			);
			foreach ($active_plugins as $plugin) {
				if ( method_exists( $plugin['class'], 'settings_tab' ) )
					$tabs[ $plugin['name'] ] =  $plugin['display_name'];
			}

			$tabs['footer']		=  __('Footer','wp-html-mail');
		}else{
			$tabs = array(
				'template' 	=>  __('Template','wp-html-mail'),
				'sender' 	=>  __('Sender','wp-html-mail'),
			);
			foreach ($active_plugins as $plugin) {
				if ( method_exists( $plugin['class'], 'settings_tab' ) )
					$tabs[ $plugin['name'] ] =  $plugin['display_name'];
			}			
		}
		// $tabs['webfonts'] 	=  __('Webfonts', 'wp-html-mail');
		$tabs['plugins']	=  __('Plugins','wp-html-mail');

		$tabs['advanced']	=  __('Advanced','wp-html-mail');

		include HAET_MAIL_PATH.'views/admin/settings.php';
	
	}



	public function get_demo_content(){
		return '<h1>Lorem ipsum dolor sit amet</h1>
			<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed <a href="#">diam nonumy</a> eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.<br>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
			<h2>Sed diam nonumy eirmod tempor</h2>
			<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>';
	}



	public function get_preview( $active_plugins, $tab, $options, $plugin_options, $theme_options ){
		foreach ($active_plugins as $plugin) {
			if ( $plugin['name'] == $tab ){
				$sender_plugin = $plugin['class']::request_preview_instance();
				$sender_plugin->current_plugin = $plugin;
			}
		}

		$template = $this->get_template($theme_options);
		if( isset($sender_plugin) ){
			$template = str_replace('###plugin-class###','plugin-'.$sender_plugin->get_plugin_name(), $template);
			$template = $sender_plugin->modify_template($template);
		}

		$demo_content = apply_filters( 'haet_mail_demo_content' , $this->get_demo_content(), $options, $plugin_options, $tab );
		if( isset( $sender_plugin ) )
			$demo_content = $sender_plugin->modify_content($demo_content);


		if( $this->is_mailbuilder_message( $demo_content ) )
			$template = str_replace('{#mailcontent#}',$demo_content,$template);
		else
			$template = str_replace('{#mailcontent#}', $this->wrap_in_padding_container( $demo_content ),$template);

		if( isset( $sender_plugin ) ){
			$template = $sender_plugin->modify_styled_mail($template);
		}

		$template = apply_filters( 'haet_mail_modify_styled_mail', $template );

		return $this->prepare_email_for_delivery($template);
	}

	function save_options($saved_options){    
		$new_options = $_POST['haet_mail'];
		$options = array_merge($saved_options,$new_options);
		if(isset($_POST['reload_haet_mailtemplate'])){
			$options = $this->get_default_theme_options();
		}

		update_option('haet_mail_options', $options);
		return $options;
	}

	function save_theme_options($saved_options){    
		$new_options = $_POST['haet_mail_theme'];

		$options = array_merge($saved_options,$new_options);
		if(isset($_POST['reload_haet_mailtemplate'])){
			$options = $this->get_default_theme_options();
		}
		

		update_option('haet_mail_theme_options', $options);
		
		return $options;
	}



	private function import_theme_options( $new_options, $saved_options ){
		$new_options = json_decode( stripslashes( $new_options ), true );
		
		if( $new_options ){
			$options = array_merge($saved_options,$new_options);

			update_option('haet_mail_theme_options', $options);
		}else{
			$options = $saved_options;
		}
		
		return $options;
	}



	/**
	*	create a template file in the active theme directory
	*	
	**/
	function create_custom_template(){
		$theme_path = get_stylesheet_directory();
		if(is_writable($theme_path)){
			if(!file_exists($theme_path.'/wp-html-mail'))
				mkdir($theme_path.'/wp-html-mail');
			if(file_exists($theme_path.'/wp-html-mail/template.html'))
				rename($theme_path.'/wp-html-mail/template.html',$theme_path.'/wp-html-mail/template-backup-'.date("Y-m-d_H-i-s").'.html');
			file_put_contents($theme_path.'/wp-html-mail/template.html',$this->load_template_file('default'));
			chmod($theme_path.'/wp-html-mail/template.html',0777);
		}
	}

	
	//add_filter('tiny_mce_before_init', array(&$this,'customizeEditor'));
	// Callback function to filter the MCE settings
	function customize_editor( $initArray ) {    
		$initArray['block_formats'] = 'Headline=h1;Text=p';
		$initArray['fontsize_formats'] = "11px 12px 13px 14px 16px 18px 21px 23px 25px 30px 35px 40px";

		$fonts = $this->get_fonts();
		$initArray['font_formats'] = "";
		foreach ($fonts as $font => $display_name) {
			$initArray['font_formats'] .= "$display_name=$font;";
		}
		$initArray['font_formats'] = trim($initArray['font_formats'],';');
		//$initArray['font_formats'] = "Arial=arial,helvetica, sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n";
		$initArray['toolbar1'] = 'fontselect,fontsizeselect,forecolor,|,bold,italic,|,alignleft,aligncenter,alignright,|,pastetext,removeformat,|,undo,redo,|,link,unlink,|';
		$initArray['toolbar2'] = '';

		return $initArray;  
	}


	private function is_mailbuilder_message( $content ){
		$email_name = '';
		preg_match("/\<!--mailbuilder\[(.*)\]-->/mU", $content, $email_name );
		return ( is_array( $email_name ) && count( $email_name ) > 1 );
	}



	function style_mail($email){
		$options = $this->get_options();
		$theme_options = $this->get_theme_options('default');
		$template = $this->get_template($theme_options);
  
		$sender_plugin = Haet_Sender_Plugin::detect_plugin($email);
		if(!$sender_plugin)
			$use_template = true;
		else
			$use_template = $sender_plugin->use_template();
		

		$use_template = apply_filters( 'haet_mail_use_template', $use_template, array('to' => $email['to'], 'subject' => $email['subject'], 'message' => $email['message'], 'headers' => $email['headers'], 'attachments' => $email['attachments'], 'sender_plugin' => ($sender_plugin?$sender_plugin->get_plugin_name():null)) );

		if($use_template){
			// plain text or no content type
			$headers_string = $email['headers'];
			if( is_array( $headers_string ) )
				$headers_string =  implode( "\n", $headers_string );

			// check the content type passed via wp_mail and the 
			// content type passed via filter. If one them is text/html 
			// the sender already did his work and we don't have to escape 
			// the content anymore.
			$is_plaintext = ( 
					( 
						stripos( $headers_string, 'Content-Type:' ) === false 
						|| stripos( $headers_string, 'Content-Type: text/plain' ) !== false 
					)
					&& apply_filters( 'wp_mail_content_type', 'text/plain' ) != 'text/html'
				);

			if( $sender_plugin ){
				$template = str_replace('###plugin-class###','plugin-'.$sender_plugin->get_plugin_name(), $template);
				
				if( $is_plaintext )
					$email['message'] = $sender_plugin->modify_content_plain($email['message']);
				else
					$email['message'] = $sender_plugin->modify_content($email['message']);

				
				$template = $sender_plugin->modify_template($template);
			}else{
				if( $is_plaintext && !( isset( $options['invalid_contenttype_to_html'] ) && $options['invalid_contenttype_to_html'] ) ) {
					$email['message'] = htmlentities($email['message']);
				
					$email['message'] = wpautop($email['message']);
				}elseif( $is_plaintext && isset( $options['invalid_contenttype_to_html'] ) && $options['invalid_contenttype_to_html'] ) {
					// user has explicitly decided to interpret text as html
					// but if the text doesn't contain any html tags but \n it get's merged into a single line
					// see: https://wordpress.org/support/topic/password-recover-link/
					if( !preg_match('/<[^h][^>]*>/m', $email['message'], $output_array) 
						&& strpos( $email['message'], "\n" ) !== false ){
						// found no HTML tags but line breaks
						$email['message'] = wpautop($email['message']);
						//replace links like <http://... with <a href="http://..."
						// removed in 2.9.1 because we should not convert plaintext to html 
						// added again in 2.9.2 because of the password reset link
						$email['message'] = preg_replace('/\<http(.*)\>/', '<a href="http$1">http$1</a>', $email['message']); 
					}

				}
			}

			// drop <style> blocks in content
			$email['message'] = preg_replace('/\<style(.*)\<\/style\>/simU', '', $email['message']);
			
			// mb_substr instead of substr suggested here: https://wordpress.org/support/topic/encoding-problem-on-woocommerce/
			$pre_header_text = mb_substr( strip_tags( $email['message'] ), 0, 200 );
			

			if( $this->is_mailbuilder_message( $email['message'] ) )
				$email['message'] = str_replace('{#mailcontent#}',$email['message'],$template);
			else
				$email['message'] = str_replace('{#mailcontent#}',$this->wrap_in_padding_container( $email['message'] ),$template);

			$pre_header_text = apply_filters( 'haet_mail_preheader', $pre_header_text, $email );
			$email['message'] = str_replace('###pre-header###', $pre_header_text, $email['message']);


			$email['message'] = str_replace('{#mailsubject#}',$email['subject'],$email['message']);

			$email['message'] = stripslashes(str_replace('\\&quot;','',$email['message']));

			if( isset($sender_plugin) ){
				$email['message'] = $sender_plugin->modify_styled_mail($email['message']);
			}

			$email['message'] = apply_filters( 'haet_mail_modify_styled_mail', $email['message'] );

			$email['message'] = do_shortcode( $email['message'] );

			$email['message'] = $this->prepare_email_for_delivery($email['message']);
		}
		
		$use_sender = !$sender_plugin || $sender_plugin->use_sender();
		$use_sender = apply_filters( 'haet_mail_use_sender', $use_sender, array('to' => $email['to'], 'subject' => $email['subject'], 'message' => $email['message'], 'headers' => $email['headers'], 'attachments' => $email['attachments'], 'sender_plugin' => ($sender_plugin?$sender_plugin->get_plugin_name():null)) );

		if ( $use_sender ){
			add_filter( 'wp_mail_from', array($this,'set_mail_from_address'), 100 );
			add_filter( 'wp_mail_from_name', array($this,'set_mail_sender_name'), 100 );
		}

		if ( $use_template ){
			add_filter('wp_mail_content_type',array($this, 'set_mail_content_type'),20);
            add_filter('wp_mail_charset',array($this, 'set_mail_charset'),20);
        }
        

        if( $use_template && $sender_plugin && $sender_plugin->is_header_hidden() )
            $email['message'] = preg_replace('/(.*)<!--header-table-->.*<!--\/header-table-->(.*)/smU', '$1 $2', $email['message']);
        if( $use_template && $sender_plugin && $sender_plugin->is_footer_hidden() )
            $email['message'] = preg_replace('/(.*)<!--footer-table-->.*<!--\/footer-table-->(.*)/smU', '$1 $2', $email['message']);


		// Field values in Ninja Forms and of course also in other plugins are encoded and otherwise not suitable for subjects
		$email['subject'] = html_entity_decode( $email['subject'] );

		$email = $this->add_attachments( $email );

		if( $this->is_debug_mode() ){
			$debug_filename = trailingslashit( get_temp_dir() ) . 'debug-' . uniqid() . '.txt';
            
            $debug = print_r( $email, true );
            $debug .= '=====POST:'.print_r($_POST,true)."\n\n";
            $debug .= '=====GET:'.print_r($_GET,true)."\n\n";
            $debug .= 'SENDER-PLUGIN: '.print_r($sender_plugin,true)."\n\n";
            $debug .= 'ACTIVE-PLUGINS: <pre>'.print_r(Haet_Sender_Plugin::get_active_plugins(),true)."\n\n";
            
            file_put_contents( $debug_filename, $debug );
			$email['attachments'][] = $debug_filename;
		}

		if(	isset( $options['testmode'] ) 
			&& isset( $options['testmode_recipient'] ) 
			&& $options['testmode'] 
			&& is_email( trim( $options['testmode_recipient'] ) ) )
			$email['to'] = trim( $options['testmode_recipient'] );

		return $email;
	}
	

	function style_mail_wpmandrill($email){
		$email['message'] = $email['html'];
		$email = $this->style_mail( $email );
		$email['html'] = $email['message'];
		return $email;
	}


	private function add_attachments( $email ){
		if( $this->is_mailbuilder_message( $email['message'] ) ){
			$email_name = '';
			preg_match("/<!--mailbuilder\[(.*)\]-->/mU", $email['message'], $email_name );
			$email_name = $email_name[1];
			
			$email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
			if( $email_id ){
				$attachment_ids_json = get_post_meta( $email_id, 'mailbuilder_attachments', true );
				$attachment_ids_array = json_decode( $attachment_ids_json );
				if( is_array( $attachment_ids_array ) && count( $attachment_ids_array ) > 0 ){
					foreach ( $attachment_ids_array as $attachment_id ) {
						$email['attachments'][] = get_attached_file( $attachment_id );
					}
				}
			}	
		}
	
		return $email;
	}



	function set_mail_content_type(){
		return "text/html";
	}

	function set_mail_charset(){
		return "UTF-8";
	}

	function set_mail_sender_name($name){ 
		$options = $this->get_options();
		$sender = stripslashes( $options['fromname'] ); 	
		return $sender; 
	}
	
	function set_mail_from_address($email){
		$options = $this->get_options();
		$sender = $options['fromaddress']; 	
		return $sender; 
	}


	public function load_template_file( $template_name ) {
		$template_path = locate_template( 'wp-html-mail/template.html');
		$upload_dir = wp_upload_dir();
		$custom_template_path = trailingslashit( $upload_dir['basedir'] ) . 'wp-html-mail/template.html';
		$haet_path = HAET_MAIL_PATH . 'views/template/template.html';

		if ( !file_exists($template_path) ) {
			$template_path = $custom_template_path;
			if (!file_exists($custom_template_path)) {
				$template_path = HAET_MAIL_PATH . 'views/template/template.html';
			}
		}

		if ( is_file( $template_path ) ) {
			ob_start();
			require( $template_path );
			$template_content = ob_get_clean();
		} else {
			$template_content = false;
		}
		return $template_content;
	}



	private function get_header( $options ){
		$headerimg_placement = $options['headerimg_placement'];
		if( !$headerimg_placement ) 
			$headerimg_placement = 'replace_text';

		$headertext_field_key = $this->multilanguage->get_translateable_theme_options_key( $options, 'headertext' );
		if( array_key_exists( $headertext_field_key, $options ) )
			$headertext = $options[$headertext_field_key];
		else
			$headertext = $options['header'];
		$headertext = str_replace( '  ', ' &nbsp;', $headertext );

		$headerimg_field_key = $this->multilanguage->get_translateable_theme_options_key( $options, 'headerimg' );

		if( $headerimg_placement != 'just_text' 
			&& array_key_exists( $headerimg_field_key, $options )  
			&& strlen($options[$headerimg_field_key])>5 ){

			$width = isset($options['headerimg_width']) && intval( $options['headerimg_width'] ) ? $options['headerimg_width'] : 0;
			$height = isset($options['headerimg_height']) && intval( $options['headerimg_height'] ) ? $options['headerimg_height'] : 0;
			$alt_text = ( $headerimg_placement == 'replace_text' ? $headertext : $options['headerimg_alt'] );
			$headerimg = '<img class="header-image' . ($width>580?' full-width-header-image':'').'" 
										src="'.$options[$headerimg_field_key].'" 
										style="'.
											( $width ?'width:' . $width . 'px; ' : '' ) .
											( $height ?'height:' . $height . 'px; ' : '' ) .
										'" '.
										( $width ? ' width="' . $width . '" ' : '' ) .
										( $height ? ' height="' . $height . '" ' : '' ) .
										'
										alt="'.$alt_text.'">';

			if( !$options['headerimg_align'] )
				$options['headerimg_align'] = $options['headeralign'];
			if( !$options['header_spacer'] )
				$options['header_spacer'] = 10;
		}else{
			$headerimg_placement = 'just_text';
		}

		switch( $headerimg_placement ){
			case 'just_text':
				$header = $headertext;
				break;
			case 'replace_text':
				ob_start();
				?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td 
							class="header-image" 
							align="<?php echo $options['headerimg_align']; ?>" 
							style="text-align: <?php echo $options['headerimg_align']; ?>;">
							<?php echo $headerimg; ?>
						</td>
					<tr>
				</table>
				<?php
				$header = ob_get_clean();
				break;
			case 'above_text':
				ob_start();
				?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td 
							class="header-image" 
							align="<?php echo $options['headerimg_align']; ?>" 
							style="
								text-align: <?php echo $options['headerimg_align']; ?>; 
								padding-bottom: <?php echo $options['header_spacer']; ?>px; 
								">
							<?php echo $headerimg; ?>
						</td>
					<tr>
					<tr>
						<td 
							class="header-text"
							align="<?php echo $options['headeralign']; ?>" 
							style="text-align: <?php echo $options['headeralign']; ?>">
							<?php echo $headertext; ?>
						</td>
					<tr>
				</table>
				<?php
				$header = ob_get_clean();
				break;
			case 'below_text':
				ob_start();
				?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td 
							class="header-text"
							align="<?php echo $options['headeralign']; ?>" 
							style="text-align: <?php echo $options['headeralign']; ?>">
							<?php echo $headertext; ?>
						</td>
					<tr>
					<tr>
						<td 
							class="header-image" 
							align="<?php echo $options['headerimg_align']; ?>" 
							style="
								text-align: <?php echo $options['headerimg_align']; ?>; 
								padding-top: <?php echo $options['header_spacer']; ?>px; 
								">
							<?php echo $headerimg; ?>
						</td>
					<tr>
				</table>
				<?php
				$header = ob_get_clean();
				break;
		}

		if( apply_filters( 'haet_mail_link_header', true ) )
			$header = '<a href="' . get_home_url() . '">' . $header . '</a>';

		return $header;
	}



	public function get_template($options){
		$template=$this->load_template_file('default');
		
		$options['headertext'] = apply_filters( 'haet_mail_header', $this->get_header( $options ) );

		if( !$options['headerbackground'] )
			$options['headerbackground'] = 'transparent';

		if( !$options['contentbackground'] )
			$options['contentbackground'] = 'transparent';

		if( !$options['footerbackground'] )
			$options['footerbackground'] = 'transparent';

		$footer_field_key = $this->multilanguage->get_translateable_theme_options_key( $options, 'footer' );
		if( array_key_exists( $footer_field_key, $options ) )
			$options[$footer_field_key] = stripslashes( $options[$footer_field_key] );
		else
			$options[$footer_field_key] = $options['footer'];
		$options['footer'] = apply_filters( 'haet_mail_footer', $options[$footer_field_key] );

		foreach ($options as $option => $value) {
			if(strpos($option, 'bold'))
				$value=($value==1?'bold':'normal');
			if(strpos($option, 'italic'))
				$value=($value==1?'italic':'normal');
			if(strpos($option, 'underline'))
				$value=($value==1?'underline':'none');
			$template = str_replace('###'.$option.'###', $value, $template);
		}
		return $template;
	}



	private function prepare_email_for_delivery( $message ){
		// general custom CSS via filters
		$custom_css_desktop = '';
		$custom_css_mobile = '';
		$custom_css_desktop = apply_filters( 'haet_mail_css_desktop', $custom_css_desktop );
		$custom_css_mobile = apply_filters( 'haet_mail_css_mobile', $custom_css_mobile );
		$custom_css_mobile = ' @media screen and (max-width: 400px) { ' . PHP_EOL . $custom_css_mobile . ' } ';
		$message = str_replace( '/**** ADD CSS HERE ****/', $custom_css_desktop . '/**** ADD CSS HERE ****/', $message );
		$message = str_replace( '/**** ADD MOBILE CSS HERE ****/', $custom_css_mobile . '/**** ADD MOBILE CSS HERE ****/', $message );


		$message = $this->inline_css($message);

		// remove any scripts injected by hooks and shortcodes
		$message = preg_replace( '/(<script.*<\/script>)/Us', '', $message );

		$options = $this->get_options();
		// OMG, isn't there a better way to get rid of these encoding issues!?
		if( isset( $options['invalid_contenttype_to_html'] ) && $options['invalid_contenttype_to_html'] ){
			$message = htmlentities( $message, ENT_NOQUOTES, "UTF-8", false );
			$message = str_replace(array('&lt;','&gt;'),array('<','>'), $message);
		}
		

		return $message;
	}


	function inline_css($html){
		require_once(HAET_MAIL_PATH.'/vendor/autoload.php');

		$cssToInlineStyles = new voku\CssToInlineStyles\CssToInlineStyles( $html );

		$cssToInlineStyles->setExcludeConditionalInlineStylesBlock(false);
		$cssToInlineStyles->setUseInlineStylesBlock(true);
		return $cssToInlineStyles->convert();
	}



	public function get_default_fonts(){
		return array(
			'Arial, Helvetica, sans-serif' 		=>	'Arial',
			'Helvetica, Arial, sans-serif' 		=>	'Helvetica',
			'Times New Roman,Georgia,serif'		=> 	'Times New Roman',
			'Georgia,Times New Roman,serif'		=> 	'Georgia',
			'Courier, monospace'				=>	'Courier',
			'Tahoma, Geneva, sans-serif'		=>  'Tahoma',
			'Lucida Sans, sans-serif'			=>  'Lucida',
			'Trebuchet, sans-serif'				=>  'Trebuchet',
			'Verdana, sans-serif'				=>  'Verdana',
		);
	}


	public function get_fonts(){
		return apply_filters( 'haet_mail_fonts', $this->get_default_fonts() );
	}


	public function get_tab_url($tab = "") {
		if( $tab )
			return add_query_arg( 'tab', $tab, remove_query_arg( 'advanced-action' ) );
		return remove_query_arg( 'tab', remove_query_arg( 'advanced-action' ) );
	}


	private function field_name_to_id( $name ){
		return str_replace( '[', '_', str_replace( ']', '_', $name ) );
	}


	public function font_toolbar( $fields ){
		?>
		<div class="haet-font-toolbar">
		<?php
		foreach ($fields as $type => $field) {
			if( is_array( $field ) ){
				switch ( $type ) {
					case 'font':
						$fonts = $this->get_fonts();
						?>
						<select  id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" name="<?php echo $field['name']; ?>">
							<?php foreach ($fonts as $font => $display_name) :?>
								<option value="<?php echo $font; ?>" <?php echo ($field['value']==$font?'selected':''); ?>><?php echo $display_name; ?></option>		
							<?php endforeach; ?>
						</select>
						<?php
						break;
					case 'fontsize':
						?>
						<select  id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" name="<?php echo $field['name']; ?>">
							<?php for ($fontsize=11; $fontsize<=50; $fontsize++) :?>
								<option value="<?php echo $fontsize; ?>" <?php echo ($field['value']==$fontsize?'selected':''); ?>><?php echo $fontsize; ?>px</option>		
							<?php endfor; ?>
						</select>
						<?php
						break;
					case 'color':
						?>
						<input type="text" class="color" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>">
						<?php
						break;
					case 'bold':
						?>
						<input type="hidden" name="<?php echo $field['name']; ?>" value="0">
						<input type="checkbox" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" class="haet-toggle" name="<?php echo $field['name']; ?>" value="1" <?php echo ($field['value']==1?'checked':''); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>"><span class="dashicons dashicons-editor-bold"></span></label>
						<?php
						break;
					case 'italic':
						?>
						<input type="hidden" name="<?php echo $field['name']; ?>" value="0">
						<input type="checkbox" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" class="haet-toggle" name="<?php echo $field['name']; ?>" value="1" <?php echo ($field['value']==1?'checked':''); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>"><span class="dashicons dashicons-editor-italic"></span></label>
						<?php
						break;
					case 'texttransform':
						?>
						<input type="hidden" name="<?php echo $field['name']; ?>" value="none">
						<input type="checkbox" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>" class="haet-toggle" name="<?php echo $field['name']; ?>" value="uppercase" <?php echo ($field['value']=="uppercase"?'checked':''); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>"><span class="dashicons dashicons-arrow-up" title="<?php esc_attr_e( 'Uppercase', 'wp-html-mail' ); ?>"></span></label>
						<?php
						break;
					case 'align':
						?>
						<input type="radio" name="<?php echo $field['name']; ?>" class="haet-toggle" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>_left" value="left" <?php echo ($field['value']=="left"?"checked":""); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>_left"><span class="dashicons dashicons-editor-alignleft"></span></label>

						<input type="radio" name="<?php echo $field['name']; ?>" class="haet-toggle" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>_center" value="center" <?php echo ($field['value']=="center"?"checked":""); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>_center"><span class="dashicons dashicons-editor-aligncenter"></span></label>

						<input type="radio" name="<?php echo $field['name']; ?>" class="haet-toggle" id="<?php echo $this->field_name_to_id( $field['name'] ); ?>_right" value="right" <?php echo ($field['value']=="right"?"checked":""); ?>>
						<label for="<?php echo $this->field_name_to_id( $field['name'] ); ?>_right"><span class="dashicons dashicons-editor-alignright"></span></label>
						<?php
						break;
				}
			}
		}
		?>
		</div>
		<?php
	}


	public function wrap_in_padding_container( $content, $id = '' ){
		return '<table width="100%" border="0" cellpadding="0" cellspacing="0" id="' . $id . '">
					<tr>
						<td class="container-padding">
							' . $content . '
						</td>
					</tr>
				</table>';
	}



	/**
	 * Show action links on the plugin screen
	 */
	public function plugin_action_links( $links ) {
		return array_merge( array(
			'<a href="' . get_admin_url(null,'options-general.php?page=wp-html-mail') . '">' . __( 'Settings' ) . '</a>'
		), $links );
	}


	public function maybe_show_testmode_warning(){
		$options = $this->get_options();
		if( is_array( $options ) && isset( $options['testmode'] ) && isset( $options['testmode_recipient'] ) ){
			if( $options['testmode'] && is_email( trim( $options['testmode_recipient'] ) ) ){
				?>
				<div class="notice notice-warning">
				    <p><?php echo sprintf( __( '<strong>Warning:</strong> WP HTML Mail – Email test mode is enabled. All emails are redirected to <strong>%1$s</strong>.', 'wp-html-mail' ), $options['testmode_recipient'] ); ?></p>
				</div>
				<?php
			}
		}
	}

	/**
	 * added in 2.9
	 * debug mode can only be used when testmode is active to avoid sending debug infos to clients
	 */
	public function is_debug_mode(){
		$options = $this->get_options();
		return ( 
				is_array( $options ) 
				&& isset( $options['testmode'] ) 
				&& $options['testmode'] 
				&& isset( $options['testmode_recipient'] ) 
				&& is_email( trim( $options['testmode_recipient'] ) )
				&& isset( $options['debugmode'] ) 
				&& $options['debugmode']
		);
	}

	/**
	 * set a default value for the header image placement.
	 * this is especially important for upgrading from versions before 3.0 where this parameter didn't exist
	 */
	public function init_headerimg_placement( $theme_options ){
		$headerimg_field_key = $this->multilanguage->get_translateable_theme_options_key( $theme_options, 'headerimg' );
		if( !array_key_exists( 'headerimg_placement', $theme_options ) )
			$theme_options['headerimg_placement'] = '';

		if( $theme_options['headerimg_placement'] != 'just_text' 
			&& (
				!isset($theme_options[$headerimg_field_key]) 
				|| strlen($theme_options[$headerimg_field_key])<5
		 	) )
			$theme_options['headerimg_placement'] = 'replace_text';
		return $theme_options;
	}
}

function Haet_Mail()
{
	return Haet_Mail::instance();
}

Haet_Mail();

?>