<?php
/**
 * Design settings for email header
 * has been replaced by the email designer in 3.0 and will be removed in a future version
 */
?>
<div class="postbox">
    <h3 class="hndle"><span><?php _e('Header','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                
                <tr valign="top">
                    <th scope="row"><label for="haet_mailheaderimg_placement"><?php _e('Image and text placement','wp-html-mail'); ?></label></th>
                    <td>
                        <?php 
                        $placement_options = [
                                'just_text'     =>  __('Show just a text header','wp-html-mail'),
                                'replace_text'  =>  __('Show image only (use text as alt attribute)','wp-html-mail'),
                                'above_text'    =>  __('Show image above text','wp-html-mail'),
                                'below_text'    =>  __('Show image below text','wp-html-mail'),
                            ];
                        ?>
                        <select id="haet_mailheaderimg_placement" name="haet_mail_theme[headerimg_placement]">
                            <?php foreach ( $placement_options as $key => $label ) :?>
                                <option value="<?php echo $key; ?>" <?php echo ($theme_options['headerimg_placement']==$key?'selected':''); ?>>
                                    <?php echo $label; ?>
                                </option>        
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailheaderbackground"><?php _e('Background color','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mailheaderbackground" name="haet_mail_theme[headerbackground]" value="<?php echo $theme_options['headerbackground']; ?>">
                    </td>
                </tr>
                <tr class="collapse-headerimg">
                    <th scope="row">
                        <label for="haet_mailheaderimg"><?php _e('Header image','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <div class="uploader">
                            <?php 
                            $headerimg = $this->multilanguage->get_translateable_theme_option( $theme_options, 'headerimg' );
                            $headerimg_field_key = $this->multilanguage->get_translateable_theme_options_key( $theme_options, 'headerimg' );
                            ?>
                            <input id="haet_mailheaderimg" name="haet_mail_theme[<?php echo $headerimg_field_key; ?>]" type="text" value="<?php echo $headerimg; ?>"/>
                            <input id="haet_mailfilepicker" class="button upload_image_button" name="haet_mailfilepicker" type="text" value="<?php _e('Select image','wp-html-mail'); ?>" />
                            <span class="dashicons dashicons-editor-help haet-mail-info-icon" data-tooltip="<?php esc_attr_e( 'Add your logo or image header (optional). max 600px wide','wp-html-mail' ); ?>"></span>
                            <input type="checkbox" id="haet_mail_headerimg_advanced" class="haet-toggle" value="1">
                            <label for="haet_mail_headerimg_advanced"><span class="dashicons dashicons-admin-generic"></span></label>
                            <div class="collapse-headerimg_advanced">
                                <label for="haet_mailheaderimg_width"><?php _e('width','wp-html-mail'); ?></label>
                                <input id="haet_mailheaderimg_width" name="haet_mail_theme[headerimg_width]" type="number" value="<?php echo $theme_options['headerimg_width']; ?>"/>px
                                <label for="haet_mailheaderimg_height"><?php _e('height','wp-html-mail'); ?></label>
                                <input id="haet_mailheaderimg_height" name="haet_mail_theme[headerimg_height]" type="number" value="<?php echo $theme_options['headerimg_height']; ?>"/>px
                                <span class="dashicons dashicons-editor-help haet-mail-info-icon" data-tooltip="<?php esc_attr_e( 'If you would like to provide the header image in retina quality, upload an image with a higher resultion, such as for example 1200px x 400px and enter only half the size values in the input fields on the left (600 x 200).','wp-html-mail' ); ?>"></span>

                                <span class="headerimg-align">
                                    <?php _e('align','wp-html-mail'); ?>
                                    <input type="radio" name="haet_mail_theme[headerimg_align]" class="haet-toggle" id="haet_mailheaderimg_align_left" value="left" <?php echo ($theme_options['headerimg_align']=="left"?"checked":""); ?>>
                                    <label for="haet_mailheaderimg_align_left"><span class="dashicons dashicons-arrow-left"></span></label>

                                    <input type="radio" name="haet_mail_theme[headerimg_align]" class="haet-toggle" id="haet_mailheaderimg_align_center" value="center" <?php echo ($theme_options['headerimg_align']=="center"?"checked":""); ?>>
                                    <label for="haet_mailheaderimg_align_center"><span class="dashicons dashicons-leftright"></span></label>

                                    <input type="radio" name="haet_mail_theme[headerimg_align]" class="haet-toggle" id="haet_mailheaderimg_align_right" value="right" <?php echo ($theme_options['headerimg_align']=="right"?"checked":""); ?>>
                                    <label for="haet_mailheaderimg_align_right"><span class="dashicons dashicons-arrow-right"></span></label>
                                </span>
                            </div>
                            <?php if( $this->multilanguage->is_multilanguage_site() ): ?>
                            <input type="hidden" name="haet_mail_theme[headerimg_enable_translation]" value="0">
                            <input type="checkbox" id="haet_mail_theme_headerimg_enable_translation" name="haet_mail_theme[headerimg_enable_translation]" value="1" <?php echo (isset($theme_options['headerimg_enable_translation']) && $theme_options['headerimg_enable_translation']==1 ?'checked':''); ?>>
                            <label for="haet_mail_theme_headerimg_enable_translation">
                                <?php _e('Enable translation for this field','wp-html-mail'); ?> 
                                <span class="dashicons dashicons-editor-help haet-mail-info-icon" data-tooltip="<?php esc_attr_e( 'If enabled you can use individual settings depending on the current language selected at the top of the page in your admin bar.','wp-html-mail' ); ?>"></span>
                            </label>
                        <?php endif; ?>
                        </div>
                        <?php if( isset( $theme_options['headerimg_notice'] ) && $theme_options['headerimg_notice'] && stripos( $headerimg, 'templates.wp-html-mail.com' ) ): ?>
                            <p class="description"><?php echo $theme_options['headerimg_notice']; ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailheaderpadding"><?php _e('Padding','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php _e('top','wp-html-mail'); ?>:
                        <select  id="haet_mailheaderpaddingtop" name="haet_mail_theme[headerpaddingtop]">
                            <?php for ($padding=0; $padding<=50; $padding++) :?>
                                <option value="<?php echo $padding; ?>" <?php echo ($theme_options['headerpaddingtop']==$padding?'selected':''); ?>><?php echo $padding; ?></option>        
                            <?php endfor; ?>
                        </select>
                        &nbsp;&nbsp; 
                        <?php _e('right','wp-html-mail'); ?>:
                        <select  id="haet_mailheaderpaddingright" name="haet_mail_theme[headerpaddingright]">
                            <?php for ($padding=0; $padding<=50; $padding++) :?>
                                <option value="<?php echo $padding; ?>" <?php echo ($theme_options['headerpaddingright']==$padding?'selected':''); ?>><?php echo $padding; ?></option>      
                            <?php endfor; ?>
                        </select>
                        &nbsp;&nbsp; 
                        <?php _e('bottom','wp-html-mail'); ?>:
                        <select  id="haet_mailheaderpaddingbottom" name="haet_mail_theme[headerpaddingbottom]">
                            <?php for ($padding=0; $padding<=50; $padding++) :?>
                                <option value="<?php echo $padding; ?>" <?php echo ($theme_options['headerpaddingbottom']==$padding?'selected':''); ?>><?php echo $padding; ?></option>     
                            <?php endfor; ?>
                        </select>
                        &nbsp;&nbsp; 
                        <?php _e('left','wp-html-mail'); ?>:
                        <select  id="haet_mailheaderpaddingleft" name="haet_mail_theme[headerpaddingleft]">
                            <?php for ($padding=0; $padding<=50; $padding++) :?>
                                <option value="<?php echo $padding; ?>" <?php echo ($theme_options['headerpaddingleft']==$padding?'selected':''); ?>><?php echo $padding; ?></option>       
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailheadertext"><?php _e('Header text','wp-html-mail'); ?> <?php $this->multilanguage->maybe_print_language_label( $theme_options, 'footer' ); ?></label>
                    </th>
                    <td>
                        <?php 
                        $headertext = $this->multilanguage->get_translateable_theme_option( $theme_options, 'headertext' );
                        $headertext_field_key = $this->multilanguage->get_translateable_theme_options_key( $theme_options, 'headertext' );
                        ?>
                        <input type="text" value="<?php echo $headertext; ?>" id="haet_mailheadertext" name="haet_mail_theme[<?php echo $headertext_field_key; ?>]">     
                        <?php if( $this->multilanguage->is_multilanguage_site() ): ?>
                            <input type="hidden" name="haet_mail_theme[headertext_enable_translation]" value="0">
                            <input type="checkbox" id="haet_mail_theme_headertext_enable_translation" name="haet_mail_theme[headertext_enable_translation]" value="1" <?php echo (isset($theme_options['headertext_enable_translation']) && $theme_options['headertext_enable_translation']==1 ?'checked':''); ?>>
                            <label for="haet_mail_theme_headertext_enable_translation">
                                <?php _e('Enable translation for this field','wp-html-mail'); ?> 
                                <span class="dashicons dashicons-editor-help haet-mail-info-icon" data-tooltip="<?php esc_attr_e( 'If enabled you can use individual settings depending on the current language selected at the top of the page in your admin bar.','wp-html-mail' ); ?>"></span>
                            </label>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailheaderfont"><?php _e('Font','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_theme[headerfont]',
                                    'value' =>  $theme_options['headerfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_theme[headerfontsize]',
                                    'value' =>  $theme_options['headerfontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_theme[headercolor]',
                                    'value' =>  $theme_options['headercolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_theme[headerbold]',
                                    'value' =>  $theme_options['headerbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_theme[headeritalic]',
                                    'value' =>  $theme_options['headeritalic']
                                    ),
                                'align' =>  array(
                                    'name'  =>  'haet_mail_theme[headeralign]',
                                    'value' =>  $theme_options['headeralign']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>