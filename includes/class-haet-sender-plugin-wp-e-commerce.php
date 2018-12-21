<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_WP_E_Commerce extends Haet_Sender_Plugin {
    public function __construct($mail) {
        $subject = $mail['subject'];
        if( $subject != __( 'Transaction Report', 'wpsc' )
            && $subject != __( 'Purchase Receipt', 'wpsc' )
            && $subject != __( 'Order Pending: Payment Required', 'wpsc' )
            && $subject != get_option( 'wpsc_trackingid_subject' )
            && $subject != __( 'The administrator has unlocked your file', 'wpsc' )
            && !strpos($mail['message'], '<!--haetshopstyling-->')
            )
            throw new Haet_Different_Plugin_Exception();
    }



    /**
    *   modify_template()
    *   mofify the email template before the content is added
    **/
    public function modify_template($template){
        $template = str_replace('</style>', '
            #products-table{
                width:100%;
                border-collapse:collapse;
                padding-bottom:1px;
                border-bottom:1px solid #606060;
            }
            #products-table th{
                text-align:right;
                border-bottom:2px solid #606060!important;
            }
            #products-table td{
                text-align:right;
                border-bottom:1px solid #606060;
                padding:0 3px;
            }
            #products-table .product_name{
                text-align:left;
            }
            </style>', 
        $template);
        return $template;
    }    


}