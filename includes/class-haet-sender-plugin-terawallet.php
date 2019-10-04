<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_Tera_Wallet extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !array_key_exists('woo_wallet', $_POST) )
            throw new Haet_Different_Plugin_Exception();
    }
}