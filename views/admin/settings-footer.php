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
                    <th scope="row"><label for="haet_mailfooter"><?php _e('Footer','wp-html-mail'); ?></label></th>
                    <td>
                        <?php 
                            wp_editor(stripslashes(str_replace('\\&quot;','',$theme_options['footer'])),'haet_mailfooter',array('textarea_name'=>'haet_mail_theme[footer]','wpautop'=>false,'textarea_rows'=>6));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="haet_mail_theme[footerlink]" value="0">
                        <input type="checkbox" id="haet_mail_theme_footerlink" name="haet_mail_theme[footerlink]" value="1" <?php echo ($theme_options['footerlink']==1 || !isset($theme_options['footerlink'])?'checked':''); ?>>
                        <label for="haet_mail_theme_footerlink"><?php _e('Show "powered by" link in email footer','wp-html-mail'); ?></label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>