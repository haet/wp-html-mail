<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}
/**
 *   detect the origin of an email
 **/
class Haet_Sender_Plugin_The_Newsletter_Plugin extends Haet_Sender_Plugin {

	public function __construct( $mail ) {
		if ( strpos( $mail['message'], 'tnpc-block-content' ) === false ) {
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
