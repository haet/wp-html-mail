=== WP HTML Mail - Email Designer ===
Contributors: haet
Tags: email template, html mail, email design, mail, email templates, ninja-forms, caldera-forms, wp-e-commerce, easy-digital-downloads, woocommerce, contact-form-7, mandrill, postman, gravityforms
Requires at least: 4.9
Tested up to: 5.2.4
Stable tag: 2.9.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

All in one email template solution for Ninja Forms, Caldera Forms, Gravity Forms, WooCommerce, CF7, Support Plus, EDD, ...

== Description ==
Beautiful responsive HTML mails, fully customizable without any coding knowledge 
Create your own professional email template within a few minutes. 

= Features = 

* **Responsive & Device independent:**
Our e-mail template has been tested in all major desktop, web and mobile mailclients. 
* **Text or image header:** 
Either style your email header with colors and text, use your logo or design a banner.
* **Customize colors and fonts:**
Differnt plugins send different emails but you can define global color and font settings for headlines, text, links and more to give all your emails a common professional look.
* **Add your companies legal information to the footer:** 
The email footer can contain links to your legal pages, your contact data or whatever you want. 
* **Inline CSS:** 
Webclients do not override your styles, because we move all style definitions to inline styles.
* **Email test mode:** 
Redirect all emails to your own email address for testing or for your staging system.
* **Live Preview:**
See all your changes immediately in the live preview.
* **WooCommerce AddOn (PRO):**
Customize design, text, products table, simply everything with our Drag & Drop MailBuilder [WooCommerce email templates](https://codemiq.com/en/downloads/wp-html-mail-woocommerce/)
* **Easy Digital Downloads AddOn (PRO):**
Read more about the [Easy Digital Downloads Email template](https://codemiq.com/en/downloads/wp-html-mail-easy-digital-downloads/)


= Tutorial: Create your WordPress email template in 2 minutes =

https://youtu.be/FwtG9NqoeJA


Turn email formatting on/off for specific plugins.
Currently supported are

* Ninja Forms
* Caldera Forms
* Contact Form 7
* Gravity Forms
* Gravity Flow
* HappyForms
* WP Support Plus Responsive Ticket System
* Birthday Emails
* Ultimate WP Mail
* Divi Theme contact forms
* [BuddyPress](https://wordpress.org/plugins/wp-html-mail-buddypress/)
* [WPForms and WPForms Lite](https://codemiq.com/en/downloads/wp-html-mail-wpforms/)
* [WooCommerce](https://codemiq.com/en/downloads/wp-html-mail-woocommerce/)
* [Easy Digital Downloads](https://codemiq.com/en/downloads/wp-html-mail-easy-digital-downloads/)
* [Give – Donation and Fundraising](https://wordpress.org/plugins/wp-html-mail-give/)
* TeraWallet
* WP E-Commerce

Allthough we didn't optimize WP HTML Mail for these plugins our users use it with many more plugins

* ACF Advanced Forms
* Elementor Forms
* Events Made Easy
* Formidable Forms
* Learndash Notifications
* Matador Jobs
* Memberpress
* Modern Events Calendar
* Uncanny Owl Groups
* ...



= WooCommerce Email Template Extension =

Use our WooCommerce extension to customize all your store emails. For all standard emails you can also edit the email content as well as the products table. 
We recently added support for [WooCommerce Advanced Shipment Tracking](https://wordpress.org/plugins/woo-advanced-shipment-tracking/) and Amazon Payment gateway.
Most emails from third party WooCommerce plugins can be styled and for some of them we created special integrations:

* Barcodes from **YITH WooCommerce Bar Codes and QR Codes** can be placed anywhere in your Emails
* You can change emails from **WooCommerce Order Status Manager**
* Insert custom checkout fields to your emails with [Checkout Field Editor](https://wordpress.org/plugins/woo-checkout-field-editor-pro/)
* **WooCommerce German Market** is fully integrated
* In version 2.9 we integrated [AutomateWoo](https://automatewoo.com/?ref=42) so you can use your email template for your **abandoned cart mails** and other **automated emails**


[more about WP HTML Mail](https://codemiq.com/en/plugins/wp-html-mail-email-templates/)


https://youtu.be/RV8vUNCWOZw


= Works with most email delivery plugins =
* Post SMTP Mailer/Email Log
* WP Mail SMTP
* wpMandrill
* ...


= Credits =

* Thanks to Julie Ng for the great "Antwort" responsive email layout [Antwort on Github](https://github.com/InterNations/antwort)
* Thanks to Tijs Verkoyen for his CSS-to-inline-styles PHP library [CssToInlineStyles on Github](https://github.com/tijsverkoyen/CssToInlineStyles)


= Translations =

The plugin is currently available in following languages
* English (of course)
* German
* Spanish translation provided by [Jose](https://profiles.wordpress.org/jmbescos) from  [Ibidem Translations](http://www.ibidem-translations.com)
* Italian translation provided by [@gablau](https://profiles.wordpress.org/gablau/) and Ema from  [Ibidem Traduzioni](http://www.ibidem-traduzioni.com)


= > Want to get notified about new features and updates? =
[Follow me on twitter](https://twitter.com/intent/user?screen_name=h_etzelstorfer)


== Installation ==
Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Screenshots ==

1. WooCommerce Demo
2. Use text or image header
3. set global font settings
4. append you contact details to the mail footer
5. turn template on and off for supported plugins


== Changelog ==

= 2.9.1.4 =
* Fixed bug with headers passed as array instead of string
* added new option to allow plaintext emails containing HTML code for backwards compatibility


= 2.9.1 =
* TeraWallet Support
* Improved filtering of plaintext emails


= 2.9.0.3 = 
* custom templates can be moved to uploads folder now

= 2.9.0.2 =
* fixed a notice with not initialized settings

= 2.9.0.1 =
* improved polylang compatibility 
* added options to enable/disable header and footer per sender plugin


= 2.9 = 
* added debug mode
* optionally remove header and footer in mailbuilder
* fixed a bug causing content to be overridden in mailbuilder


= 2.8.4.2 =
* removed a notice during login


= 2.8.4.1 =
* fixed a missing file during commit


= 2.8.4  =
* Happy Forms support
* Added email test mode to redirect all messages to your own mailbox


= 2.8.3.1 = 
* Fixed a character encoding bug


= 2.8.3 = 
* Improvements for Gravity Forms and Gravity Flow


= 2.8.2 =
* Developers get back the custom template feature
* new filter for custom header HTML code `haet_mail_header`
* new filter for custom footer HTML code `haet_mail_footer`
* filter for custom CSS `haet_mail_css_desktop` and `haet_mail_css_mobile


= 2.8.1 =
* fixed a character encoding bug


= 2.8 =
* another fix for gravity forms https://wordpress.org/support/topic/unwanted-space-in-gravity-form-notifications-and-solution/
* changed get_tab_url() to use add_query_arg()
* added reset buttons to delete settings
* added preheader text to improve preview in some mailclients


= 2.7.9.1 =
* Bugfix for GravityForms line breaks


= 2.7.9 =
* Added support for Gravity Forms
* added shortcode support


= 2.7.8 =
* Improved Mailbuilder interface
* Mailbuilder supports padding and bg color for each element
* Removed "advanced" tab
* Added link to website to header 
* Supports some special characters now in sender name and subject


= 2.7.3 =
* Added support for [Birthday Emails](https://wordpress.org/plugins/birthday-emails/), thanks to @carman23
* improved CSS inlining (using a fork of CSS inliner now, but keeping the previous version for compatibility with WP HTML Mail for WooCommerce until 2.7.3)


= 2.7.1 =
* Added support for WP Support Plus Responsive Ticket System
* WooCommerce Attachments Bugfix
* Mailbuilder Improvement



= 2.7 =
* Improved MailBuilder for WooCommerce
* Minor Bugfixes


= 2.5 =
* Updates for WooCommerce 3
* Supports attachments for WooCommerce emails now
* Fixed detection of NinjaForms emails


= 2.4 =
* Improved responsive display in Gmail App
* Improved scaling for Outlook with high DPI setting
* Fixed a bug adding inline styles to head tag
* PHP Version check 
* Fixed line breaks in emails sent by Contact Form 7

= 2.3 =
* Updated translation management

= 2.2.6 =
* Better alignment for multicolumn content (especially for WooCommerce)

= 2.2.5 =
* Fixed another bug with inline styles causing wrong preview of email footer

= 2.2.4 = 
* Updated CSS to Inline Styles library because of a bug with multiple line breaks

= 2.2.3 =
* Updated italian translation
* Improved some WooCommerce features

= 2.2.1 =
* Fixed a bug causing double line breaks

= 2.2 =
* Works with wpMandrill now

= 2.1.1 =
* Plugin can be used by Non-Admins now [see here](https://codemiq.com/en/enable-email-customization-for-woocommerce-shop-managers/)

= 2.1 =
* Updated inline css library
* Improved font toolbar


= 2.0.2 =
* Updated german translation

= 2.0.1 =
* Fixed a bug causing Email sender not beeing set

= 2.0 = 
* Added Drag&Drop Mailbuilder to customize email content

= 1.2 =
* Bugfixes
* Support for Contact Form 7
* Italian and German translations

= 1.1 =
* Improved formatting of embedded tables and lists

= 1.0 = 
* Bugfixes
* added support for WooCommerce
* added support for Easy Digital Downloads

= 0.3 =
* replaced nl2br with wpautop https://wordpress.org/support/topic/replace-nl2br-with-wpautop 
* removed a few PHP notices
* added support for Caldera forms

== Upgrade Notice ==

== Frequently Asked Questions ==

= Can I send my newsletter campaigns with this plugin? =

No, this is not a newsletter tool, it just makes your emails beautiful but doesn't send custom ones.


= How can I remove the gap at the header of my Contact Form 7 emails? =

Go to your Contact Form 7 email settings and change email type from HTML to text. WP HTML Mail will take care of the HTML.


= Can a customize the HTML code of the email header? =

There's a filter to change the header HTML code. Just add this to your (child-)themes functions.php:

`
add_filter('haet_mail_header', function( $header ){
    return 'hello <strong>world</strong>';
});
`

Of course you can display HTML code, not just text.


= Can a customize the HTML code of the email footer? =

There's a filter to change the footer HTML code. Just add this to your (child-)themes functions.php:

`
add_filter('haet_mail_footer', function( $header ){
    return 'hello <strong>world</strong>';
});
`

= Can I add custom CSS code to my WordPress emails? =

You can add your own CSS code for desktop and mobile. Just add this example to your (child-)themes functions.php and customize it:

`
add_filter( 'haet_mail_css_desktop', function( $css ){
    $css .= '  
            h1{
                border-bottom: 2px solid green;
            }
        ';
    return $css;
});

add_filter( 'haet_mail_css_mobile', function( $css ){
    $css .= '  
            h1{
                background:red;
            }
        ';
    return $css;
});
`

= How to disable the template for some emails? =

Find anything all emails hav in common. It may be the sender, a word in the subject or something in the email body.
Then add this function to your (child-)themes functions.php and customize it. Return TRUE if the template should be used and FALSE if not.

`
// return true if you want to use a template for current mail
// return false if you want to leave the content of this email unchanged
add_filter( 'haet_mail_use_template', 'customize_template_usage', 10, 2 );
function customize_template_usage( $use_template, $mail ){
    // $mail['to'] ...
    // $mail['subject'] ...
    // $mail['message'] ...
    // $mail['headers'] ...
    // $mail['attachments'] ...
    return true;
}
`


