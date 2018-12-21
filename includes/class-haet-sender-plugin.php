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
        $options = array();
        foreach (Haet_Sender_Plugin::get_available_plugins() as $plugin_name => $plugin) {
            if( class_exists($plugin['class']) )
                $options[$plugin_name] = $plugin['class']::get_plugin_default_options();
        }
        $haet_mail_options = get_option('haet_mail_plugin_options');
        if (!empty($haet_mail_options)) {
            foreach ($haet_mail_options as $key => $option){
                if( is_array($option) ){
                    foreach ($option as $sub_key => $sub_option)
                        $options[$key][$sub_key] = $sub_option;    
                }else
                    $options[$key] = $option;
            }
        }               
        update_option('haet_mail_plugin_options', $options);
        return $options;
    }

    public static function save_plugin_options($saved_options) {
        $new_options = $_POST['haet_mail_plugins'];
        $options = $saved_options;
        foreach ($new_options as $key => $value) {
            if( isset( $saved_options[$key] ) )
                $options[$key] = array_merge($saved_options[$key],$new_options[$key]);
            else
                $options[$key] = $new_options[$key];
        }

        update_option('haet_mail_plugin_options', $options);
        return $options;
    }
    

    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        return $content;
    }

    /**
    *   get_plugin_default_options()
    *   define plugin specific default options
    **/
    public static function get_plugin_default_options(){
        return array('template'=>true,'sender'=>true);
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