<?php
/**
 * Design settings for email footer
 * has been replaced by the email designer in 3.0 and will be removed in a future version
 */
?>
<div class="postbox">
    <h3 class="hndle"><span><?php _e('Footer','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailfooterbackground"><?php _e('Background color','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mailfooterbackground" name="haet_mail_theme[footerbackground]" value="<?php echo $theme_options['footerbackground']; ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailfooter"><?php _e('Footer','wp-html-mail'); ?> <?php $this->multilanguage->maybe_print_language_label( $theme_options, 'footer' ); ?></label></th>
                    <td>
                        <?php 
                            $footer = $this->multilanguage->get_translateable_theme_option( $theme_options, 'footer' );
                            $footer_field_key = $this->multilanguage->get_translateable_theme_options_key( $theme_options, 'footer' );
                            wp_editor(stripslashes(str_replace('\\&quot;','',$footer)),'haet_mailfooter',array('textarea_name'=>'haet_mail_theme[' . $footer_field_key . ']','wpautop'=>false,'textarea_rows'=>6));
                        ?>
                        <?php if( $this->multilanguage->is_multilanguage_site() ): ?>
                            <input type="hidden" name="haet_mail_theme[footer_enable_translation]" value="0">
                            <input type="checkbox" id="haet_mail_theme_footer_enable_translation" name="haet_mail_theme[footer_enable_translation]" value="1" <?php echo (isset($theme_options['footer_enable_translation']) && $theme_options['footer_enable_translation']==1 ?'checked':''); ?>>
                            <label for="haet_mail_theme_footer_enable_translation">
                                <?php _e('Enable translation for this field','wp-html-mail'); ?> 
                                <span class="dashicons dashicons-editor-help haet-mail-info-icon" data-tooltip="<?php esc_attr_e( 'If enabled you can use individual settings depending on the current language selected at the top of the page in your admin bar.','wp-html-mail' ); ?>"></span>
                            </label>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>