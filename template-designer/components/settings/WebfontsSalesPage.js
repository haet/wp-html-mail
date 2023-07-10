import { useState, useEffect, useContext } from "@wordpress/element";

import {
  Button,
  CardBody,
  CardHeader,
  Heading,
  CardDivider,
  Card,
  Spinner,
  Notice,
  TextControl,
  CheckboxControl
} from "@wordpress/components";
import { sprintf, __ } from "@wordpress/i18n";

export default function WebfontsSalesPage() {


  return (
    <div className="mail-settings">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300&family=Oswald:wght@300&family=Rock+Salt&family=Train+One&display=swap" rel="stylesheet" />
      <div id="wp-html-mail-webfonts-sales-page">
        <div className="salespage-intro">
          <div className="small-heading">Are</div>
          <div className="huge-heading">Webfonts</div>
          <div className="small-heading">ready to be used in</div>
          <div className="huge-heading">Emails?</div>
          <div className="hand">** or is it still too experimental? **</div>
        </div>
        <div className="salespage-facts">
          <h2>{__('The facts', 'wp-html-mail')}</h2>
          <p>
            {__('According to caniemail.com webfonts in emails work for just around 52% of users.', 'wp-html-mail')}
            <br />
            {__('To be honest, this is a really bad value! So what does it mean for us?', 'wp-html-mail')}
          </p>
          <ol>
            <li>
              <h3>{__('Choose fonts with suitable fallbacks', 'wp-html-mail')}</h3>
              <p>
                {__('Those users whose email clients do not support webfonts will see an alternative font so use webfonts that can be replaced easily by a standard font without destroying your complete email design.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h3>{__('Know your recipients', 'wp-html-mail')}</h3>
              <p>
                {__('If you already know how many of your recipients use Outlook, Gmail, Apple Mail, Thunderbird, ... check the stats on whether or not it even makes sense to use non standard fonts.', 'wp-html-mail')}
                <br />
                <a href="https://www.caniemail.com/features/css-at-font-face/" target="_blank">
                  {__('caniemail.com statistics', 'wp-html-mail')}
                </a>
              </p>
            </li>
            <li>
              <h3>{__('Test, test, test', 'wp-html-mail')}</h3>
              <p>
                {__('Test your emails in multiple email clients to make sure they look either great or at least ok and not completely different than expected.', 'wp-html-mail')}
                <br />
                <a href="https://codemiq.com/en/webfonts-in-wordpress-emails/" target="_blank">
                  {__('See our own tests here', 'wp-html-mail')}
                </a>
              </p>
            </li>
          </ol>
        </div>
        <div className="salespage-cta-bar">
          <h3>{__('Not the sales pitch you expected?', 'wp-html-mail')}</h3>
          <p>
            {__('You are a user of our product so we do not want you to buy something you can\'t use as expected.', 'wp-html-mail')}
            <br />
            {__('This may be one of the reasons for our ★★★★★ ratings.', 'wp-html-mail')}
          </p>
          <p>
            <span className="dashicons dashicons-yes"></span> {__('Use more than 1000 Google fonts.', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Most requested feature by our users.', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Works with our WooCommerce extension.', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Works with our Easy Digital Downloads extension.', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Works with our BuddyPress extension.', 'wp-html-mail')}
          </p>
          <a href="https://codemiq.com/en/plugins/wp-html-mail-webfonts/" target="_blank" className="cq-cta">
            {__('get the webfonts extension', 'wp-html-mail')} <span className="dashicons dashicons-arrow-right-alt2"></span>
          </a>
        </div>
      </div>
    </div>
  );
}
