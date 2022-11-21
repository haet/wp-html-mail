<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

class Haet_Template_Library {
	private $api_url              = 'https://templates.wp-html-mail.com/api/';
	private $min_template_version = '3.0';
	private $max_template_version = '3.0';

	public function get_templates( $force_reload = false ) {
		if ( false === ( $templates = get_transient( 'haet_mail_templates' ) ) || $force_reload ) {
			// this code runs when there is no valid transient set
			$request = wp_remote_get( $this->api_url . 'templates/' );
			if ( is_wp_error( $request ) ) {
				return array();
			}

			$request_body = wp_remote_retrieve_body( $request );
			$templates    = json_decode( $request_body );

			if ( is_array( $templates ) && count( $templates ) ) {
				foreach ( $templates as $template_index => $template ) {
					if ( version_compare( $template->version, $this->min_template_version, 'lt' )
						|| version_compare( $template->version, $this->max_template_version, 'gt' ) ) {
						unset( $templates[ $template_index ] );
					}
				}
			}

			if ( is_array( $templates ) && count( $templates ) ) {
				set_transient( 'haet_mail_templates', $templates, 12 * HOUR_IN_SECONDS );
			}
		}
		if ( ! is_array( $templates ) ) {
			$templates = array();
		}
		return $templates;
	}
}
