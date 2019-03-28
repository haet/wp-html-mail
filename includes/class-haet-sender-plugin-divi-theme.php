<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*     there's nothing to do for happy forms it works out of the box 
*     we just use this as a detector to allow disabling WP HTML Mail for these emails
**/
class Haet_Sender_Plugin_DiviTheme extends Haet_Sender_Plugin {
    public function __construct($mail) {
        $is_divi = false;
        if( is_array( $_POST ) && count( $_POST ) ){
            foreach( $_POST as $key => $value ){
                if( strpos( $key, 'et_pb_contact' ) !== false ){
                    $is_divi = true;
                    break;
                }
            }
        }
        if( !$is_divi )
            throw new Haet_Different_Plugin_Exception();
    }
}

