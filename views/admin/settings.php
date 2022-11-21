<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="wp-html-mail-header">
	<div class="heading-wrap">
		<img src="<?php echo HAET_MAIL_URL . 'images/icon.png'; ?>" width="32" height="32">
		<div class="heading">
			<h2>
				WP HTML Mail - Email Template Designer
			</h2>
			<div class="subtitle">
				<?php _e( 'The only true all-in-one email template solution', 'wp-html-mail' ); ?>
			</div>
		</div>
	</div>
	<div class="nav-buttons">
		<?php
		foreach ( $tabs as $el => $name ) {
			echo '<a class="button' . ( $el == $tab ? ' active' : '' ) . ( $el == 'template' ? ' button-primary' : '' ) . '" href="' . esc_url( $this->get_tab_url( $el ) ) . '">' . esc_html( $name ) . '</a>';
		}
		?>
	</div>
</div>
<textarea style="display:none;" id="haet_mailtemplate"><?php echo esc_textarea( stripslashes( str_replace( '\\&quot;', '', $template ) ) ); ?></textarea>

<form method="post" id="haet_mail_form" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
	<?php
	wp_nonce_field( 'save_email_options', 'email_options_nonce' );
	do_action( 'haet_mail_before_settings_tab_' . $tab );
	switch ( $tab ) {
		case 'template':
			include 'settings-template.php';
			break;
		case 'webfonts':
			if ( defined( 'HAET_MAIL_WEBFONTS_PATH' ) ) {
				include HAET_MAIL_WEBFONTS_PATH . 'views/admin/settings-webfonts.php';
			} else {
				include 'settings-webfonts-sales-page.php';
			}
			break;
		default:
			$is_plugin_tab = false;
			if ( isset( $active_plugins[ $tab ] ) ) {
				$active_plugins[ $tab ]['class']::settings_tab();
				$is_plugin_tab = true;
			}
			break;
	} //switch Tab
	do_action( 'haet_mail_after_settings_tab_' . $tab );
	?>
	<?php if ( $tab === 'woocommerce' || $tab === 'easy-digital-downloads' ) : ?>
		<div class="submit">
			<input type="submit" name="update_haet_mailSettings" class="button-primary" value="<?php esc_html_e( 'Save and Preview', 'wp-html-mail' ); ?>" />
		</div>
	<?php endif; ?>
</form>

<?php
if ( ! in_array( $tab, [ 'webfonts'] ) ):
	if (
		! Haet_Mail()->multilanguage->is_multilanguage_site()
		|| Haet_Mail()->multilanguage->get_current_language()
	) :
		?>
		<div class="nav-tab-wrapper">
			<h3 class="haet-mail-preview-headline">
				<?php esc_html_e( 'Preview', 'wp-html-mail' ); ?>
			</h3>
			<a class="nav-tab nav-tab-active haet-mail-preview-size-button" data-previewwidth="800">
				<span class="dashicons dashicons-desktop"></span> &amp; <span class="dashicons dashicons-tablet"></span>
			</a>
			<a class="nav-tab haet-mail-preview-size-button" data-previewwidth="360">
				<span class="dashicons dashicons-smartphone"></span>
			</a>

			<div class="haet-mail-send-preview">
				<?php esc_html_e( 'Send a test mail', 'wp-html-mail' ); ?>
				<input id="haet_mail_test_address" required type="email" placeholder="you@example.org">
				<button id="haet_mail_test_submit"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
				<div id="haet_mail_test_sent" class="haet-mail-dialog" title="<?php esc_html_e( 'Send a test mail', 'wp-html-mail' ); ?>">
					<p>

					</p>
				</div>
			</div>
		</div>
		<iframe id="mailtemplatepreview" style="width:800px; height:480px; border:1px solid #ccc;"></iframe>
		<br>
		<?php
	elseif (
		Haet_Mail()->multilanguage->is_multilanguage_site()
		&& ! Haet_Mail()->multilanguage->get_current_language()
	) :
		?>
		<div class="nav-tab-wrapper">
			<h3 class="haet-mail-preview-headline">
				<?php esc_html_e( 'Preview', 'wp-html-mail' ); ?>
			</h3>
		</div>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'Please select a language in your admin bar at the top of the page to show the preview.', 'wp-html-mail' ); ?></p>
		</div>

	<?php endif; ?>

<?php endif; ?>

<?php if ( ( ! isset( $is_plugin_tab ) || false === $is_plugin_tab ) ) : ?>
	<div id="haet_mail_enable_translation_dialog" class="haet-mail-dialog" title="<?php esc_html_e( 'Please wait', 'wp-html-mail' ); ?>">
		<p style="text-align:center;">
			<?php esc_html_e( 'Translation settings for this field are updated.', 'wp-html-mail' ); ?><br /><img src="<?php echo esc_url( get_admin_url( null, '/images/spinner.gif' ) ); ?>">
		</p>
	</div>
<?php endif; ?>
