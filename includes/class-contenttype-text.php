<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class Haet_MB_ContentType_Text extends Haet_MB_ContentType
{

	private static $instance;

	/**
	 * @var string
	 */
	protected $_name  = 'text';

	/**
	 * @var string
	 */
	protected $_nicename = '';

	/**
	 * @var int
	 */
	protected $_priority = 1;

	/**
	 * @var bool
	 * contenttype can be used once per email
	 */
	protected $_once = false;

	/**
	 * @var string
	 */
	protected $_icon = 'dashicons-text';

	
	public static function instance(){
		if (!isset(self::$instance) && !(self::$instance instanceof Haet_MB_ContentType_Text)) {
			self::$instance = new Haet_MB_ContentType_Text();
		}

		return self::$instance;
	}




	public function __construct(){
		$this->_nicename = __('Text','wp-html-mail');
		parent::__construct();
	}




	public function enqueue_scripts_and_styles($page){
    	if( false !== strpos($page, 'post.php')){
	        wp_enqueue_script( 'haet_mb_contenttype_'.$this->_name.'_js',  HAET_MAIL_URL.'/js/contenttype-text.js', array( 'jquery' ) );
	    }
	}




	public function admin_render_contentelement_template( $current_email ){
		$this->admin_print_element_start(); ?>
		<div class="mb-contentelement-content">
			<div class="mb-edit-wysiwyg">
				<textarea name="content">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</textarea>
				<div class="mb-content-preview">
					Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
				</div>
			</div>
		</div>
		<?php
		$this->admin_print_element_end();
	}




	public function print_content( $element_content, $settings ){
		$html = '';
		if( isset($element_content->content) && isset($element_content->content->content) )
			$html = $element_content->content->content;

		$html = Haet_Mail()->wrap_in_padding_container( $html, $element_content->id );
		$html = apply_filters( 'haet_mail_print_content_'.$this->_name, $html, $element_content, $settings );

		echo $html;
	}
}



function Haet_MB_ContentType_Text()
{
	return Haet_MB_ContentType_Text::instance();
}

Haet_MB_ContentType_Text();