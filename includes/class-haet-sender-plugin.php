<?php if ( ! defined( 'ABSPATH' ) ) exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-ninja-forms.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-wp-e-commerce.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-caldera-forms.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-contact-form-7.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-wp-support-plus-responsive-ticket-system.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-birthday-emails.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-gravityforms.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-happyforms.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-ultimate-wp-mail.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-divi-theme.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-terawallet.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-formidable.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin-wpforo.php';

class Haet_Different_Plugin_Exception extends Exception {}

/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin {
    protected $active_plugins;   
    public $current_plugin;
    protected $mail;

    public static function get_available_plugins(){
        $plugins = array(
            // 'PLUGIN_SLUG'   =>  array(
            //     'name'      =>  'PLUGIN_SLUG',
            //     'file'      =>  'PLUGIN_FOLDER/MAIN_PLUGIN_FILE.php',
            //     'class'     =>  'DETECTOR_CLASS_IN_THIS_PLUGIN',
            //     'display_name' => 'DISPLAY_NAME'
            // ),
            'contact-form-7'   =>  array(
                'name'      =>  'contact-form-7',
                'file'      =>  'contact-form-7/wp-contact-form-7.php',
                'class'     =>  'Haet_Sender_Plugin_contact_form_7',
                'display_name' => 'Contact Form 7'
            ),
            'ninja-forms'   =>  array(
                'name'      =>  'ninja-forms',
                'file'      =>  'ninja-forms/ninja-forms.php',
                'class'     =>  'Haet_Sender_Plugin_Ninja_forms',
                'display_name' => 'Ninja Forms'
            ),
            'wp-e-commerce'   =>  array(
                'name'      =>  'wp-e-commerce',
                'file'      =>  'wp-e-commerce/wp-shopping-cart.php',
                'class'     =>  'Haet_Sender_Plugin_WP_E_Commerce',
                'display_name' => 'WP eCommerce'
            ),
            'caldera-forms'   =>  array(
                'name'      =>  'caldera-forms',
                'file'      =>  'caldera-forms/caldera-core.php',
                'class'     =>  'Haet_Sender_Plugin_Caldera_forms',
                'display_name' => 'Caldera Forms'
            ),
            'wp-support-plus-responsive-ticket-system'   =>  array(
                'name'      =>  'wp-support-plus-responsive-ticket-system',
                'file'      =>  'wp-support-plus-responsive-ticket-system/wp-support-plus.php',
                'class'     =>  'Haet_Sender_Plugin_WP_Support_Plus',
                'display_name' => 'WP Support Plus'
            ),
            'birthday-emails'   =>  array(
                'name'      =>  'birthday-emails',
                'file'      =>  'birthday-emails/birthday-emails.php',
                'class'     =>  'Haet_Sender_Plugin_Birthday_Emails',
                'display_name' => 'Birthday Emails'
            ),
            'gravityforms'   =>  array(
                'name'      =>  'gravityforms',
                'file'      =>  'gravityforms/gravityforms.php',
                'class'     =>  'Haet_Sender_Plugin_GravityForms',
                'display_name' => 'GravityForms',
                'image_url' =>  HAET_MAIL_URL . '/images/gravityforms.png'
            ),
            'happyforms'   =>  array(
                'name'      =>  'happyforms',
                'file'      =>  'happyforms/happyforms.php',
                'class'     =>  'Haet_Sender_Plugin_Happyforms',
                'display_name' => 'HappyForms'
            ),
            'ultimate-wp-mail'   =>  array(
                'name'      =>  'ultimate-wp-mail',
                'file'      =>  'ultimate-wp-mail/Main.php',
                'class'     =>  'Haet_Sender_Plugin_UltimateWPMail',
                'display_name' => 'Ultimate WP Mail'
            ),
            'divi-theme'   =>  array(
                'name'      =>  'divi-theme',
                'file'      =>  'Divi',
                'class'     =>  'Haet_Sender_Plugin_DiviTheme',
                'display_name' => 'Divi',
                'image_url' =>  HAET_MAIL_URL . '/images/divi-theme.png'
            ),
            'woo-wallet'   =>  array(
                'name'      =>  'woo-wallet',
                'file'      =>  'woo-wallet/woo-wallet.php',
                'class'     =>  'Haet_Sender_Plugin_Tera_Wallet',
                'display_name' => 'Tera Wallet',
                'image_url' =>  HAET_MAIL_URL . '/images/woo-wallet.png'
            ),
            'formidable'   =>  array(
                'name'      =>  'formidable',
                'file'      =>  'formidable/formidable.php',
                'class'     =>  'Haet_Sender_Plugin_Formidable',
                'display_name' => 'Formidable Forms',
                'image_url' =>  HAET_MAIL_URL . '/images/formidable.png'
            ),
            'wpforo'   =>  array(
                'name'      =>  'wpforo',
                'file'      =>  'wpforo/wpforo.php',
                'class'     =>  'Haet_Sender_Plugin_WPForo',
                'display_name' => 'WP Foro',
                'image_url' =>  HAET_MAIL_URL . '/images/wpforo-logo.png'
            ),
        );

        return apply_filters( 'haet_mail_available_plugins', $plugins );
    }
    
    public static function request_preview_instance(){
        return false;
    }


    public static function detect_plugin($mail){
        $active_plugins = Haet_Sender_Plugin::get_active_plugins();
        foreach ($active_plugins as $plugin) {
            try {
                $sender_plugin = new $plugin['class']($mail);
                $sender_plugin->current_plugin = $plugin;
                $sender_plugin->mail = $mail;
                $sender_plugin->activate_plugins = $active_plugins;

                return $sender_plugin;
            } catch (Haet_Different_Plugin_Exception $e) {

            }
        }
        return null;
    }



    /**
    *   use_template()
    *   return true if the mail template should be used for the current mail
    **/
    public function use_template(){
        $plugin_options = $this->get_plugin_options();
        if(array_key_exists($this->current_plugin['name'], $plugin_options))
            return $plugin_options[ $this->current_plugin['name'] ]['template'];
        else
            return true;
    }

    /**
    *   use_sender()
    *   return true if the sender should be overwritten for the current mail
    **/
    public function use_sender(){
        $plugin_options = $this->get_plugin_options();
        if(array_key_exists($this->current_plugin['name'], $plugin_options))
            return $plugin_options[ $this->current_plugin['name'] ]['sender'];
        else
            return true;
    }

    
    /**
    *   is_header_hidden()
    *   return true if the header of the mail template should hidden
    **/
    public function is_header_hidden(){
        $plugin_options = $this->get_plugin_options();
        if(array_key_exists($this->current_plugin['name'], $plugin_options)){
            if( array_key_exists( 'hide_header', $plugin_options[ $this->current_plugin['name'] ] ) )
                return $plugin_options[ $this->current_plugin['name'] ]['hide_header'];
            else
                return false;
        }else
            return false;
    }

    /**
    *   is_footer_hidden()
    *   return true if the footer of the mail template should hidden
    **/
    public function is_footer_hidden(){
        $plugin_options = $this->get_plugin_options();
        if(array_key_exists($this->current_plugin['name'], $plugin_options)){
            if( array_key_exists( 'hide_footer', $plugin_options[ $this->current_plugin['name'] ] ) )
                return $plugin_options[ $this->current_plugin['name'] ]['hide_footer'];
            else
                return false;
        }else
            return false;
    }


    public function get_plugin_name(){
        return $this->current_plugin['name'];
    }


    public static function is_plugin_active( $plugin ){
        if( class_exists($plugin['class']) ){ 
            if( array_key_exists( 'file', $plugin ) && is_plugin_active($plugin['file']) )
                return true;

            // plugins can consist of mutliple plugins e.g. wpforms & wpforms-lite
            if( array_key_exists( 'files', $plugin ) && is_array( $plugin['files'] ) ){
                foreach ( $plugin['files'] as $plugin_file ) {
                    if( is_plugin_active($plugin_file) ){
                        return true;
                    }
                }
            }
            $active_theme = wp_get_theme();
            if ( $plugin['display_name'] == $active_theme->name || $plugin['display_name']== $active_theme->parent_theme ) {
                return true;
            }
        }
        return false;
    }


    /**
    *   get_active_plugins
    *   Check all available plugin detectors and return an array of installed plugins
    **/
    public static function get_active_plugins() {
        $active_plugins = array();
        foreach (Haet_Sender_Plugin::get_available_plugins() as $plugin_name => $plugin) {
            if( self::is_plugin_active( $plugin ) )
                $active_plugins[$plugin_name] = $plugin;
        }
        return $active_plugins;
    }

    public static function get_plugin_options() {
        $default_options = [];
        foreach (Haet_Sender_Plugin::get_available_plugins() as $plugin_name => $plugin) {
            if( class_exists($plugin['class']) )
                $default_options[$plugin_name] = $plugin['class']::get_plugin_default_options();
        }
        $options = get_option('haet_mail_plugin_options');
        
        if (!empty($options)) {
            foreach ($options as $plugin_name => $plugin_options){
                if( array_key_exists( $plugin_name, $default_options ) )
                    $options[$plugin_name] = wp_parse_args( $plugin_options, $default_options[$plugin_name] );

            }
        } else {
            $options = $default_options;
        }             

        update_option('haet_mail_plugin_options', $options);
        return $options;
    }

    public static function save_plugin_options($old_options) {
        $new_options = $_POST['haet_mail_plugins'];

        foreach ($new_options as $plugin_name => $new_plugin_options) {
            $new_options[$plugin_name] = wp_parse_args( $new_plugin_options, $old_options[$plugin_name] );
        }

        foreach ( $old_options as $plugin_name => $old_plugin_options) {
            if( !array_key_exists( $plugin_name, $new_options ) )
                $new_options[$plugin_name] = $old_plugin_options;
        }
        update_option('haet_mail_plugin_options', $new_options);
        return $new_options;
    }
    

    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        return $content;
    }

    
    /**
    *   modify_content_plain()
    *   mofify the email content before applying the template
    **/
    public function modify_content_plain($content){
        return $this->modify_content($content);
    }


    /**
    *   get_plugin_default_options()
    *   define plugin specific default options
    **/
    public static function get_plugin_default_options(){
        return array('template'=>1,'sender'=>1,'hide_header'=>0,'hide_footer'=>0);
    }


    /**
    *   modify_template()
    *   mofify the email template before the content is added
    **/
    public function modify_template($template){
        return $template;
    }    

    /**
    *   modify_styled_mail()
    *   mofify the email body after the content has been added to the template
    **/
    public function modify_styled_mail($message){
        return $message;
    } 

    public static function hook_plugins(){
        $active_plugins = Haet_Sender_Plugin::get_active_plugins();
        foreach ($active_plugins as $plugin) {
            $plugin['class']::plugin_actions_and_filters();
        }
    }   

    public static function plugin_actions_and_filters(){}
}