<div class="postbox">
    <h3 class="hndle"><span><?php _e('Advanced features','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                
                <tr valign="top">
                    <th scope="row"><label><?php _e('Import / Export template','wp-html-mail') ?></label></th>
                    <td>
                        <a href="#" class="button-secondary haet-mail-toggle-export-button"><?php _e('Export template', 'wp-html-mail'); ?></a>
                        <a href="#" class="button-secondary haet-mail-toggle-import-button"><?php _e('Import template', 'wp-html-mail'); ?></a>
                        <div class="haet-mail-toggle-export">
                            <textarea readonly><?php echo str_replace('\\&quot;','',json_encode($theme_options)); ?></textarea>
                            <p class="description">
                                <?php _e('Copy the settings above and paste into another site.','wp-html-mail'); ?>
                            </p>
                        </div>

                        <div class="haet-mail-toggle-import">
                            <input id="haet_mail_enable_import_theme_options" type="hidden" name="enable_import_theme_options" value="0">
                            <textarea id="haet_mail_import_theme_options" name="import_theme_options"></textarea>
                            <p class="description">
                                <?php _e('Paste settings from another site to the field above.','wp-html-mail'); ?>
                            </p>
                            <a href="#" class="button-secondary haet-mail-import-start"><?php _e('Start Import', 'wp-html-mail'); ?></a>
                        </div>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label><?php _e('Content type','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail[invalid_contenttype_to_html]" value="0">
                        <input type="checkbox" id="haet_mail_invalid_contenttype_to_html" name="haet_mail[invalid_contenttype_to_html]" value="1" <?php echo ( isset( $options['invalid_contenttype_to_html'] ) && $options['invalid_contenttype_to_html']==1 ?'checked':''); ?>>
                        <label for="haet_mail_invalid_contenttype_to_html"><?php _e('Allow HTML code in plain text content type','wp-html-mail'); ?></label>
                        <p class="description">
                            <?php _e('From security perspective you should not enable this option but some plugin developers send HTML code in their emails without setting the correct content type header, so you might see HTML tags in your emails.','wp-html-mail'); ?>
                       </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Delete plugin settings','wp-html-mail') ?></label></th>
                    <td>
                        <a href="<?php echo add_query_arg( 'advanced-action', 'delete-design' ); ?>" class="button-secondary" data-haet-confirm="<?php esc_attr_e('Are you sure? This can not be undone!', 'wp-html-mail') ?>"><?php _e('Delete design settings', 'wp-html-mail'); ?></a>
                        <a href="<?php echo add_query_arg( 'advanced-action', 'delete-all' ); ?>" class="button-secondary" data-haet-confirm="<?php esc_attr_e('Are you sure? This can not be undone!', 'wp-html-mail') ?>"><?php _e('Delete ALL settings', 'wp-html-mail'); ?></a>
                        <?php do_action( 'haet_mail_plugin_reset_buttons' ) ?>
                    </td>
                </tr>
                <?php if( class_exists( 'WPHTMLMail_Woocommerce' ) ): ?>
                    <tr valign="top">
                        <th scope="row"><label><?php _e('Mailbuilder custom posts','wp-html-mail') ?></label></th>
                        <td>
                            <a href="<?php echo get_admin_url( null, 'edit.php?post_type=wphtmlmail_mail' ); ?>" class="button-secondary"><?php _e('Show all emails', 'wp-html-mail'); ?></a>
                            <p class="description">
                                <?php _e('All emails created with our mailbuilder are stored as custom post types. You can manage them at the link above','wp-html-mail'); ?>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
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
                        <p class="description">
                            <?php _e("If you don't use a child theme and need an update save place to store your email template you can also copy the template file from the plugin to <strong>wp-content/uploads/wp-html-mail/template.html</strong>.",'wp-html-mail'); ?>
                        </p>
                        <div id="haet_mail_template_created" class="haet-mail-dialog" title="<?php _e('Template created','wp-html-mail'); ?>">
                            <p>
                                <?php _e('Your template has been created.','wp-html-mail'); ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <?php if( $is_able_to_use_new_editor ): ?>
                    <tr valign="top">
                        <th scope="row"><label><?php _e('Old template editor','wp-html-mail') ?></label></th>
                        <td>
                            <input type="hidden" name="haet_mail[use_classic_template_editor]" value="0">
                            <input type="checkbox" id="haet_mail_use_classic_template_editor" name="haet_mail[use_classic_template_editor]" value="1" <?php echo ( isset( $options['use_classic_template_editor'] ) && $options['use_classic_template_editor']==1 ?'checked':''); ?>>
                            <label for="haet_mail_use_classic_template_editor"><?php _e('Go back to our old editor if you don\'t like our new JavaScript based template designer.','wp-html-mail'); ?></label>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr valign="top">
                    <th scope="row"><label><?php _e('E-Mail test mode','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail[testmode]" value="0">
                        <input type="checkbox" id="haet_mail_testmode" name="haet_mail[testmode]" value="1" <?php echo ( isset( $options['testmode'] ) && $options['testmode']==1 ?'checked':''); ?>>
                        <label for="haet_mail_testmode"><?php _e('enable test mode','wp-html-mail'); ?></label>
                        <div class="collapse-testmode">
                            <input type="text" id="haet_mail_testmode_recipient" name="haet_mail[testmode_recipient]" placeholder="you@example.org" value="<?php echo ( isset( $options['testmode_recipient'] ) ? $options['testmode_recipient'] : '' ); ?>">
                            <br>
                            <input type="hidden" name="haet_mail[debugmode]" value="0">
                            <input type="checkbox" id="haet_mail_debugmode" name="haet_mail[debugmode]" value="1" <?php echo ( isset( $options['debugmode'] ) && $options['debugmode']==1 ?'checked':''); ?>>
                            <label for="haet_mail_debugmode"><?php _e('also enable debug outputs','wp-html-mail'); ?></label>
                        </div>
                        <p class="description">
                            <?php _e('Enable email test mode to redirect all messages to your own email address.','wp-html-mail'); ?>
                       </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>