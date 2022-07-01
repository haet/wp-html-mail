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
	TextareaControl,
	ToggleControl,
	Panel,
	PanelBody,
	PanelRow,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";



import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function AdvancedSettings() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { setSettings,settings,theme } = templateDesignerContext;
	const [importTheme, setImportTheme] = useState("");
	const [advancedActions, setAdvancedActions] = useState("");
	

	useEffect(() => {
		templateDesignerContext.loadSettings();
		templateDesignerContext.loadTheme();
		loadAdvancedActions();
	}, []);


	const loadAdvancedActions = () => {
		var request = new Request(
			window.mailTemplateDesigner.restUrl + "advancedactions",
			{
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.mailTemplateDesigner.nonce
				},
				credentials: "same-origin"
			});
		fetch(request)
			.then((resp) => resp.json())
			.then((data) => {
				setAdvancedActions(data);
			});
	};
	

	const renderActionButtons = (buttons) => {
		return <>
			{buttons.map((button, i) => <Button
				variant="secondary"
				key={'advanced-action-' + i}
				href={button.href}
				disabled={button.disabled}
				showTooltip={ Boolean( button.tooltip )}
				label={button.tooltip ? button.tooltip : null}
				className="mail-advanced-action-button"
				onClick={(e) => {
					if (button.confirm) {
						e.preventDefault();
						templateDesignerContext.setConfirmDialog({
							message: button.confirm,
							callback: () => {
								window.location.href = button.href;
							}
						})
					} 
						
				}}
			>
				{button.caption}
			</Button>
			)}
		</>;
	};
	
	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-settings">
				<Card className="mail-settings-content">
					<CardHeader>
						<h3>{ __( 'Advanced features', 'wp-html-mail' ) }</h3>
					</CardHeader>
					<CardBody>
						<ToggleControl
							label={__( 'Allow HTML code in plain text content type', 'wp-html-mail' )}
							checked={(settings.invalid_contenttype_to_html && settings.invalid_contenttype_to_html !== '0') ? settings.invalid_contenttype_to_html : false}
							help={__('From security perspective you should not enable this option but some plugin developers send HTML code in their emails without setting the correct content type header, so you might see HTML tags in your emails.', 'wp-html-mail')}
							onChange={(checked) => {
								const newSettings = { ...settings, invalid_contenttype_to_html: checked }
								setSettings(newSettings);
								templateDesignerContext.saveSettings(() => {
									templateDesignerContext.setInfoMessage(__('Your settings have been saved.', 'wp-html-mail'))
										setTimeout(() => {
											templateDesignerContext.setInfoMessage("");
										}, 40000)
									}, newSettings);
								}}
						/>
					</CardBody>
					
					
					<CardDivider />	

					<CardBody>
						<Panel className="mail-settings-content">
							<PanelBody title={__("Export template", "wp-html-mail")} initialOpen={ false }>
								<PanelRow>
									<TextareaControl
										help={__('Copy the settings above and paste into another site.', 'wp-html-mail')}
										readOnly
										className="mail-import-export"
										value={JSON.stringify(theme)}
									/>
								</PanelRow>
							</PanelBody>
							<PanelBody title={__("Import template", "wp-html-mail")} initialOpen={ false }>
								<PanelRow>
									<TextareaControl
										help={__('Paste settings from another site to the field above.', 'wp-html-mail')}
										className="mail-import-export"
										value={importTheme}
										onChange={ (value) => {
											setImportTheme(value);
										} }
									/>
								</PanelRow>
								<PanelRow>
									<Button
										variant="secondary"
										onClick={(e) => {
											try {
												const imported = JSON.parse(importTheme);
												const newTheme = {...templateDesignerContext.theme};
												console.log(imported);
												Object.keys(newTheme).forEach(key => {
													if (key in imported) {
														newTheme[key] = imported[key];
													}
												});
												templateDesignerContext.setTheme(newTheme);
												templateDesignerContext.saveTheme(null,newTheme);
												templateDesignerContext.setInfoMessage(__('Your settings have been imported.', 'wp-html-mail'))
												setTimeout(() => {
													templateDesignerContext.setInfoMessage("");
												}, 7000)
												setTimeout(() => {
													setImportTheme("");
												}, 5000)
											}catch(e){
												templateDesignerContext.setErrorMessage(__('Your settings could not be imported.', 'wp-html-mail'))
												setTimeout(() => {
													templateDesignerContext.setErrorMessage("");
												}, 15000)
											}
										}}
									>
										{__("Start Import", "wp-html-mail")}
									</Button>
								</PanelRow>
							</PanelBody>
						</Panel>
					</CardBody>

					{advancedActions && advancedActions.template &&
						<CardBody>
							<h3>{__('Create custom template', 'wp-html-mail')}</h3>
							{renderActionButtons(advancedActions.template.buttons)}
							{advancedActions.template.description &&
								<div
									dangerouslySetInnerHTML={{
										__html: advancedActions.template.description,
									}}
								/>
							}
						</CardBody>
					}
					<CardDivider />	
					{advancedActions && advancedActions.reset &&
						<CardBody>
							<h3>{__('Delete plugin settings', 'wp-html-mail')}</h3>
							{renderActionButtons(advancedActions.reset.buttons)}
							{advancedActions.reset.description &&
								<div
									dangerouslySetInnerHTML={{
										__html: advancedActions.reset.description,
									}}
								/>
							}
						</CardBody>
					}
					{advancedActions && advancedActions.debug && advancedActions.debug.buttons &&
						<>
							<CardDivider />	
							<CardBody>
								<h3>{__('Debug', 'wp-html-mail')}</h3>
							{renderActionButtons(advancedActions.debug.buttons)}
							{advancedActions.debug.description &&
								<div
									dangerouslySetInnerHTML={{
										__html: advancedActions.debug.description,
									}}
								/>
							}
							</CardBody>
						</>
					}
				</Card>
				{/* <div className="save-button-pane-bottom">
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
				</div> */}
			</div>
		);
}
