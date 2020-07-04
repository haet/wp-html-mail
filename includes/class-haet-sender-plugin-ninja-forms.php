<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_Ninja_forms extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !array_key_exists('_form_id', $_POST)/* v2x */ && !array_key_exists('formData', $_POST)/* v3x */ )
            throw new Haet_Different_Plugin_Exception();
    }



    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        $content = $this->add_blank_between_multiple_options($content);
        return $content;
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

    private function add_blank_between_multiple_options($message){
        $message = str_replace(',',', ', $message);
        return $message;
    }

}