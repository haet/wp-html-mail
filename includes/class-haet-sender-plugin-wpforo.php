<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}
/**
 *   detect the origin of an email
 **/
class Haet_Sender_Plugin_WPForo extends Haet_Sender_Plugin {
	public function __construct( $mail ) {
		if ( ! array_key_exists( 'wpfaction', $_POST ) ) {
			throw new Haet_Different_Plugin_Exception();
		}
	}
}
