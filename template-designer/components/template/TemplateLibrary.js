import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
  CardMedia,
  CardHeader,
  CardBody,
	CardFooter,
	Card,
	Spinner,
	Modal
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";


import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function TemplateLibrary() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
  const [templates, setTemplates] = useState([]);
  const [isTemplateLibraryOpen, setIsTemplateLibraryOpen] = useState(false);

  const fetchTemplates = () => {
    if (Object.keys(templates).length === 0) {
      {
        var request = new Request(
          window.mailTemplateDesigner.restUrl + "templatelibrary",
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
              setTemplates(data);
            }
          });
      }
    }
  }

  const importTemplate = (config_url) => {
    var request = new Request(
      config_url,
      {
        method: "GET", 
        headers: {
          "Content-Type": "text/plain",
        },
      }
    );
    fetch(request)
      .then((resp) => resp.json())
      .then((template) => {
        const newTheme = { ...templateDesignerContext.theme };
        Object.keys(template).forEach(theme_property => {
          if (newTheme.hasOwnProperty(theme_property)) {
            newTheme[theme_property] = template[theme_property];
          }
        });
        templateDesignerContext.setTheme(newTheme);
        setIsTemplateLibraryOpen(false);
      });
  }

	useEffect(() => {

  }, []);
  
  useEffect(() => {
    if (isTemplateLibraryOpen) {
      fetchTemplates();
    }
	}, [isTemplateLibraryOpen]);

  return (
    <>
      <Button
        isSecondary
        onClick={() => setIsTemplateLibraryOpen(true) }
        style={{ marginRight: 10 }}
      >
        {__("Browse our template library", "wp-html-mail")}
      </Button>
      {isTemplateLibraryOpen && (
        <Modal
          title={__( 'Use one of our templates to get started', 'wp-html-mail' )}
          onRequestClose={() => setIsTemplateLibraryOpen(false)}
          className="mail-template-library"
          isFullScreen={true}
        >
          {templates.length === 0 ?
            <div className="mail-loader">
              <Spinner /> 
            </div>
            :
            <div className="mail-template-cards">
              {templates.map(template => {
                
                return <Card className="mail-template-card" key={template.slug}>
                  <CardHeader>
                    {template.name}
                  </CardHeader>
                  <CardMedia>
                    <img src={template.screenshot}/>
                  </CardMedia>
                  <CardFooter isShady={true} size="small">
                    <Button
                      isSecondary
                      isSmall
                      onClick={() => {
                        importTemplate(template.config);
                      } }
                    >
                      {__("import this template", "wp-html-mail")}
                    </Button>
                  </CardFooter>
                </Card>
              })}
            </div>
          }
        </Modal> 
      )}
    </>
  );
  
}
