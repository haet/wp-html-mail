<?php
/**
 * Design settings for email content
 * has been replaced by the email designer in 3.0 and will be removed in a future version
 */
?>
<div class="postbox">
    <h3 class="hndle"><span><?php _e('Content','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="haet_mailcontentbackground"><?php _e('Background color','wp-html-mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mailcontentbackground" name="haet_mail_theme[contentbackground]" value="<?php echo $theme_options['contentbackground']; ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailheadlinefont"><?php _e('Headline Font','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_theme[headlinefont]',
                                    'value' =>  $theme_options['headlinefont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_theme[headlinefontsize]',
                                    'value' =>  $theme_options['headlinefontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_theme[headlinecolor]',
                                    'value' =>  $theme_options['headlinecolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_theme[headlinebold]',
                                    'value' =>  $theme_options['headlinebold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_theme[headlineitalic]',
                                    'value' =>  $theme_options['headlineitalic']
                                    ),
                                'texttransform'  =>  array(
                                    'name'  =>  'haet_mail_theme[headlinetexttransform]',
                                    'value' =>  $theme_options['headlinetexttransform']
                                    ),
                                'align' =>  array(
                                    'name'  =>  'haet_mail_theme[headlinealign]',
                                    'value' =>  $theme_options['headlinealign']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailsubheadlinefont"><?php _e('Subsubheadline Font','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinefont]',
                                    'value' =>  $theme_options['subheadlinefont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinefontsize]',
                                    'value' =>  $theme_options['subheadlinefontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinecolor]',
                                    'value' =>  $theme_options['subheadlinecolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinebold]',
                                    'value' =>  $theme_options['subheadlinebold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlineitalic]',
                                    'value' =>  $theme_options['subheadlineitalic']
                                    ),
                                'texttransform'  =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinetexttransform]',
                                    'value' =>  $theme_options['subheadlinetexttransform']
                                    ),
                                'align' =>  array(
                                    'name'  =>  'haet_mail_theme[subheadlinealign]',
                                    'value' =>  $theme_options['subheadlinealign']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailtextfont"><?php _e('Content Font','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_theme[textfont]',
                                    'value' =>  $theme_options['textfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_theme[textfontsize]',
                                    'value' =>  $theme_options['textfontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_theme[textcolor]',
                                    'value' =>  $theme_options['textcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_theme[textbold]',
                                    'value' =>  $theme_options['textbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_theme[textitalic]',
                                    'value' =>  $theme_options['textitalic']
                                    ),
                                'align' =>  array(
                                    'name'  =>  'haet_mail_theme[textalign]',
                                    'value' =>  $theme_options['textalign']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mailtextfont"><?php _e('Link styling','wp-html-mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'color' =>  array(
                                    'name'  =>  'haet_mail_theme[linkcolor]',
                                    'value' =>  $theme_options['linkcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_theme[linkbold]',
                                    'value' =>  $theme_options['linkbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_theme[linkitalic]',
                                    'value' =>  $theme_options['linkitalic']
                                    ),
                                'texttransform'  =>  array(
                                    'name'  =>  'haet_mail_theme[linktexttransform]',
                                    'value' =>  $theme_options['linktexttransform']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>