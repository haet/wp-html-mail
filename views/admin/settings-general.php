<?php
/**
 * Email sender an background color
 * has been replaced by the email designer and the tab "sender" in 3.0 and will be removed in a future version
 */
?>
<div class="postbox">
    <h3 class="hndle"><span><?php _e('General','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailbackground"><?php _e('Background color','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mailbackground" name="haet_mail_theme[background]" value="<?php echo $theme_options['background']; ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailfromname"><?php _e('Email sender name','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" id="haet_mailfromname" name="haet_mail[fromname]" value="<?php echo stripslashes($options['fromname']); ?>" required>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailfromaddress"><?php _e('Email sender address','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" id="haet_mailfromaddress" name="haet_mail[fromaddress]" value="<?php echo $options['fromaddress']; ?>" required>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>