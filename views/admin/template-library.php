<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} ?>


	<br/>
	<a href="<?php echo $this->get_tab_url(); ?>" class="button-secondary haet-mail-browse-templates">
		&lt; <?php _e('back', 'wp-html-mail'); ?>
	</a>
	<br/>


<h1>
	<?php _e('Use one of our templates to get started', 'wp-html-mail'); ?>
</h1>
	

<?php 
$templates = $template_library->get_templates(); 
if( !is_array( $templates ) || count( $templates ) === 0 ): ?>
	<h3><?php _e('No templates found', 'wp-html-mail'); ?></h3>
	<p><?php _e('We could not find any templates. Maybe your server cannnot connect to our template library.', 'wp-html-mail'); ?></p>
<?php else: ?>
	<div class="haet-mail-template-list clearfix">
		<?php foreach( $templates as $template ): ?>
			<a 
				href="#" 
				class="haet-mail-template" 
				style="background-image:url(<?php echo $template->screenshot; ?>)" 
				data-name="<?php echo esc_attr( $template->name ); ?>"
				data-screenshot="<?php echo esc_attr( $template->screenshot ); ?>"
				data-config="<?php echo esc_attr( $template->config ); ?>"
				data-slug="<?php echo esc_attr( $template->slug ); ?>"
				data-description="<?php echo esc_attr( $template->description ); ?>">

				<span class="template-name"><?php echo $template->name; ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<div id="haet_mail_template_preview_dialog" class="haet-mail-dialog" title="" data-button-caption="<?php esc_attr_e( 'Import template (and overwrite my existing settings)', 'wp-html-mail'); ?>">
		<img class="screenshot" src=""> 
		<div class="template-description">
			<h2></h2>
			<p class="template-description-text"></p>
		</div>
	</div>
	<div id="haet_mail_template_importing_dialog" class="haet-mail-dialog" title="<?php _e('Please wait','wp-html-mail'); ?>">
		<p style="text-align:center;">
			<?php _e('Importing template, please wait a moment.','wp-html-mail'); ?><br/><img src="<?php echo get_admin_url(null,'/images/spinner.gif'); ?>">
		</p>
	</div>
<?php endif; ?>
<form method="post" id="haet_mail_import_form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="haet_mail_import_template_url" value="">
</form>

		



