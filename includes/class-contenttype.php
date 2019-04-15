<?php if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Haet_MB_ContentType
{
	/**
	 * @var string
	 */
	protected $_name  = '';

	/**
	 * @var string
	 */
	protected $_nicename = '';

	/**
	 * @var int
	 */
	protected $_priority = 10;

	/**
	 * @var bool
	 * contenttype can be used once per email
	 */
	protected $_once = false;

	/**
	 * @var string 
	 * Dashicon or image url
	 */
	protected $_icon = 'dashicons-screenoptions';


	public function __construct(){
		add_filter( 'haet_mail_content_types', array( $this, 'register_contenttype'), $this->_priority, 3 );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		add_action( 'haet_mail_content_template', array($this, 'admin_render_contentelement_template'), $this->_priority );
		add_action( 'haet_mail_content_settings', array($this, 'admin_print_contentelement_settings'), $this->_priority );
	}





	public function register_contenttype( $content_types, $email_name, $email_post = null ){
		$content_types[$this->_name] = array(
				'name'		=>	$this->_name,
				'nicename'	=>	$this->_nicename,
				'icon'		=>	$this->_icon,
				'once'		=>	$this->_once,
				'elementclass'	=>	get_called_class()
			);
		return $content_types;
	}


	protected function get_element_settings(){
		return array( 
				'styles'	=>	$this->get_element_styles()
			);
	}


	protected function get_element_styles(){
		return array(
			'desktop'	=> array(
					'padding-top'	=> '0px',
					'padding-right'	=> '24px',
					'padding-bottom'=> '0px',
					'padding-left'	=> '24px',
					'background-color'	=> '',
				),
			'mobile'	=> array(
					'padding-top'	=> '0px',
					'padding-right'	=> '12px',
					'padding-bottom'=> '0px',
					'padding-left'	=> '12px'
				)
			);
	}



	protected function admin_print_element_start(){
		?>
		<div class="mb-contentelement mb-contentelement-<?php echo $this->_name; ?> clearfix container-padding" data-type="<?php echo $this->_name; ?>">
			<input type="hidden" class="mb-element-settings" value='<?php echo json_encode( $this->get_element_settings() ); ?>'>
		<?php
		$this->admin_print_element_title_bar(); 
	}




	protected function admin_print_element_title_bar(){
		?>
		<div class="mb-title-bar">
			<?php echo ( false !== strpos( $this->_icon,'dashicons-') ? '<span class="dashicons '.$this->_icon.'"></span>' : '<img class="mb-type-icon" src="'.$this->_icon.'">' ); ?>
			<span class="mb-title">
				<?php echo $this->_nicename; ?>
			</span>
			<a href='#' class="mb-edit-element">
				<span class="dashicons dashicons-edit"></span>
			</a>
			<a href='#' class="mb-remove-element">
				<span class="dashicons dashicons-trash"></span>
			</a>
		</div>
		<?php
	}




	protected function admin_print_element_end(){
		?>
		</div>
		<?php
	}



	public function admin_print_contentelement_settings(){
	    ?>
	    <div class="mb-element-settings-<?php echo $this->_name; ?> mailbuilder-sidebar-element">
	        <h3>
	            <?php _e('Content Element Settings','wp-html-mail'); ?>
	        </h3> 
	        <table class="form-table">
	            <tbody>
	                <tr valign="top">
	                    <th scope="row"><label><?php _e('Background color','wp-html-mail'); ?></label></th>
	                    <td>
	                        <input type="text" class="background-color color" value="">
	                    </td>
	                </tr>
		    	</tbody>
		    </table>
	        <h4><?php _e('Padding desktop','wp-html-mail'); ?></h4>
	        <div class="mb-element-padding-desktop padding-settings clearfix">
	            <select class="padding-left">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-top">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-right">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-bottom">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	        </div>

	        <h4><?php _e('Padding mobile','wp-html-mail'); ?></h4>
	        <div class="mb-element-padding-mobile padding-settings clearfix">
	            <select class="padding-left">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-top">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-right">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	            
	            <select class="padding-bottom">
	                <?php for ($width=0; $width<=60; $width++) :?>
	                    <option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>      
	                <?php endfor; ?>
	            </select>
	        </div>

	        <div class="mb-popup-buttons">
	            <button class="mb-apply button button-primary" type="button">
	                <span class="dashicons dashicons-yes"></span>
	                <?php _e('Apply', 'wp-html-mail'); ?>
	            </button>
	            <button class="mb-cancel button button-secondary" type="button">
	                <span class="dashicons dashicons-no-alt"></span>
	                <?php _e('Cancel', 'wp-html-mail'); ?>
	            </button>
	        </div>
	    </div>
	    <?php
	}

	public abstract function enqueue_scripts_and_styles($page);

	public abstract function admin_render_contentelement_template( $current_email );

	public abstract function print_content( $element_content, $settings );
}

