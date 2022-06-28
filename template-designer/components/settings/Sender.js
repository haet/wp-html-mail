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

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function Sender() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;

	useEffect(() => {
		templateDesignerContext.loadSettings();
	}, []);

	

	const searchForActiveMailDeliveryPlugins = () => {
		const searchTerms = ['smtp', 'mandrill'];
		let activeMailDeliveryPlugins = [];
		searchTerms.forEach(searchTerm => {
			const results = useSelect(
				select =>
					select(coreDataStore).getEntityRecords(
						'root',
						'plugin',
						{
							search: searchTerm,
							status: 'active'
						}),
				[]
			);

			if (results && results.length) {
				activeMailDeliveryPlugins = [...activeMailDeliveryPlugins, ...results];
			}
		});
		
		// retry if no result yet
		// if (activeMailDeliveryPlugins === null) {
		// 	setTimeout(() => searchForActiveMailDeliveryPlugins(), 1000);
		// } else {
			if (activeMailDeliveryPlugins.length === 0) {
				return false;
			} else {
				return activeMailDeliveryPlugins[0]['name'];
			}
			
		//}
		console.log("activeMailDeliveryPlugins: ", activeMailDeliveryPlugins);
	}
	
	const mailDeliveryPlugin = searchForActiveMailDeliveryPlugins();
	
	
	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-settings">
				{mailDeliveryPlugin && (
					<Notice status="warning" isDismissible={true}>
						<p>
							{sprintf(
								__(
									'It seems you are using an email delivery plugin (%s).',
									"wp-html-mail"
								),mailDeliveryPlugin
							)}
						</p>
						<p>
							{sprintf(
								__(
									'Please make sure you use the same email address here as in %s. Otherwise your emails could be categorized as junk or not delivered at all.',
									"wp-html-mail"
								),mailDeliveryPlugin
							)}
						</p>
					</Notice>
				)}
				<Card className="mail-settings-content">
					<CardHeader>
						<h3>{ __( 'Email sender', 'wp-html-mail' ) }</h3>
					</CardHeader>
					<CardBody>
						<p>
							{__(
								'Typically WordPress sends all emails using "WordPress" as sender name and "wordpress@yourserver.com" as sender address which is totally fine for admin notifications but not for any kind of emails to you users.',
								"wp-html-mail"
							)}
						</p>
					</CardBody>
					<CardBody>
						<TextControl
							type="text"
							label={__( 'Email sender name', 'wp-html-mail' )}
							onChange={(value) => {
								templateDesignerContext.updateSetting(
									"fromname",
									value
								);
							}}
							value={settings.fromname ? settings.fromname : ""}
						/>
					</CardBody>
					<CardBody>
						<TextControl
							type="email"
							label={__( 'Email sender address', 'wp-html-mail' )}
							onChange={(value) => {
								templateDesignerContext.updateSetting(
									"fromaddress",
									value
								);
							}}
							value={settings.fromaddress ? settings.fromaddress : ""}
						/>
					</CardBody>
					<CardDivider />
					<CardBody>
						<CheckboxControl
							label={__( 'Do not change email sender by default', 'wp-html-mail' )}
							help={__( 'Normally we change the sender name and sender address of all emails. You can customize this for each plugin on tab "Plugins" but you can also set a default value here.', 'wp-html-mail' )}
							checked={ settings.disable_sender ? settings.disable_sender : false }
							onChange={ (checked) => {
								templateDesignerContext.updateSetting(
									"disable_sender",
									checked
								);
							} }
						/>
					</CardBody>
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
