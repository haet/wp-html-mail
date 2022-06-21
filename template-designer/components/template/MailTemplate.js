import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	PanelBody,
	PanelRow,
	ColorPicker,
	Spinner,
	Notice,
	TabPanel,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

import OptionsPanel from "./OptionsPanel";
import MailHeader from "./MailHeader";
import MailContent from "./MailContent";
import MailFooter from "./MailFooter";
import EditableElement from "./EditableElement";

export default function MailTemplate() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const [showSaveSuccess, setShowSaveSuccess] = useState(false);
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
					{showSaveSuccess && (
						<Notice status="success" isDismissible={false}>
							<p>
								{__(
									"Your settings have been saved. Check the preview of your email at the bottom of the page.",
									"wp-html-mail"
								)}
							</p>
						</Notice>
					)}

					<div className="save-button-pane components-panel__header">
						<Button
							isSecondary
							href={
								window.mailTemplateDesigner.templateLibraryUrl
							}
							style={{ marginRight: 10 }}
						>
							{__("Browse our template library", "wp-html-mail")}
						</Button>
						<Button
							isPrimary
							isBusy={templateDesignerContext.isSaving}
							onClick={(e) => {
								e.preventDefault();
								templateDesignerContext.saveTheme(() => {
								setShowSaveSuccess(true);
								setTimeout(() => {
									setShowSaveSuccess(false);
								}, 4000);
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
