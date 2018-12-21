<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_Caldera_forms extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !array_key_exists('_cf_frm_id', $_POST) )
            throw new Haet_Different_Plugin_Exception();
    }



    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        $content = wpautop($content);
        return $content;
    }

}