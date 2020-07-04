<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} ?>
<?php /* disabled survey after 2.7.3 for a while and enabled in 2.9.2 again */
if( !isset( $options['survey2020_completed'] ) && !isset( $options['survey2020_dismissed'] ) && $tab != 'survey' ): ?>
	<div class="notice notice-warning haet-survey-nag">
	    <p>
	    	<?php _e('Please help us to improve this plugin.','wp-html-mail'); ?>
	    	<?php _e('How do you like WP HTML Mail so far?','wp-html-mail'); ?>
	    	<span class="haet-star-rating">
	    	    <input type="hidden" class="" id="haet_survey_email_result" name="haet_survey_email_result" value="0">
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=1'; ?>" class="dashicons dashicons-star-empty" data-rating="1"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=2'; ?>" class="dashicons dashicons-star-empty" data-rating="2"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=3'; ?>" class="dashicons dashicons-star-empty" data-rating="3"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=4'; ?>" class="dashicons dashicons-star-empty" data-rating="4"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=5'; ?>" class="dashicons dashicons-star-empty" data-rating="5"></a>
	    	</span>
	    </p>
	</div>
<?php endif; ?>


<h2 class="nav-tab-wrapper">
<?php
	foreach( $tabs as $el => $name ){
		$class = ( $el == $tab ) ? ' nav-tab-active' : '';
		echo '<a class="nav-tab'.$class.'" href="'.$this->get_tab_url($el).'">'.$name.'</a>';
	}
?>
</h2>
<textarea style="display:none;" id="haet_mailtemplate"><?php echo stripslashes(str_replace('\\&quot;','',$template)); ?></textarea>
	
<form method="post" id="haet_mail_form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php
	do_action( 'haet_mail_before_settings_tab_' . $tab );
	switch ($tab){
		case 'general':
			// for backwards 2.x compatibility
			include('settings-general.php');
			break; 
		case 'header': 
			// for backwards 2.x compatibility
			include('settings-header.php');
			break; 
		case 'content': 
			// for backwards 2.x compatibility
			include('settings-content.php');
			break;
		case 'footer':
			// for backwards 2.x compatibility
			include('settings-footer.php');
			break;
		case 'template':
			include('settings-template.php');
			break;
		case 'sender':
			include('settings-sender.php');
			break;
		case 'plugins':
			include('settings-plugins.php');
			break;
		case 'advanced':
			include('settings-advanced.php');
			break;
		case 'survey':
			include('settings-survey.php');
			break;
		case 'webfonts':
			if( defined( 'HAET_MAIL_WEBFONTS_PATH' ) )
				include( HAET_MAIL_WEBFONTS_PATH.'views/admin/settings-webfonts.php' );
			else
				include('settings-webfonts-sales-page.php');
			break;
		default:
			$is_plugin_tab = false;
			if( isset($active_plugins[ $_GET['tab'] ] ) ){
				$active_plugins[ $_GET['tab'] ]['class']::settings_tab();
				$is_plugin_tab = true;
			}
		break;  
	} //switch Tab 
	do_action( 'haet_mail_after_settings_tab_' . $tab );
	?>
	<?php if( $tab != 'survey' && $tab != 'template' && $tab != 'webfonts' ): ?>
		<div class="submit">
			<input type="submit" name="update_haet_mailSettings" class="button-primary" value="<?php _e('Save and Preview', 'wp-html-mail') ?>" />
			<!--<input type="submit" name="reload_haet_mailtemplate" class="button-secondary" value="<?php _e('Discard changes and reload template', 'wp-html-mail') ?>" />-->
		</div>
	<?php endif; ?>
</form>

<?php if( 
		$tab != 'survey' 
		&& $tab != 'webfonts' 
		&& ( 
			!Haet_Mail()->multilanguage->is_multilanguage_site() 
			|| Haet_Mail()->multilanguage->get_current_language() 
		) ): ?>
	<div class="nav-tab-wrapper">
		<h3 class="haet-mail-preview-headline">
			<?php _e('Preview','wp-html-mail'); ?>
		</h3>
		<a class="nav-tab nav-tab-active haet-mail-preview-size-button" data-previewwidth="800">
			<span class="dashicons dashicons-desktop"></span> &amp; <span class="dashicons dashicons-tablet" ></span>
		</a>
		<a class="nav-tab haet-mail-preview-size-button" data-previewwidth="360">
			<span class="dashicons dashicons-smartphone"></span>
		</a>

		<div class="haet-mail-send-preview">
			<?php _e('Send a test mail','wp-html-mail'); ?>
			<input id="haet_mail_test_address" required type="email" placeholder="you@example.org"> 
			<button id="haet_mail_test_submit"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
			<div id="haet_mail_test_sent" class="haet-mail-dialog" title="<?php _e('Email sent','wp-html-mail'); ?>">
				<p>
					<?php _e('Your message has been sent.','wp-html-mail'); ?>
				</p>
			</div>
		</div>
	</div>
	<iframe id="mailtemplatepreview" style="width:800px; height:480px; border:1px solid #ccc;" ></iframe>
	<br>
<?php endif; ?>

<?php if( ( !isset($is_plugin_tab) || false===$is_plugin_tab ) && $tab != 'survey' ): ?>
	<div id="haet_mail_enable_translation_dialog" class="haet-mail-dialog" title="<?php _e('Please wait','wp-html-mail'); ?>">
		<p style="text-align:center;">
			<?php _e('Translation settings for this field are updated.','wp-html-mail'); ?><br/><img src="<?php echo get_admin_url(null,'/images/spinner.gif'); ?>">
		</p>
	</div>
<?php endif; ?>

		



