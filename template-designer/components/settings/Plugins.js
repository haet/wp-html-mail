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
	ToggleControl,
	PanelRow
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";


export default function Plugins({plugins}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { setPluginSettings,pluginSettings,loadPluginSettings } = templateDesignerContext;
	
	useEffect(() => {
		loadPluginSettings();
	}, []);


	const handleToggle = (plugin_name, setting_name, checked) => {
		const newPluginSettings = { ...pluginSettings, [plugin_name]: { ...pluginSettings[plugin_name], [setting_name]: checked } };
		setPluginSettings(newPluginSettings);
		templateDesignerContext.savePluginSettings(() => {
			templateDesignerContext.setInfoMessage(__('Your settings have been saved.', 'wp-html-mail'))
				setTimeout(() => {
					templateDesignerContext.setInfoMessage("");
				}, 7000)
			}, newPluginSettings);
	}

	

	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-pluginSettings">
				<Card className="mail-pluginSettings-content">
					<CardHeader>
						<h3>{ __( 'Plugins', 'wp-html-mail' ) }</h3>
					</CardHeader>
					<CardBody>
						<p>
							{__(
								'We try to detect the plugins sending emails so you can customize some pluginSettings for each type of emails.',
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
											: (
												pluginSettings.hasOwnProperty(plugin.name) ?
													<>
														<ToggleControl
																label={__('Use template', 'wp-html-mail')}
																checked={
																	(pluginSettings[plugin.name] && pluginSettings[plugin.name].hasOwnProperty('template'))
																		? pluginSettings[plugin.name].template
																		: true}
																onChange={(checked) => {
																	handleToggle(plugin.name, 'template', checked);
																}}
														/>
														<ToggleControl
															label={__( 'Overwrite sender', 'wp-html-mail' )}
															checked={
																( pluginSettings[plugin.name] && pluginSettings[plugin.name].hasOwnProperty('sender') )
																	? pluginSettings[plugin.name].sender
																	: false}
															onChange={ (checked) => {
																handleToggle(plugin.name, 'sender', checked);	
															} }
														/>
														<ToggleControl
															label={__( 'Hide header', 'wp-html-mail' )}
															checked={
																( pluginSettings[plugin.name] && pluginSettings[plugin.name].hasOwnProperty('hide_header') )
																	? pluginSettings[plugin.name].hide_header
																	: false}
																onChange={(checked) => {
																	handleToggle(plugin.name, 'hide_header', checked);	
															} }
														/>
														<ToggleControl
															label={__( 'Hide footer', 'wp-html-mail' )}
															checked={
																( pluginSettings[plugin.name] && pluginSettings[plugin.name].hasOwnProperty('hide_footer') )
																	? pluginSettings[plugin.name].hide_footer
																	: false}
															onChange={ (checked) => {
																handleToggle(plugin.name, 'hide_footer', checked);	
															} }
														/>
													</>
													:
													<Spinner />
											)
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
			</div>
		);
}
