<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*     there's nothing to do for happy forms it works out of the box 
*     we just use this as a detector to allow disabling WP HTML Mail for these emails
**/
class Haet_Sender_Plugin_Happyforms extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !array_key_exists('happyforms_form_id', $_POST) )
            throw new Haet_Different_Plugin_Exception();
    }
}