import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	CardBody,
	CardHeader,
	CardDivider,
	Card,
	Spinner,
	Notice,
	SelectControl,
	CheckboxControl
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";


import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function DebugEmailPostIDMapping() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
  const [availableMails, setAvailableMails] = useState({});

  const fetchAvailableMails = () => {
    if (Object.keys(availableMails).length === 0) {
      //dev02.loc:8888/wp-json/wp/v2/wphtmlmail_mail?_fields=id,date&per_page=100&search=WC_Email_Customer_New_Account
      {
        Object.keys(settings.email_post_ids).map((email_name) => {
          var request = new Request(
            window.mailTemplateDesigner.restUrl.replace("whm/v3", "wp/v2") + "wphtmlmail_mail?_fields=id,date&per_page=100&search=" + email_name,
            {
              method: "GET",
              headers: {
                "Content-Type": "application/json",
                "X-WP-Nonce": window.mailTemplateDesigner.nonce
              },
              credentials: "same-origin"
            }
          );
          fetch(request)
            .then((resp) => resp.json())
            .then((data) => {
              if (data.length > 0) {
                setAvailableMails(mails => {
                  const available_for_this_mail = [];
                  data.forEach(mail => {
                    available_for_this_mail.push({ label: mail.date, value: mail.id });
                  });
                  return { ...mails, [email_name]: available_for_this_mail };
                });
              }
            });
        })
      }
    }
  }


	useEffect(() => {
		templateDesignerContext.loadSettings();
  }, []);
  
  useEffect(() => {
    fetchAvailableMails();
	}, [settings]);

	
  if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
    return (
      <div className="mail-loader">
        <Spinner />
      </div>
    );
  else {
    if (!availableMails || Object.keys(availableMails).length === 0)
      return null;
    console.log(availableMails);
    return (
      <div className="mail-settings">
        <Card className="mail-settings-content">
          <CardHeader>
            <h3>{__('WP HTML Mail WooCommerce Update:', 'wp-html-mail')}<br />{__('Debug and restore template mapping', 'wp-html-mail')}</h3>
          </CardHeader>
          <CardBody>
            <p>
              {__(
                'If you lost your WooCommerce templates during an update you can restore them here:',
                "wp-html-mail"
              )}
            </p>
          </CardBody>
          {settings.email_post_ids && Object.keys(settings.email_post_ids).length > 0 &&
            <CardBody>
              {Object.keys(settings.email_post_ids).filter(email_name => availableMails.hasOwnProperty( email_name ) ).map((email_name) => {
                return (<div
                    key={email_name}
                    style={{width:200, marginBottom: 15}}>
                    <SelectControl
                      label={email_name.replace('WC_Email_', '')}
                      size="small"
                      value={settings.email_post_ids[email_name]}
                      options={availableMails[email_name]}
                      onChange={(value) => {
                        templateDesignerContext.updateSetting(
                          "email_post_ids",
                          {...settings.email_post_ids,[email_name]:value}
                        );
                      }}
                    />
                  </div>);
              })}
            </CardBody>
          }
          <CardDivider />
        </Card>
        <div className="save-button-pane-bottom">
          <Button
            isPrimary
            isBusy={templateDesignerContext.isSaving}
            onClick={(e) => {
              e.preventDefault();
              templateDesignerContext.saveSettings(() => {
                templateDesignerContext.setInfoMessage(__('Your settings have been saved.', 'wp-html-mail'))
                setTimeout(() => {
                  templateDesignerContext.setInfoMessage("");
                }, 7000)
              });
            }}
          >
            {__("Save settings", "wp-html-mail")}
          </Button>
        </div>
      </div>
    );
  }
}
