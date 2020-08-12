<?php if ( ! defined( 'ABSPATH' ) ) exit;
require_once HAET_MAIL_PATH . 'includes/class-contenttype.php';
require_once HAET_MAIL_PATH . 'includes/class-contenttype-text.php';
require_once HAET_MAIL_PATH . 'includes/class-contenttype-twocol.php';
//require_once HAET_MAIL_PATH . 'includes/class-contenttype-socialicons.php';

final class Haet_Mail_Builder
{
    const VERSION = '';

    private static $instance;

    /**
     * Plugin DIRECTORY
     */
    public static $dir = '';

    /**
     * Plugin URL
     */
    public static $url = '';
    
    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof Haet_Mail_Builder)) {
            self::$instance = new Haet_Mail_Builder();

            self::$dir = plugin_dir_path(__FILE__);

            self::$url = plugin_dir_url(__FILE__);
        }

        return self::$instance;
    }

    public function __construct(){
        add_action( 'init', array($this,'register_post_type') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles') );
        add_action( 'save_post', array($this,'save_post') );
        add_filter( 'tiny_mce_before_init', array($this, 'customize_editor_toolbar' ) );  
        add_filter( 'mce_external_plugins', array( $this, 'tinymce_external_plugins' ) );

        add_filter( 'haet_mail_modify_styled_mail', array( $this, 'modify_styled_mail' ) );

        // make post type translateabble if polylang is active
        add_filter( 'pll_get_post_types', function( $post_types, $is_settings ) {
            if ( $is_settings ) {
                // hides from the list of custom post types in Polylang settings
                unset( $post_types['wphtmlmail_mail'] );
            } else {
                // enables language and translation management
                $post_types['wphtmlmail_mail'] = 'wphtmlmail_mail';
            }
            $post_types['wphtmlmail_mail'] = 'wphtmlmail_mail';
            return $post_types;
        }, 10, 2 );
    }

    public function register_post_type(){        
        $labels = array(
            'name'                => __( 'custom emails', 'wp-html-mail' ),
            'singular_name'       => __( 'custom email', 'wp-html-mail' ),
            'add_new'             => __( 'Add New Custom Email', 'wp-html-mail' ),
            'add_new_item'        => __( 'Add New Custom Email', 'wp-html-mail' ),
            'edit_item'           => __( 'Edit Custom Email', 'wp-html-mail' ),
            'new_item'            => __( 'New Custom Email', 'wp-html-mail' ),
            'view_item'           => __( 'View Custom Email', 'wp-html-mail' ),
            'search_items'        => __( 'Search Custom Emails', 'wp-html-mail' ),
            'not_found'           => __( 'No Custom Emails found', 'wp-html-mail' ),
            'not_found_in_trash'  => __( 'No Custom Emails found in Trash', 'wp-html-mail' ),
            'parent_item_colon'   => __( 'Parent Custom Email:', 'wp-html-mail' ),
            'menu_name'           => __( 'Custom Emails', 'wp-html-mail' ),
        );
    
        $args = array(
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'description',
            'taxonomies'          => array(),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => null,
            'menu_icon'           => null,
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => false,
            'can_export'          => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'register_meta_box_cb'=> array($this,'setup_meta_boxes'),
            'supports'            => array('')
        );
    
        register_post_type( 'wphtmlmail_mail', $args );
    }





    public function setup_meta_boxes(){
        add_meta_box( 'header', __( 'Message', 'wp-html-mail' ),
                        array($this,'render_header_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );
        add_meta_box( 'subject', __( 'Email Subject', 'wp-html-mail' ),
                        array($this,'render_subject_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );
        add_meta_box( 'mailbuilder', __( 'Email Content Builder', 'wp-html-mail' ),
                        array($this,'render_mailbuilder_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );

        add_meta_box ( 'mb_attachments_metabox_fullwidth', __( 'Attach files', 'haet_mail' ), array($this,'render_attachments_meta_box'), 'wphtmlmail_mail', 'normal', 'default' );
    }





    public function render_header_meta_box(){
        global $post;
        ?>
        <div class="haet-mailbuilder-header clearfix">
            <h4 class="email-name">
                <?php echo str_replace('_',' ', str_replace('WC_Email_', '', get_the_title( ) ) ); ?>
            </h4>
            <div class="header-buttons">
                <?php do_action( 'haet_mailbuilder_header_buttons', $post ); ?>
                <button type="submit" class="button button-primary"><?php _e('Update'); ?></button>
            </div>
        </div>
        <?php
    }




    public function render_subject_meta_box(){
        global $post;
        $value = get_post_meta( $post->ID, 'subject', true );
        echo '<input type="text" name="subject" value="' . htmlentities( $value ) . '">';
    }


    public function render_attachments_meta_box(){
        global $post;
        $attachments = get_post_meta( $post->ID, 'mailbuilder_attachments', true );
        $attachments = str_replace( "'", "&apos;", $attachments );
        ?>
        <div class="mb-preview-attachments"></div>
        <input type="hidden" name="mb_attachments" id="mb_attachments" value="0">
        <div class="uploader">
            <input id="mailbuilder_attachments" name="mailbuilder_attachments" type="hidden" value='<?php echo $attachments; ?>'/>
            <button class="button upload_attachment_button" type="button">
                <span class="dashicons dashicons-paperclip"></span>
                <?php _e('Add attachments','haet_mail'); ?>
            </button>
        </div>
        <?php
    }


    private function output_template_styles_for_mailbuilder( $template, $custom_css_desktop = '', $custom_css_mobile = '' ){
        preg_match_all("/<style>(.*)<\/style>/smU", $template, $output_array);
        if( count( $output_array ) > 1 && count( $output_array[1] ) > 0 ){
            $css = $output_array[1][0];

            $css = str_replace('/**** ADD CSS HERE ****/', $custom_css_desktop . '/**** ADD CSS HERE ****/', $css);
            $css = str_replace('/**** ADD MOBILE CSS HERE ****/', $custom_css_mobile . '/**** ADD MOBILE CSS HERE ****/', $css);

            require_once(HAET_MAIL_PATH.'/vendor/autoload.php');
            $mailbilder_id = "#mailbuilder-editor";
            $css_parser = new Sabberworm\CSS\Parser($css);
            $css_object = $css_parser->parse();
            foreach($css_object->getAllDeclarationBlocks() as $css_block) {
                foreach($css_block->getSelectors() as $css_selector) {
                    //Loop over all selector parts (the comma-separated strings in a selector) and prepend the id
                    if( $css_selector->getSelector() == 'body' )
                        $css_selector->setSelector($mailbilder_id);
                    else
                        $css_selector->setSelector($mailbilder_id.' '.$css_selector->getSelector());
                }
            }
            echo '<style>' . $css_object->render() . '</style>';
        }
    }


    private function output_template_header_for_mailbuilder( $template ){
        preg_match_all("/<!--header-table-->(.*)<!--\/header-table-->/smU", $template, $output_array);
        if( count( $output_array ) > 1 && count( $output_array[1] ) > 0 ){
            $header = $output_array[1][0];
            echo $header;
        }
    }


    private function output_template_content_for_mailbuilder( $template ){
        preg_match_all("/<!--content-table-->(.*)<!--\/content-table-->/smU", $template, $output_array);
        if( count( $output_array ) > 1 && count( $output_array[1] ) > 0 ){
            $content = $output_array[1][0];
            echo str_replace( '{#mailcontent#}', '<div id="mailbuilder-content"></div>', $content );
        }
    }


    private function output_template_footer_for_mailbuilder( $template ){
        preg_match_all("/<!--footer-table-->(.*)<!--\/footer-table-->/smU", $template, $output_array);
        if( count( $output_array ) > 1 && count( $output_array[1] ) > 0 ){
            $footer = $output_array[1][0];
            echo $footer;
        }
    }


    public function render_mailbuilder_meta_box(){
        global $post;
        $value = get_post_meta( $post->ID, 'mailbuilder_json', true );
        $value = str_replace( "'", "&apos;", $value );
        wp_nonce_field( 'save_mailbuilder', 'mailbuilder_nonce' );
        $template = Haet_Mail()->get_template( Haet_Mail()->get_theme_options('default') );

        $css_desktop = get_post_meta( $post->ID, 'mailbuilder_css_desktop', true );
        $css_mobile = get_post_meta( $post->ID, 'mailbuilder_css_mobile', true );
        $this->output_template_styles_for_mailbuilder( $template, $css_desktop, $css_mobile );

        wp_enqueue_script( 'code-editor' );
        wp_enqueue_style( 'code-editor' );

        $header_hidden = get_post_meta( $post->ID, 'mailbuilder_header_hidden', true );
        $footer_hidden = get_post_meta( $post->ID, 'mailbuilder_footer_hidden', true );
        ?>
        </style>
        <input type="hidden" name="mailbuilder_json" id="mailbuilder_json" value='<?php echo $value; ?>'>
        <input type="hidden" name="mailbuilder_header_hidden" id="mailbuilder_header_hidden" value="<?php echo $header_hidden; ?>">
        <input type="hidden" name="mailbuilder_footer_hidden" id="mailbuilder_footer_hidden" value="<?php echo $footer_hidden; ?>">
        <div id="mailbuilder-editor">
            <br>
            <div class="container">
                <div id="mailbuilder-header" class="<?php echo ( $header_hidden ? 'mailbuilder-hidden' : '' ); ?>">
                    <?php $this->output_template_header_for_mailbuilder( $template ); ?>
                    <a href="#" class="mailbuilder-hide-button" data-status-field="mailbuilder_header_hidden">
                        <span class="dashicons dashicons-visibility"></span>
                        <span class="dashicons dashicons-hidden"></span>
                        <?php _e( 'show / hide header for this email', 'wp-html-mail' ); ?>
                    </a>
                </div>
                <?php $this->output_template_content_for_mailbuilder( $template ); ?>
                
                <div id="mailbuilder-footer" class="<?php echo ( $footer_hidden ? 'mailbuilder-hidden' : '' ); ?>">
                    <?php $this->output_template_footer_for_mailbuilder( $template ); ?>
                    <a href="#" class="mailbuilder-hide-button" data-status-field="mailbuilder_footer_hidden">
                        <span class="dashicons dashicons-visibility"></span>
                        <span class="dashicons dashicons-hidden"></span>
                        <?php _e( 'show / hide footer for this email', 'wp-html-mail' ); ?>
                    </a>
                </div>
            </div>
            <br><br><br>

            <a href="#" class="mailbuilder-custom-css-button">
                <span class="dashicons dashicons-editor-code"></span>
                <?php _e( 'Custom CSS', 'wp-html-mail' ); ?>
            </a>
        </div>
        <div id="mailbuilder-templates">
            <?php
            do_action( 'haet_mail_content_template', $post->post_title );
            ?>
        </div>
        

        <div class="mailbuilder-settings-sidebar">
            <?php $this->print_add_content_menu(); ?>
            
            <?php do_action( 'haet_mail_content_settings', $post ); ?>

            <div id="mb-wysiwyg-edit" class="mailbuilder-sidebar-element">
                <?php wp_editor( '', 'mb_wysiwyg_editor', array(
                            'wpautop'       =>  false,
                            'textarea_rows' =>  5,
                            'quicktags'     =>  false
                        ) ); ?>
                <div class="mb-popup-buttons">
                    <button class="mb-apply button button-primary" type="button">
                        <span class="dashicons dashicons-yes"></span>
                        <?php _e('Apply', 'wp-html-mail'); ?>
                    </button>
                    <button class="mb-cancel button button-secondary" type="button">
                        <span class="dashicons dashicons-no-alt"></span>
                        <?php _e('Cancel', 'wp-html-mail'); ?>
                    </button>
                </div>
            </div>

            <div id="mb-css-edit" class="mailbuilder-sidebar-element">
                <h3><?php _e('Custom CSS','wp-html-mail'); ?></h3>
                <textarea class="mailbuilder-css-desktop" name="mailbuilder_css_desktop" id="mailbuilder_css_desktop"><?php echo $css_desktop; ?></textarea>

                <p class="description"><?php _e('WP HTML Mail converts all desktop styling to inline CSS code for better support in webmail clients. Mobile CSS stays in the head, so please don\'t write @media queries to the field above but use the mobil CSS field below','wp-html-mail'); ?></p>
                <h3><?php _e('Mobile CSS','wp-html-mail'); ?></h3>
                <textarea class="mailbuilder-css-mobile" name="mailbuilder_css_mobile" id="mailbuilder_css_mobile"><?php echo $css_mobile; ?></textarea>

                <p class="description"><?php _e('CSS changes are not shown live. Please save the email to see your changes.','wp-html-mail'); ?></p>

                <div class="mb-popup-buttons">
                    <button class="mb-apply button button-primary" type="button">
                        <span class="dashicons dashicons-yes"></span>
                        <?php _e('Done', 'wp-html-mail'); ?>
                    </button>
                </div>
            </div>

            <?php do_action( 'haet_mail_sidebar' ); ?>
        </div>
        <?php
    }




    public function save_post( $post_id ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
            
        if ( ! isset( $_POST[ 'mailbuilder_nonce' ] ) ||
            ! wp_verify_nonce( $_POST[ 'mailbuilder_nonce' ], 'save_mailbuilder' ) )
            return;
        
        if ( ! current_user_can( 'edit_posts' ) )
            return;

        elseif( isset( $_POST['mailbuilder_json'] ) )
            update_post_meta( $post_id, 'mailbuilder_json', $_POST['mailbuilder_json'] );

        if( isset( $_POST['subject'] ) )
            update_post_meta( $post_id, 'subject', $_POST['subject'] );

        if( isset( $_POST['mailbuilder_attachments'] ) )
                    update_post_meta( $post_id, 'mailbuilder_attachments', $_POST['mailbuilder_attachments'] );

        if( isset( $_POST['mailbuilder_css_desktop'] ) )
            update_post_meta( $post_id, 'mailbuilder_css_desktop', $_POST['mailbuilder_css_desktop'] );

        if( isset( $_POST['mailbuilder_css_mobile'] ) )
            update_post_meta( $post_id, 'mailbuilder_css_mobile', $_POST['mailbuilder_css_mobile'] );

        if( isset( $_POST['mailbuilder_header_hidden'] ) )
            update_post_meta( $post_id, 'mailbuilder_header_hidden', $_POST['mailbuilder_header_hidden'] );

        if( isset( $_POST['mailbuilder_footer_hidden'] ) )
            update_post_meta( $post_id, 'mailbuilder_footer_hidden', $_POST['mailbuilder_footer_hidden'] );
    }




    
    private function print_add_content_menu(){
        ?>
        <div class="mb-add-wrap mailbuilder-sidebar-element active">
            <h4>
                <?php _e('Add Content Element','wp-html-mail'); ?>
            </h4> 
        
            <?php
            global $post;
            $content_types = array();
            $content_types = apply_filters( 'haet_mail_content_types', $content_types, $post->post_title, $post );
            ?>

            <ul class="mb-add-types">
                <?php foreach ($content_types as $content_type): ?>
                    <li>
                        <a href="#" data-type="<?php echo $content_type['name']; ?>" <?php echo ($content_type['once']? 'data-once="once"':''); ?>>
                            <?php echo ( false !== strpos( $content_type['icon'],'dashicons-') ? '<span class="dashicons '.$content_type['icon'].'"></span>' : '<img class="mb-type-icon" src="'.$content_type['icon'].'">' ); ?>
                            <?php echo $content_type['nicename']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }



    



    public function enqueue_scripts_and_styles($page){
        if( false !== strpos($page, 'post.php')){
            global $post;
            $post_type = get_post_type( $post->ID );
            if ( $post_type == 'wphtmlmail_mail' ){
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script('haet_mailbuilder_js',  HAET_MAIL_URL.'/js/mailbuilder.js', array( 'wp-color-picker', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery'),'3.0', true);
                wp_enqueue_style('haet_mailbuilder_css',  HAET_MAIL_URL.'/css/mailbuilder.css', array(), '3.0');
                wp_enqueue_style('haet_mail_admin_style',  HAET_MAIL_URL.'/css/style.css', array(), '3.0');

                $enqueue_data = array(
                        'translations'  =>  array(),
                        'placeholders'  =>  array(),
                        'placeholder_menu'  =>  array(),
                    );
                $enqueue_data = apply_filters( 'haet_mail_enqueue_js_data', $enqueue_data );

                wp_localize_script( 
                    'haet_mailbuilder_js', 
                    'haet_mb_data',
                    $enqueue_data
                );
            }
        }
    }





    public function get_email_post_id( $email_name ){
        $options = Haet_Mail()->get_options();
        if( !isset($options['email_post_ids']) )
            $options['email_post_ids'] = array();

        if( !isset( $options['email_post_ids'][$email_name] ) || !$options['email_post_ids'][$email_name] )
            $options['email_post_ids'][$email_name] = $this->create_email( $email_name );

        $translated_email_post_id = false;
        if( $options['email_post_ids'][$email_name] && Haet_Mail()->multilanguage->is_multilanguage_site() ){
            $translated_email_post_id = Haet_Mail()->multilanguage->get_email_post_id_in_current_language( $options['email_post_ids'][$email_name] );
        }

        // if( !get_post( $options['email_post_ids'][$email_name] ) ){
        //     // just in case polylang is installed it may have returned no post because the email doesn't exist in current language
        //     // so we check once again wheter it exists in other languages. 
        //     // missing translations are created a few lines below
        //     if( !function_exists('pll_get_post') || !pll_get_post( $options['email_post_ids'][$email_name] ) ) 
        //         $options['email_post_ids'][$email_name] = $this->create_email( $email_name ); 
        // }

        // post ID exists but post can't be loaded
        if( !get_post( $options['email_post_ids'][$email_name] ) 
            && (
                !Haet_Mail()->multilanguage->is_multilanguage_site() // just doesn't exist (language independent)
                || !$translated_email_post_id                        // no translation exists
                || !get_post( $translated_email_post_id ) )          // translation id exists but post does not
            ){
            // create the first email post in any language
            $options['email_post_ids'][$email_name] = $this->create_email( $email_name ); 
            $translated_email_post_id = false;
        }
        

        update_option('haet_mail_options', $options );

        $email_post_id = $options['email_post_ids'][$email_name];

        // create missing translations
        if( $email_post_id && Haet_Mail()->multilanguage->is_multilanguage_site() && !$translated_email_post_id ){
            $translated_email_post_id = $this->create_email( $email_name );

            if( Haet_Mail()->multilanguage->get_multilang_plugin() === 'WPML' ){
                // create language settings for WPML
                $wpml_element_type = apply_filters( 'wpml_element_type', 'wphtmlmail_mail' );
                
                $get_language_args = array('element_id' => $email_post_id, 'element_type' => 'wphtmlmail_mail' );
                $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );

                $set_language_args = array(
                    'element_id'    => $translated_email_post_id,
                    'element_type'  => $wpml_element_type,
                    'trid'   => $original_post_language_info->trid,
                    'language_code'   => ICL_LANGUAGE_CODE,
                    'source_language_code' => $original_post_language_info->language_code
                );
         
                do_action( 'wpml_set_element_language_details', $set_language_args );
            }elseif( Haet_Mail()->multilanguage->get_multilang_plugin() === 'Polylang' ){
                // create language settings for Polylang
                PLL()->model->post->set_language($translated_email_post_id, pll_current_language());
                
                $translations = PLL()->model->post->get_translations($email_post_id);
		        if (!$translations && $lang = PLL()->model->post->get_language($email_post_id)) {
			        $translations[$lang->slug] = $email_post_id;
                }
                $translations[pll_current_language()] = $translated_email_post_id;
                PLL()->model->post->save_translations($translated_email_post_id, $translations);
            }
        }
        
        return $translated_email_post_id ? $translated_email_post_id : $email_post_id;
    }





    private function create_email( $email_name ){
        $postarr = array(
                'post_title'    =>  $email_name,
                'post_status'   =>  'publish',
                'post_type'     =>  'wphtmlmail_mail'
            );

        $post_id = wp_insert_post( $postarr );

        do_action( 'haet_mailbuilder_create_email', $post_id, $email_name );

        return $post_id;
    }




    public function customize_editor_toolbar( $initArray ) {  
        global $post;
        if( $post ){
            $post_type = get_post_type( $post->ID );
            if( 'wphtmlmail_mail' == $post_type && $initArray['selector'] == '#mb_wysiwyg_editor' ){
                $initArray['block_formats'] = 'Headline=h1;Subheadline=h2;Paragraph=p;';

                $fonts = Haet_Mail()->get_fonts();
                $initArray['font_formats'] = "";
                foreach ($fonts as $font => $display_name) {
                    $initArray['font_formats'] .= "$display_name=$font;";
                }
                $initArray['font_formats'] = trim($initArray['font_formats'],';');

                $initArray['toolbar1'] = 'formatselect,fontselect,fontsizeselect,|,bold,italic,|,alignleft,aligncenter,alignright,|,pastetext,removeformat,|,undo,redo,|,bullist,numlist,|,link,unlink,forecolor,|,spellchecker,fullscreen,table,code';
                // Font size
                $initArray['fontsize_formats'] = "10px 11px 12px 14px 16px 18px 20px 22px 24px 28px 32px";
                
                $initArray['toolbar2'] = '';    
            }
        }


        return $initArray;  
      
    } 


    public function tinymce_external_plugins($plugins) {
        $plugins['code'] = HAET_MAIL_URL.'/js/tinymce-code-button.min.js';
        $plugins['table'] = HAET_MAIL_URL.'/js/tinymce-table.min.js';
        return $plugins;
    }


    public function get_contenttype_object( $type, $email_name, $email_post ){
        $content_types = array();
        $content_types = apply_filters( 'haet_mail_content_types', $content_types, $email_name, $email_post );

        if ( array_key_exists( $type , $content_types ) ){
            $content_class = $content_types[$type]['elementclass'];
            return $content_class();
        }
        return null;
    }



    /**
     * modify_styled_mail
     * email content and email styling are executed independently
     * when we print the content we have full control over the parameters
     * but here we have to parse the content to get the email type
     * @param  $message email body html
     * @return email body html
     */
    public function modify_styled_mail( $message ){
        preg_match_all("/<\!--mailbuilder\[(.*)\]-->/smU", $message, $matches);
        if( count($matches) > 1 && count($matches[1]) ){
            $email_name = $matches[1][0];
            if( $email_name ){
                $email_id = $this->get_email_post_id( $email_name );
                
                // email specific CSS
                $custom_css_desktop = get_post_meta( $email_id, 'mailbuilder_css_desktop', true );
                $custom_css_mobile = get_post_meta( $email_id, 'mailbuilder_css_mobile', true );

                $mailbuilder_json = get_post_meta( $email_id, 'mailbuilder_json', true );
                $mailbuilder_array = json_decode( $mailbuilder_json, true );
                if ( $mailbuilder_array != null ):
                    $element_styles_desktop = '';
                    $element_styles_mobile = '';
                    foreach ( $mailbuilder_array as $element_content ):
                        if( isset( $element_content['settings'] ) && isset( $element_content['settings']['styles'] ) ){
                            foreach ( $element_content['settings']['styles']['desktop'] as $attribute => $value ) {
                                if( $value ){
                                    if( stripos( $attribute, 'padding' ) !== false )
                                        $element_styles_desktop .= ' #' . $element_content['id'] . ' .container-padding{ ' . $attribute . ': ' . $value . '; } ' . PHP_EOL;
                                    else
                                        $element_styles_desktop .= ' #' . $element_content['id'] . '{ ' . $attribute . ': ' . $value . '; } ' . PHP_EOL;
                                }
                            }

                            foreach ( $element_content['settings']['styles']['mobile'] as $attribute => $value ) {
                                if( $value ){
                                    if( stripos( $attribute, 'padding' ) !== false )
                                        $element_styles_mobile .= ' table#' . $element_content['id'] . '[id="' . $element_content['id'] . '"] td[class*="container-padding"]{ ' . $attribute . ': ' . $value . '; } ' . PHP_EOL;
                                    else
                                        $element_styles_mobile .= ' table#' . $element_content['id'] . '[id="' . $element_content['id'] . '"] { ' . $attribute . ': ' . $value . '; } ' . PHP_EOL;
                                }
                            }
                        }
                        
                    endforeach;

                    $custom_css_desktop .= ' ' . $element_styles_desktop;
                    $custom_css_mobile = ' @media screen and (max-width: 400px) { ' . PHP_EOL . $custom_css_mobile . ' ' . $element_styles_mobile . ' } ';
                    $message = str_replace('/**** ADD CSS HERE ****/', $custom_css_desktop . '/**** ADD CSS HERE ****/', $message);
                    $message = str_replace('/**** ADD MOBILE CSS HERE ****/', $custom_css_mobile . '/**** ADD MOBILE CSS HERE ****/', $message);
                endif;

                // optionally remove header and footer since 2.9
                if( get_post_meta( $email_id, 'mailbuilder_header_hidden', true ) )
                    $message = preg_replace('/(.*)<!--header-table-->.*<!--\/header-table-->(.*)/smU', '$1 $2', $message);
                if( get_post_meta( $email_id, 'mailbuilder_footer_hidden', true ) )
                    $message = preg_replace('/(.*)<!--footer-table-->.*<!--\/footer-table-->(.*)/smU', '$1 $2', $message);
        
            }
        }
        return $message;
    }



    public function print_email($email_name,$settings){
        $email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
        $mailbuilder_json = get_post_meta( $email_id, 'mailbuilder_json', true );
        $mailbuilder_array = json_decode( $mailbuilder_json );
        if ( $mailbuilder_array != null ):
            echo '<!--mailbuilder[' . $email_name . ']-->';
            echo '<!--mailbuilder-content-start-->';
            foreach ( $mailbuilder_array as $element_content ):
                $content_element = Haet_Mail_Builder()->get_contenttype_object( $element_content->type, $email_name, get_post( $email_id ) );
                if( $content_element )
                    $content_element->print_content( $element_content, $settings );
            endforeach;
            echo '<!--mailbuilder-content-end-->';
        endif;
    }
}



function Haet_Mail_Builder()
{
    return Haet_Mail_Builder::instance();
}

Haet_Mail_Builder();