import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	CardBody,
	CardHeader,
	CardDivider,
	Card,
	Spinner,
	Snackbar,
	Icon,
	TextControl,
	CheckboxControl
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import Editor from "./Editor";
import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function ContentEditor() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const [css, setCSS] = useState('');
	const [isSaving, setIsSaving] = useState(false);
	const [showSaveSuccess, setShowSaveSuccess] = useState(false);
	
	const loadCSS = () => {
		if ( Object.entries(settings).length === 0 ) {
			var request = new Request(
				window.mailTemplateDesigner.restUrl + "themecss/0",
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
					setCSS(data);
				});
		}
	};

	// const saveSettings = () => {
	// 	setIsSaving(true);
	// 	var request = new Request(
	// 		window.mailTemplateDesigner.restUrl + "settings",
	// 		{
	// 			method: "POST",
	// 			body: JSON.stringify(settings),
	// 			headers: {
	// 				"Content-Type": "application/json",
	// 				"X-WP-Nonce": window.mailTemplateDesigner.nonce
	// 			},
	// 		}
	// 	);
	// 	fetch(request)
	// 		.then((resp) => resp.json())
	// 		.then((data) => {
	// 			setIsSaving(false);
	// 			setShowSaveSuccess(true);
	// 			setTimeout(() => {
	// 				setShowSaveSuccess(false);
	// 			}, 4000);
	// 			templateDesignerContext.setSettings(data);
	// 		});
	// };

	useEffect(() => {
		loadCSS();
	}, []);

	
	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-settings">
				{showSaveSuccess && (
					<Snackbar className="snackbar" status="success" isDismissible={false}>
					<Icon icon="saved" />
						<p>
							{__(
								"Your settings have been saved.",
								"wp-html-mail"
							)}
						</p>
					</Snackbar>
				)}
				<Editor css={css}/>
			</div>
		);
}
