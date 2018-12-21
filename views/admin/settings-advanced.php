<div class="postbox">
    <h3 class="hndle"><span><?php _e('Advanced features','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <?php /*
                <tr valign="top">
                    <th scope="row"><label><?php _e('Import / Export template','wp-html-mail') ?></label></th>
                    <td>
                        <div class="export-toggle">
                            <textarea><?php echo stripslashes(str_replace('\\&quot;','',json_encode($theme_options))); ?></textarea>
                            <p class="description">
                                <?php _e('Copy the settings above and paste into another site or paste other sites settings here.','wp-html-mail'); ?>
                            </p>
                        </div>
                    </td>
                </tr>
                */?>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Delete plugin settings','wp-html-mail') ?></label></th>
                    <td>
                        <a href="<?php echo add_query_arg( 'advanced-action', 'delete-design' ); ?>" class="button-secondary" data-haet-confirm="<?php esc_attr_e('Are you sure? This can not be undone!', 'wp-html-mail') ?>"><?php _e('Delete design settings', 'wp-html-mail'); ?></a>
                        <a href="<?php echo add_query_arg( 'advanced-action', 'delete-all' ); ?>" class="button-secondary" data-haet-confirm="<?php esc_attr_e('Are you sure? This can not be undone!', 'wp-html-mail') ?>"><?php _e('Delete ALL settings', 'wp-html-mail'); ?></a>
                        <?php do_action( 'haet_mail_plugin_reset_buttons' ) ?>
                    </td>
                </tr>
                <?php
                $theme_is_writable = is_writable(get_stylesheet_directory());
                ?>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Create custom template','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail_create_template">
                        <button type="button" id="haet_mail_create_template_button" class="button-secondary <?php echo ($theme_is_writable?'':'button-disabled'); ?>" data-haet-confirm="<?php esc_attr_e('Are you sure you want to write your own code?', 'wp-html-mail') ?>"><?php _e('create template file in my theme folder', 'wp-html-mail') ?></button>
                        <?php if(file_exists(get_stylesheet_directory().'/wp-html-mail/template.html')): ?>
                            <p><?php _e('You already have a custom template. If you create a new one the existing template will be backed up.','wp-html-mail'); ?></p>
                        <?php endif; ?>
                        <?php if(!$theme_is_writable): ?>
                            <p><?php _e('WARNING: Your theme directory is not writable by the server. Please change the permission to allow us to create the mail template.','wp-html-mail'); ?></p>
                        <?php endif; ?>
                        <p class="description">
                            <?php _e('Customize your mail template as far as you can. Then click this button to export the template to your theme directory for further modifications.<br>The template will be created in <strong>wp-content/YOUR-THEME/wp-html-mail/template.html</strong>','wp-html-mail'); ?>
                        </p>
                        <p class="description">
                            <?php _e('<strong>Only use this feature if you know what you are doing!</strong><br>From this point you have to continue your work in HTML and CSS code.','wp-html-mail'); ?>
                        </p>
                        <div id="haet_mail_template_created" class="haet-mail-dialog" title="<?php _e('Template created','wp-html-mail'); ?>">
                            <p>
                                <?php _e('Your template has been created.','wp-html-mail'); ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('E-Mail test mode','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail[testmode]" value="0">
                        <input type="checkbox" id="haet_mail_testmode" name="haet_mail[testmode]" value="1" <?php echo ( isset( $options['testmode'] ) && $options['testmode']==1 ?'checked':''); ?>>
                        <label for="haet_mail_testmode"><?php _e('enable test mode','wp-html-mail'); ?></label>
                        <input type="text" id="haet_mail_testmode_recipient" name="haet_mail[testmode_recipient]" placeholder="you@example.org" value="<?php echo ( isset( $options['testmode_recipient'] ) ? $options['testmode_recipient'] : '' ); ?>">
                        <p class="description">
                            <?php _e('Enable email test mode to redirect all messages to your own email address.','wp-html-mail'); ?>
                       </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>