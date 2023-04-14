<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}
/**
 *   detect the origin of an email
 **/
class Haet_Sender_Plugin_FluentSupport extends Haet_Sender_Plugin {

	public function __construct( $mail ) {
		if ( ! array_key_exists( 'client_priority', $_POST ) && ! array_key_exists( 'close_ticket', $_POST ) && ! array_key_exists( 'close_ticket_silently', $_POST ) && ! array_key_exists( 'ticket', $_POST )) {
			throw new Haet_Different_Plugin_Exception();
		}
	}

	public function modify_content( $content ) {

		if ( preg_match( '/<body.*>(.*)<\/body>/Ums', $content, $matches ) ) {
			if ( is_array( $matches ) ) {
				$content = $matches[1];
			}
		}

		return $content;
	}

}
