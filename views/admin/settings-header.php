<div class="postbox">
    <h3 class="hndle"><span><?php _e('Header','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailheaderbackground"><?php _e('Background color','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mailheaderbackground" name="haet_mail_theme[headerbackground]" value="<?php echo $theme_options['headerbackground']; ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailheaderimg"><?php _e('Header image','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <div class="uploader">
                            <input id="haet_mailheaderimg" name="haet_mail_theme[headerimg]" type="text" value="<?php echo $theme_options['headerimg']; ?>"/>
                            <input id="haet_mailfilepicker" class="button upload_image_button" name="haet_mailfilepicker" type="text" value="<?php _e('Select image','wp-html-mail'); ?>" />
                            <input id="haet_mailheaderimg_width" name="haet_mail_theme[headerimg_width]" type="hidden" value="<?php echo $theme_options['headerimg_width']; ?>"/>
                            <input id="haet_mailheaderimg_height" name="haet_mail_theme[headerimg_height]" type="hidden" value="<?php echo $theme_options['headerimg_height']; ?>"/>
                        </div>
                        <p class="description"><?php _e('Add your logo or image header (optional). max 600px wide','wp-html-mail'); ?></p>
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
                        <label for="haet_mailheadertext"><?php _e('Header text','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $theme_options['headertext']; ?>" id="haet_mailheadertext" name="haet_mail_theme[headertext]">     
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