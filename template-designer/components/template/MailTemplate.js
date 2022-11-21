import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	PanelBody,
	PanelRow,
	ColorPicker,
	Spinner,
	Icon,
	TabPanel,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

import OptionsPanel from "./OptionsPanel";
import MailHeader from "./MailHeader";
import MailContent from "./MailContent";
import MailFooter from "./MailFooter";
import EditableElement from "./EditableElement";
import TemplateLibrary from './TemplateLibrary';

export default function MailTemplate() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const elementTitle = __("Email Container", "wp-html-mail");
	const elementName = "container";
	const options = (
		<PanelBody
			title={__("Background color", "wp-html-mail")}
			initialOpen={true}
		>
			<PanelRow>
				<ColorPicker
					color={theme.background}
					onChangeComplete={(value) => {
						templateDesignerContext.updateTheme(
							"background",
							value.hex
						);
					}}
					disableAlpha
				/>
			</PanelRow>
		</PanelBody>
	);


	

	useEffect(() => {
		templateDesignerContext.loadTheme();
	}, []);

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelTitle(elementTitle);
		}
	}, [templateDesignerContext.editingElement]);

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelOptions(options);
		}
	}, [templateDesignerContext.editingElement, theme]);

	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-designer">
				<div
					className="mail-container"
					style={{
						backgroundColor: theme.background,
					}}
				>
					<div className="save-button-pane components-panel__header">
						<TemplateLibrary/>
						<Button
							isPrimary
							isBusy={templateDesignerContext.isSaving}
							onClick={(e) => {
								e.preventDefault();
								templateDesignerContext.saveTheme(() => {
									templateDesignerContext.setInfoMessage(__('Your settings have been saved.', 'wp-html-mail'))
									setTimeout(() => {
										templateDesignerContext.setInfoMessage("");
									}, 7000)
							});
							}}
						>
							{__("Save and Preview", "wp-html-mail")}
						</Button>
					</div>
					<EditableElement
						elementTitle={elementTitle}
						elementName={elementName}
						frameSize="xs"
					>
						<div className="mail-content-wrap">
							<MailHeader />
							<MailContent />
							<MailFooter />
						</div>
					</EditableElement>
				</div>
				<OptionsPanel />
			</div>
		);
}
