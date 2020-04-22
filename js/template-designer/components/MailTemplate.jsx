import { useState, useEffect, useContext } from "@wordpress/element";

import { IsolatedEventContainer } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext.jsx";

import OptionsPanel from "./OptionsPanel.jsx";
import MailHeader from "./MailHeader.jsx";
import EditableElement from "./EditableElement.jsx";

export default function MailTemplate() {
	const [fonts, setFonts] = useState({});
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Email Container", "wp-html-mail");
	const elementName = "container";
	const options = <div></div>;

	const loadSettings = () => {
		fetch(window.mailTemplateDesigner.restUrl + "themesettings")
			.then((resp) => resp.json())
			.then((data) => {
				templateDesignerContext.setSettings(data);
				templateDesignerContext.setIsLoading(false);
			});
	};

	const loadFonts = () => {
		fetch(window.mailTemplateDesigner.restUrl + "fonts")
			.then((resp) => resp.json())
			.then((data) => {
				setFonts(data);
			});
	};

	useEffect(() => {
		loadSettings();
		loadFonts();
	}, []);

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelTitle(elementTitle);
			templateDesignerContext.setPanelOptions(options);
		}
	}, [templateDesignerContext.editingElement]);

	if (templateDesignerContext.isLoading || !templateDesignerContext.settings)
		return null;
	else
		return (
			<React.Fragment>
				<div className="mail-container">
					<EditableElement
						elementTitle={elementTitle}
						elementName={elementName}
					>
						<div className="mail-content-wrap">
							<MailHeader />
							<h1>I am the body</h1>
						</div>
					</EditableElement>
				</div>
				<OptionsPanel />
			</React.Fragment>
		);
}
