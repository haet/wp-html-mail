<?php
/**
 * Email sender an background color
 * has been replaced by the email designer and the tab "sender" in 3.0 and will be removed in a future version
 */
?>
<div class="postbox">
	<h3 class="hndle"><span><?php esc_html_e( 'General', 'wp-html-mail' ); ?></span></h3>
	<div style="" class="inside">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="haet_mailbackground"><?php esc_html_e( 'Background color', 'wp-html-mail' ); ?></label></th>
					<td>
						<input type="text" class="color" id="haet_mailbackground" name="haet_mail_theme[background]" value="<?php echo esc_attr( $theme_options['background'] ); ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haet_mailfromname"><?php esc_html_e( 'Email sender name', 'wp-html-mail' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="haet_mailfromname" name="haet_mail[fromname]" value="<?php echo esc_attr( stripslashes( $options['fromname'] ) ); ?>" required>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haet_mailfromaddress"><?php esc_html_e( 'Email sender address', 'wp-html-mail' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="haet_mailfromaddress" name="haet_mail[fromaddress]" value="<?php echo esc_attr( $options['fromaddress'] ); ?>" required>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
