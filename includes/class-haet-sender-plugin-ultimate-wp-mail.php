<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_UltimateWPMail extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( strpos( $mail['message'], '<!-- [IS UWP EMAIL] -->' ) === false )
            throw new Haet_Different_Plugin_Exception();
    }



    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        $content = str_replace( '<!-- [IS UWP EMAIL] -->', '', $content );
        return $content;
    }

    /**
    *   modify_template()
    *   mofify the email template before the content is added
    **/
    public function modify_template($template){
        return $template;
    } 

    /**
    *   modify_styled_mail()
    *   mofify the email body after the content has been added to the template
    **/
    public function modify_styled_mail($message){
        return $message;
    }  


    public static function plugin_actions_and_filters(){
        // add a comment to message body to detect UWP forms emails later
        add_filter( 'ewd_uwpm_content_post_substitutions', function( $Email_Content, $Params ){
            if( strpos( $Email_Content, '</body>' ) !== false )
                $Email_Content = str_replace( '</body>', '<!-- [IS UWP EMAIL] --></body>', $Email_Content );
            else
                $Email_Content .= '<!-- [IS UWP EMAIL] -->';

            return $Email_Content;
        }, 10, 2);


        // add some CSS fixes
        add_filter( 'haet_mail_css_desktop', function( $css ){
            $css .= '  
                    .ewd-uwpm-section-container{
                        margin: 12px 0!important;
                    }
                ';
            return $css;
        });

        // add_filter( 'haet_mail_css_mobile', function( $css ){
        //     $css .= '  
                    
        //         ';
        //     return $css;
        // });
    }
}