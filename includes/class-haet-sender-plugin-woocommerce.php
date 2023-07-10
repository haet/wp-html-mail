<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_WooCommerce extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !strpos($mail['message'], '<!--woocommerce-content-start-->') )
            throw new Haet_Different_Plugin_Exception();
    }

    /**
    *   request_preview_instance()
    *   force creating an instance to apply all modifications to live preview
    **/
    public static function request_preview_instance(){
        $fake_mail = array('message'=>' <!--woocommerce-content-start-->');
        return new self($fake_mail);
    }

    /**
    *   modify_template()
    *   mofify the email template before the content is added
    **/
    public function modify_template($template){
        return $template;
    }    


    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        $startpos = strpos($content, '<!--woocommerce-content-start-->');
        $endpos = strpos($content, '<!--woocommerce-content-end-->');
        
        if(!$startpos || !$endpos)
            return $content;

        $header = substr($content, 0, $startpos);
        
        // extract H1 tag from header
        $headline = '';
        $pattern = '/<h1[^>]*>(.*?)<\/h1>/i';
        // Perform the regex match
        if (preg_match($pattern, $header, $matches)) {
            $headline = $matches[1];  // Extract the content from the first match
        }
        
        $content = substr($content, $startpos, $endpos-$startpos);
        if( $headline ){
            $content = '<h1>'.$headline.'</h1>'.$content;
        }
        return $content;        
    }

    /**
    *   modify_styled_mail()
    *   mofify the email body after the content has been added to the template
    **/
    public function modify_styled_mail($message){
        return $message;
    } 

    /**
    *   email_header()
    *   add a marker to the end of the woocommerce header to find the content in between
    **/
    public static function email_header( $email_heading, $email = null ){
        echo '<!--woocommerce-content-start-->';
    }

    /**
    *   email_footer()
    *   add a marker to the top of the woocommerce footer to find the content in between
    **/
    public static function email_footer(){
        echo '<!--woocommerce-content-end-->';
    }


    public static function plugin_actions_and_filters(){

        // remove woocommerce email styling
        add_action( 'woocommerce_email_header', 'Haet_Sender_Plugin_WooCommerce::email_header', 100, 2 );
        add_action( 'woocommerce_email_footer', 'Haet_Sender_Plugin_WooCommerce::email_footer', 1 );
    }
}