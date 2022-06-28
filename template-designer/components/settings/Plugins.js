import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	CardBody,
	CardHeader,
	Panel,
	PanelBody,
	CardDivider,
	Card,
	Spinner,
	Notice,
	CheckboxControl,
	PanelRow
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function Plugins({plugins}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [settings, setSettings] = useState([]);
	
	
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
						<h3>{ __( 'Plugins', 'wp-html-mail' ) }</h3>
					</CardHeader>
					<CardBody>
						<p>
							{__(
								'We try to detect the plugins sending emails so you can customize some settings for each type of emails.',
								"wp-html-mail"
							)}
						</p>
					</CardBody>
					<CardDivider />
					<CardBody>
						<Panel header={__('Active plugins','wp-html-mail')}>
							{plugins.filter( plugin => plugin.active ).map(plugin => 
								<PanelBody
									key={plugin.name}
									title={<>
										{plugin['image_url'] &&
											<img
												src={plugin.image_url}
												className="mail-plugin-icon"
											/>}
										{plugin['display_name']}
									</>}
									initialOpen={false}
								>
									<PanelRow>
										{( plugin.has_addon && !plugin.is_addon_active && plugin.addon_url ) ?
											<Button
												isSecondary
												href={plugin.addon_url.replaceAll('&amp;','&')}
											>
												{__('get WP HTML Mail for', 'wp-html-mail') + ' ' + plugin.display_name}
											</Button>
											: <>
											<CheckboxControl
												label={__( 'Use template', 'wp-html-mail' )}
												checked={
													( settings[plugin.name] && settings[plugin.name].hasOwnProperty('template') )
														? settings[plugin.name].template
														: true}
												onChange={ (checked) => {
													setSettings({...settings,[plugin.name]:{...settings[plugin.name],template: checked}});
												} }
											/>
											<CheckboxControl
												label={__( 'Hide header', 'wp-html-mail' )}
												checked={
													( settings[plugin.name] && settings[plugin.name].hasOwnProperty('hide_header') )
														? settings[plugin.name].hide_header
														: false}
												onChange={ (checked) => {
													setSettings({...settings,[plugin.name]:{...settings[plugin.name],hide_header: checked}});
												} }
											/>
											<CheckboxControl
												label={__( 'Hide footer', 'wp-html-mail' )}
												checked={
													( settings[plugin.name] && settings[plugin.name].hasOwnProperty('hide_footer') )
														? settings[plugin.name].hide_footer
														: false}
												onChange={ (checked) => {
													setSettings({...settings,[plugin.name]:{...settings[plugin.name],hide_footer: checked}});
												} }
											/>
											<CheckboxControl
												label={__( 'Overwrite sender', 'wp-html-mail' )}
												checked={
													( settings[plugin.name] && settings[plugin.name].hasOwnProperty('sender') )
														? settings[plugin.name].sender
														: false}
												onChange={ (checked) => {
													setSettings({...settings,[plugin.name]:{...settings[plugin.name],sender: checked}});
												} }
												/>
											</>
										}
										</PanelRow>

									</PanelBody>
								)
							}
						</Panel>
						<br/><br/>
						<Panel header={__('More supported plugins','wp-html-mail')}>
							{plugins.filter(plugin => !plugin.active).map(plugin =>
								<PanelBody
									key={plugin.name}
									title={<>
										{plugin['image_url'] &&
											<img
												src={plugin.image_url}
												className="mail-plugin-icon"
											/>}
										{plugin['display_name']}
									</>}
									initialOpen={false}
								>
									<PanelRow>
										{plugin.installation_url &&
											<Button
												isSecondary
												href={plugin.installation_url.replaceAll('&amp;','&')}
											>
												{__('install', 'wp-html-mail') + ' ' + plugin.display_name}
											</Button>
										}
										{plugin.addon_url &&
											<Button
												isSecondary
												href={plugin.addon_url.replaceAll('&amp;','&')}
											>
												{__('get WP HTML Mail for', 'wp-html-mail') + ' ' + plugin.display_name}
											</Button>
										}
									</PanelRow>
								</PanelBody>
							)}
						</Panel>
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
