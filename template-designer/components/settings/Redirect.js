import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	CardBody,
	CardHeader,
	CardDivider,
	Card,
	Spinner,
	Notice,
	TextControl,
	CheckboxControl
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";


import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function Redirect() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const [showSaveSuccess, setShowSaveSuccess] = useState(false);
	

	useEffect(() => {
		templateDesignerContext.loadSettings();
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
					<Notice status="success" isDismissible={false}>
						<p>
							{__(
								"Your settings have been saved.",
								"wp-html-mail"
							)}
						</p>
					</Notice>
				)}
				<Card className="mail-settings-content">
					<CardHeader>
						<h3>{ __( 'Redirect emails / Email test mode', 'wp-html-mail' ) }</h3>
					</CardHeader>
					<CardBody>
						<p>
							{__(
								'If you want to avoid emails being sent to users or customers, you can temporary redirect all mails to another recipient.',
								"wp-html-mail"
							)}
						</p>
					</CardBody>
					<CardBody>
						<CheckboxControl
							label={__( 'Redirect all emails', 'wp-html-mail' )}
							checked={ ( settings.testmode && settings.testmode !== '0' ) ? settings.testmode : false }
							onChange={ (checked) => {
								templateDesignerContext.updateSetting(
									"testmode",
									checked
								);
							} }
						/>
					</CardBody>
					{settings.testmode &&
						<CardBody>
							<TextControl
								type="email"
								label={__('Recipient address', 'wp-html-mail')}
								onChange={(value) => {
									templateDesignerContext.updateSetting(
										"testmode_recipient",
										value
									);
								}}
								value={settings.testmode_recipient ? settings.testmode_recipient : ""}
							/>
						</CardBody>
					}
					{settings.testmode &&
						<CardBody>
							<CheckboxControl
								label={__( 'also enable debug outputs', 'wp-html-mail' )}
								checked={ ( settings.debugmode && settings.debugmode !== '0' ) ? settings.debugmode : false }
								onChange={ (checked) => {
									templateDesignerContext.updateSetting(
										"debugmode",
										checked
									);
								} }
							/>
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
								setShowSaveSuccess(true);
								setTimeout(() => {
									setShowSaveSuccess(false);
								}, 4000);
							});
						}}
					>
						{__("Save settings", "wp-html-mail")}
					</Button>
				</div>
			</div>
		);
}
