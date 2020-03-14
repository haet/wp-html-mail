<div class="postbox haet-mail-survey">
    <h3 class="hndle"><span><?php _e('Help us improve WP HTML Mail','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <input type="hidden" name="haet_mail[survey2020_completed]" value="1">

                <?php
                $plugin_data = get_plugin_data( HAET_MAIL_PATH.'/wp-html-mail.php' );
                ?>
                <input type="hidden" name="haet_survey_version" value="<?php echo $plugin_data['Version']; ?>"> 
                <?php 
                $survey_step = 1;
                if( isset($_POST['haet_survey_next']) )
                    $survey_step = 2;
                if( isset($_POST['haet_survey_send']) )
                    $survey_step = 3;

                if( $survey_step == 1 ):
                    ?>
                    <input type="hidden" name="haet_survey_site_key" value="<?php echo md5( get_bloginfo('name').get_bloginfo('url') ); ?>"> 
                    <?php /* we need this to detect multiple submissions be we anonymize your data with a hash */ ?>
                    <tr valign="top">
                        <th scope="row">
                            <label for="haet_survey_rating"><?php _e('How do you like WP HTML Mail so far?','wp-html-mail'); ?></label>
                            <p class="description"><?php _e('Your overall impression.','wp-html-mail'); ?></p>
                        </th>
                        <td>
                            <div class="haet-star-rating">
                                <?php 
                                $rating = ( isset( $_GET['rating'] ) ? $_GET['rating'] : 0 );
                                if( !is_numeric($rating) )
                                    $rating = 0;
                                ?>
                                <input type="hidden" class="" id="haet_survey_rating" name="haet_survey_rating" value="<?php echo $rating; ?>">
                                <a href="#" class="dashicons dashicons-star-<?php echo ( $rating >= 1 ? 'filled' : 'empty' ); ?>" data-rating="1"></a>
                                <a href="#" class="dashicons dashicons-star-<?php echo ( $rating >= 2 ? 'filled' : 'empty' ); ?>" data-rating="2"></a>
                                <a href="#" class="dashicons dashicons-star-<?php echo ( $rating >= 3 ? 'filled' : 'empty' ); ?>" data-rating="3"></a>
                                <a href="#" class="dashicons dashicons-star-<?php echo ( $rating >= 4 ? 'filled' : 'empty' ); ?>" data-rating="4"></a>
                                <a href="#" class="dashicons dashicons-star-<?php echo ( $rating >= 5 ? 'filled' : 'empty' ); ?>" data-rating="5"></a>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="haet_survey_email_result"><?php _e('How satisfied are you with your final email template?','wp-html-mail'); ?></label>
                            <p class="description"><?php _e('Does ist look as expected?','wp-html-mail'); ?></p>
                        </th>
                        <td>
                            <div class="haet-star-rating">
                                <input type="hidden" class="" id="haet_survey_email_result" name="haet_survey_email_result" value="0">
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="1"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="2"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="3"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="4"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="5"></a>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="haet_survey_usability"><?php _e('How would you rate the configuration usability?','wp-html-mail'); ?></label>
                            <p class="description"><?php _e('Is it clear and comprehensive?','wp-html-mail'); ?></p>
                        </th>
                        <td>
                            <div class="haet-star-rating">
                                <input type="hidden" class="" id="haet_survey_usability" name="haet_survey_usability" value="0">
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="1"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="2"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="3"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="4"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="5"></a>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="haet_survey_features"><?php _e('What about the styling features?','wp-html-mail'); ?></label>
                            <p class="description"><?php _e('Did you find everything you need?','wp-html-mail'); ?></p>
                        </th>
                        <td>
                            <div class="haet-star-rating">
                                <input type="hidden" class="" id="haet_survey_features" name="haet_survey_features" value="0">
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="1"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="2"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="3"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="4"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="5"></a>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="haet_survey_compatibility"><?php _e('How did the plugin integrate with other plugins and themes?','wp-html-mail'); ?></label>
                            <p class="description"><?php _e('You can suggest plugins to integrate in the next step.','wp-html-mail'); ?></p>
                        </th>
                        <td>
                            <div class="haet-star-rating">
                                <input type="hidden" class="" id="haet_survey_compatibility" name="haet_survey_compatibility" value="0">
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="1"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="2"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="3"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="4"></a>
                                <a href="#" class="dashicons dashicons-star-empty" data-rating="5"></a>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                        </th>
                        <td>
                            <input type="submit" name="haet_survey_next" class="button-primary" value="<?php _e('Submit', 'wp-html-mail') ?>" />
                        </td>
                    </tr>
                <?php elseif( $survey_step == 2 ): ?>
                    <?php 
                        $rating = $_POST['haet_survey_rating'];
                        $email_result = $_POST['haet_survey_email_result'];
                        $usability = $_POST['haet_survey_usability'];
                        $features = $_POST['haet_survey_features'];
                        $compatibility = $_POST['haet_survey_compatibility'];


                        $response = wp_remote_post( 'https://survey.etzelstorfer.com/survey/wphtmlmail/' , array( 'body' => $_POST ) );
                        // if ( is_wp_error( $response ) ) {
                        //    $error_message = $response->get_error_message();
                        //    echo "Something went wrong: $error_message";
                        // } else {
                        //    echo 'Response:<pre>';
                        //    print_r( $response );
                        //    echo '</pre>';
                        // }
                    ?>
                    <input type="hidden" name="haet_survey_site_key" value="<?php echo md5( get_bloginfo('name').get_bloginfo('url') ); ?>"> 
                    <?php /* we need this to detect multiple submissions be we anonymize your data with a hash */ ?>
                    <input type="hidden" class="" id="haet_survey_rating" name="haet_survey_rating" value="<?php echo $rating; ?>">
                    <input type="hidden" class="" id="haet_survey_email_result" name="haet_survey_email_result" value="<?php echo $email_result; ?>">
                    <input type="hidden" class="" id="haet_survey_usability" name="haet_survey_usability" value="<?php echo $usability; ?>">
                    <input type="hidden" class="" id="haet_survey_features" name="haet_survey_features" value="<?php echo $features; ?>">
                    <input type="hidden" class="" id="haet_survey_compatibility" name="haet_survey_compatibility" value="<?php echo $compatibility; ?>">

                    <?php if( $email_result > 0 && $email_result < 3 ): ?>
                        <tr valign="top">
                            <td>
                                <label for="haet_survey_email_result_details">
                                    <h4><?php _e('What do you think might help you to be more satisfied with your final email template?','wp-html-mail'); ?></h4>
                                </label>
                                
                                <textarea id="haet_survey_email_result_details" name="haet_survey_email_result_details"></textarea>
                            </td>
                        </tr>
                    <?php endif; ?>   

                    <?php if( $usability > 0 && $usability < 3 ): ?>
                        <tr valign="top">
                            <td>
                                <label for="haet_survey_usability_details">
                                    <h4><?php _e('Any details you want me to improve regarding usability?','wp-html-mail'); ?></h4>
                                </label>
                                
                                <textarea id="haet_survey_usability_details" name="haet_survey_usability_details"></textarea>
                            </td>
                        </tr>
                    <?php endif; ?>  

                    
                    <tr valign="top">
                        <td>
                            <h4><?php _e('Which of your plugins send emails with WP HTML Mail?','wp-html-mail'); ?></h4>
                            <?php 
                            if ( ! function_exists( 'get_plugins' ) ) {
                                require_once ABSPATH . 'wp-admin/includes/plugin.php';
                            }

                            $plugins = get_plugins();
                            foreach ($plugins as $plugin): 
                                if( $plugin['Name'] != 'WP HTML Mail' ): ?>
                                    <label>
                                        <input type="checkbox" name="haet_survey_plugins[]" value="<?php echo $plugin['Name'] . '|||' . $plugin['Version'] . '|||' . $plugin['PluginURI']; ?>" <?php echo ( stripos($plugin['Name'], 'WP HTML Mail') !== false ? ' checked="checked" disabled="disabled" ' : '' ); ?>>
                                        <?php echo $plugin['Name']; ?>
                                    </label>
                                    <br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>

                    <tr valign="top">
                        <td>
                            <label for="haet_survey_additional_plugins">
                                <h4><?php _e('Are there any plugins you want to see supported in future versions?','wp-html-mail'); ?></h4>
                            </label>
                            
                            <textarea id="haet_survey_additional_plugins" name="haet_survey_additional_plugins"></textarea>
                        </td>
                    </tr>

                    <tr valign="top">
                        <td>
                            <h4><?php _e('What are the features you would like to see in future versions?','wp-html-mail'); ?></h4>
                            
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Templates">
                                <?php _e('A set of ready to use templates','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Webfonts">
                                <?php _e('Custom Webfonts','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Mobile Preview">
                                <?php _e('Mobile email preview','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Social Icons">
                                <?php _e('Social media icons','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Attachments">
                                <?php _e('Attachments','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="haet_survey_features_new[]" value="Conditions">
                                <?php _e('Conditions to turn the template on and off for some emails','wp-html-mail'); ?>
                            </label>
                            <br>
                            <label>
                                <?php _e('Other:','wp-html-mail'); ?>
                            </label>
                            <input type="text" name="haet_survey_features_new_other">
                        </td>
                    </tr>

                    <?php if( $rating > 3 ): ?>
                        <tr valign="top">
                            <td>
                                <label for="haet_survey_testimonial">
                                    <h4><?php _e('I want to add some testimonials to my website.<br>Would you mind writing a few lines to inspire other customers to use the plugin?','wp-html-mail'); ?></h4>
                                </label>
                                
                                <textarea id="haet_survey_testimonial" name="haet_survey_testimonial"></textarea>
                                <br>
                                <label for="haet_survey_testimonial_name" class="testimonial-inline-label">
                                    <?php _e('Your name, company and job description:','wp-html-mail'); ?>
                                </label>
                                <input type="text" name="haet_survey_testimonial_name" id="haet_survey_testimonial_name" class="testimonial-inline-field">
                                <br>
                                <label for="haet_survey_testimonial_link" class="testimonial-inline-label">
                                    <?php _e('Website / Twitter profile:','wp-html-mail'); ?>
                                </label>
                                <input type="text" name="haet_survey_testimonial_link" id="haet_survey_testimonial_link" class="testimonial-inline-field">
                                <br>
                                <label for="haet_survey_testimonial_email" class="testimonial-inline-label">
                                    <?php _e('Email for questions (optional):','wp-html-mail'); ?>
                                </label>
                                <input type="text" name="haet_survey_testimonial_email" id="haet_survey_testimonial_email" class="testimonial-inline-field">
                            </td>
                        </tr>
                    <?php endif; ?>  
                    <tr valign="top">
                        <td>
                            <input type="submit" name="haet_survey_send" class="button-primary" value="<?php _e('Submit', 'wp-html-mail') ?>" />
                        </td>
                    </tr>
                <?php elseif( $survey_step == 3 ): ?>
                    <?php 
                        $rating = $_POST['haet_survey_rating'];
                        $email_result = $_POST['haet_survey_email_result'];
                        $usability = $_POST['haet_survey_usability'];
                        $features = $_POST['haet_survey_features'];
                        $compatibility = $_POST['haet_survey_compatibility'];

                        $response = wp_remote_post( 'https://survey.etzelstorfer.com/survey/wphtmlmail/' , array( 'body' => $_POST ) );
                        // if ( is_wp_error( $response ) ) {
                        //    $error_message = $response->get_error_message();
                        //    echo "Something went wrong: $error_message";
                        // } else {
                        //    echo 'Response:<pre>';
                        //    print_r( $response );
                        //    echo '</pre>';
                        // }
                    ?>
                    <tr valign="top">
                        <td>
                            <h3><?php _e('Thank you for your feedback!','wp-html-mail'); ?></h3>
                            <p><?php _e('This will help us to constantly improve the plugin.','wp-html-mail'); ?></p>
                            <br>
                            <h4><?php _e('If you want to buy some of our plugins you can use the following discount code to get 20% off.','wp-html-mail'); ?></h4>
                            <pre>SURVEY2345345</pre>
                            <a href="https://codemiq.com/en/wordpress-plugins/" target="_blank">
                                <?php _e('Go to our website','wp-html-mail'); ?>
                            </a>
                            <br><br><br>
                            <?php if( $rating > 3 ): ?>
                                <a class="button-primary" href="https://wordpress.org/support/plugin/wp-html-mail/reviews/?filter=5" target="_blank">
                                    <span class="dashicons dashicons-wordpress-alt"></span>
                                    <?php _e('Leave a review on WordPress.org','wp-html-mail'); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>