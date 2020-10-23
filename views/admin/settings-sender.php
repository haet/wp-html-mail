<?php
/**
 * Set the email sender
 * added in 3.0
 */
?>
<div class="postbox">
    <h3 class="hndle"><span><?php _e('Email sender','wp-html-mail'); ?></span></h3>
    <div class="inside">
        <table class="form-table">
            <tbody>
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
                <tr valign="top">
                    <th scope="row"><label><?php _e('Overwrite sender by default?','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail[disable_sender]" value="0">
                        <input type="checkbox" id="haet_mail_disable_sender" name="haet_mail[disable_sender]" value="1" <?php echo ( isset( $options['disable_sender'] ) && $options['disable_sender']==1 ?'checked':''); ?>>
                        <label for="haet_mail_disable_sender"><?php _e('Do not change email sender by default','wp-html-mail'); ?></label>
                        <p class="description"><?php _e('Normally we change the sender name and sender address of all emails. You can customize this for each plugin on tab "Plugins" but you can also set a default value here.','wp-html-mail'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div> 