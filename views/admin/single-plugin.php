<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$is_installed        = Haet_Sender_Plugin::is_plugin_active( $plugin_data );
$is_bridge_installed = class_exists( $plugin_data['class'] );
?>
<li class="haet-mail-plugin <?php echo ( $is_installed ? 'installed' : 'not-installed' ); ?>">
	<div class="haet-mail-plugin-left">
		<?php if ( array_key_exists( 'image_url', $plugin_data ) ) : ?>
			<img src="<?php echo esc_url( $plugin_data['image_url'] ); ?>" width="128" height="128">
			<?php
		else :
			$image_url = 'https://ps.w.org/' . $plugin_name . '/assets/icon-128x128.png';
			if ( @get_headers( $image_url )[0] == 'HTTP/1.1 404 Not Found' ) {
				$image_url = 'https://ps.w.org/' . $plugin_name . '/assets/icon-128x128.jpg';
			}
			?>
			<img src="<?php echo esc_url( $image_url ); ?>" width="128" height="128">
		<?php endif; ?>
	</div>
	<div class="haet-mail-plugin-right">
		<h3><?php echo esc_html( $plugin_data['display_name'] ); ?></h3>
		<?php if ( $is_bridge_installed && $is_installed ) : ?>
			<input type="hidden" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][template]" value="0">
			<input type="checkbox" id="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_template" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][template]" value="1" <?php echo ( isset( $plugin_options[ $plugin_name ]['template'] ) && $plugin_options[ $plugin_name ]['template'] == 1 || ! isset( $plugin_options[ $plugin_name ] ) ? 'checked' : '' ); ?>>
			<label for="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_template"><?php esc_html_e( 'Use template', 'wp-html-mail' ); ?></label><br>

			<input type="hidden" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][hide_header]" value="0">
			<input type="checkbox" id="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_hide_header" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][hide_header]" value="1" <?php echo ( isset( $plugin_options[ $plugin_name ]['hide_header'] ) && $plugin_options[ $plugin_name ]['hide_header'] == 1 ? 'checked' : '' ); ?>>
			<label for="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_hide_header"><?php esc_html_e( 'Hide header', 'wp-html-mail' ); ?></label><br>

			<input type="hidden" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][hide_footer]" value="0">
			<input type="checkbox" id="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_hide_footer" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][hide_footer]" value="1" <?php echo ( isset( $plugin_options[ $plugin_name ]['hide_footer'] ) && $plugin_options[ $plugin_name ]['hide_footer'] == 1 ? 'checked' : '' ); ?>>
			<label for="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_hide_footer"><?php esc_html_e( 'Hide footer', 'wp-html-mail' ); ?></label><br>

			<input type="hidden" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][sender]" value="0">
			<input type="checkbox" id="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_sender" name="haet_mail_plugins[<?php echo esc_attr( $plugin_name ); ?>][sender]" value="1" <?php echo ( isset( $plugin_options[ $plugin_name ]['sender'] ) && $plugin_options[ $plugin_name ]['sender'] == 1 || ! isset( $plugin_options[ $plugin_name ] ) ? 'checked' : '' ); ?>>
			<label for="haet_mail_plugins_<?php echo esc_attr( $plugin_name ); ?>_sender"><?php esc_html_e( 'Overwrite sender', 'wp-html-mail' ); ?></label>
		<?php elseif ( ! $is_bridge_installed ) : ?>
			<p style="text-align:right;">
				<?php esc_html_e( 'Install WP HTML Mail for', 'wp-html-mail' ); ?><br>
				<a class="button-secondary" href="https://codemiq.com/en/downloads/wp-html-mail-<?php echo esc_attr( $plugin_name ); ?>" target="_blank">
					<?php echo esc_html( $plugin_data['display_name'] ); ?> &gt;
				</a>
			</p>
		<?php elseif ( $is_bridge_installed && ! $is_installed ) : ?>
			<?php echo esc_html( $plugin_data['display_name'] . ' ' . __( 'is currently not installed.', 'wp-html-mail' ) ); ?>
			<?php if ( strpos( $plugin_data['name'], '-theme' ) === false ) : ?>
				<a href="<?php echo esc_url( wp_nonce_url( network_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_name ), 'install-plugin_' . $plugin_name ) ); ?>">
					<?php echo esc_html( __( 'install', 'wp-html-mail' ) . ' ' . $plugin_data['display_name'] ); ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</li>
