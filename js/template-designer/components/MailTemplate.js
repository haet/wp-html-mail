import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Button,
	PanelBody,
	PanelRow,
	ColorPicker,
	Spinner,
	Notice,
	Animate,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";

import OptionsPanel from "./OptionsPanel";
import MailHeader from "./MailHeader";
import MailContent from "./MailContent";
import MailFooter from "./MailFooter";
import EditableElement from "./EditableElement";

export default function MailTemplate() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const [isSaving, setIsSaving] = useState(false);
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
					color={settings.background}
					onChangeComplete={(value) => {
						templateDesignerContext.updateSetting(
							"background",
							value.hex
						);
					}}
					disableAlpha
				/>
			</PanelRow>
		</PanelBody>
	);

	const loadSettings = () => {
		var request = new Request(
			window.mailTemplateDesigner.restUrl + "themesettings",
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
				templateDesignerContext.setSettings(data);
				templateDesignerContext.setIsLoading(false);
			});
	};

	const saveSettings = () => {
		setIsSaving(true);
		var request = new Request(
			window.mailTemplateDesigner.restUrl + "themesettings",
			{
				method: "POST",
				body: JSON.stringify(settings),
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.mailTemplateDesigner.nonce
				},
			}
		);
		fetch(request)
			.then((resp) => resp.json())
			.then((data) => {
				setIsSaving(false);
				setShowSaveSuccess(true);
				setTimeout(() => {
					setShowSaveSuccess(false);
				}, 4000);
				if (data.preview) {
					haet_mail.previewMail(data.preview);
				}
			});
	};

	useEffect(() => {
		loadSettings();
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
	}, [templateDesignerContext.editingElement, settings]);

	if (templateDesignerContext.isLoading || !templateDesignerContext.settings)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<React.Fragment>
				<div
					className="mail-container"
					style={{
						backgroundColor: settings.background,
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
							isBusy={isSaving}
							onClick={(e) => {
								e.preventDefault();
								saveSettings();
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
			</React.Fragment>
		);
}
