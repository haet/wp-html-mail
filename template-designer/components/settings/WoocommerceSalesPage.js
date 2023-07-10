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
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';

export default function WoocommerceSalesPage() {


  return (
    <div className="mail-settings">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300&display=swap" rel="stylesheet" />
      <div id="wp-html-mail-woocommerce-sales-page">
        <div className="salespage-intro">
          <h2>{__('The free version', 'wp-html-mail')}</h2>
          <p>
            {__('In this free version your WooCommerce emails will use your global email template like all the other WordPress plugins.', 'wp-html-mail')}
          </p>
          <h4>
            <span className="dashicons dashicons-yes"></span> {__('custom header', 'wp-html-mail')}
          </h4>
          <h4>
            <span className="dashicons dashicons-yes"></span> {__('your own colors', 'wp-html-mail')}
          </h4>
          <h4>
            <span className="dashicons dashicons-yes"></span> {__('custom font', 'wp-html-mail')}
          </h4>
          <h4>
            <span className="dashicons dashicons-yes"></span> {__('your individual footer', 'wp-html-mail')}
          </h4>
        </div>
        <div className="salespage-facts">
          <h2>{__('Do you need more?', 'wp-html-mail')}</h2>
          <p>
            {__("There's so much more...", 'wp-html-mail')}
          </p>
          <ol>
            <li>
              <h4>{__('custom content', 'wp-html-mail')}</h4>
              <p>
                {__('Write your own text and use order details as placeholders.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h4>{__('Modify products table', 'wp-html-mail')}</h4>
              <p>
                {__('Customize images, borders, sizes and content of your products table.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h4>{__('Add attachments', 'wp-html-mail')}</h4>
              <p>
                {__('Add your own attachments to individual emails.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h4>{__('Images', 'wp-html-mail')}</h4>
              <p>
                {__('Fresh up your design with some images.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h4>{__('Meta fields', 'wp-html-mail')}</h4>
              <p>
                {__('Add order or product meta fields to your emails.', 'wp-html-mail')}
              </p>
            </li>
            <li>
              <h4>{__('Hide header and footer', 'wp-html-mail')}</h4>
              <p>
                {__('Some store owners want to print their order notifications so it can be useful to remove header and footer for admin notifications.', 'wp-html-mail')}
              </p>
            </li>
          </ol>
        </div>
        <div className="salespage-cta-bar">
          <h3>{__('Still not sure? Just give it a try.', 'wp-html-mail')}</h3>
          <p>
            {__('We\'ve got a 30 days money back guarantee, great support and WP HTML Mail WooCommerce integrates with many WooCommerce extensions:', 'wp-html-mail')}
          </p>
          <p>
            <span className="dashicons dashicons-yes"></span> {__('Advanced Shipment Tracking', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Order status manager', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Yith Barcodes', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('PW Giftcards', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('WooCommerce German Market', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('WooCommerce Germanized', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Quotes for WooCommerce', 'wp-html-mail')}
            <br />
            <span className="dashicons dashicons-yes"></span> {__('Automate Woo', 'wp-html-mail')}
            <br />
            {__('and many more...', 'wp-html-mail')}
          </p>
          <a href="https://codemiq.com/en/plugins/wp-html-mail-woocommerce/" target="_blank" className="cq-cta">
            {__('learn more about the WooCommerce extension', 'wp-html-mail')} <span className="dashicons dashicons-arrow-right-alt2"></span>
          </a>
        </div>
      </div>
    </div>
  );
}
