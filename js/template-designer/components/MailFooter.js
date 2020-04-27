import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	ColorPicker,
	FormToggle,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { arrowUp, arrowLeft, arrowRight, arrowDown } from "@wordpress/icons";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import {
	isTranslationEnabledForField,
	getTranslateableThemeOption,
	getTranslateableThemeOptionsKey,
} from "../functions/helper-functions";

import EditableElement from "./EditableElement.js";

export default function MailFooter({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [editor, setEditor] = useState(false);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Email footer", "wp-html-mail");
	const elementName = "footer";
	const footer = getTranslateableThemeOption(settings, "footer");
	const footer_field_key = getTranslateableThemeOptionsKey(
		settings,
		"footer"
	);

	const options = (
		<React.Fragment>
			<PanelBody
				title={__("Background color", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.footerbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"footerbackground",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
			</PanelBody>
			{/* <PanelBody
				title={__("Padding", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<RangeControl
						beforeIcon={arrowDown}
						value={getIntVal(settings.headerpaddingtop, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingtop",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon={arrowLeft}
						value={getIntVal(settings.headerpaddingright, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingright",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon={arrowUp}
						value={getIntVal(settings.headerpaddingbottom, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingbottom",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon={arrowRight}
						value={getIntVal(settings.headerpaddingleft, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingleft",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
			</PanelBody> */}
			{window.mailTemplateDesigner.isMultiLanguageSite == 1 && (
				<PanelBody
					title={__("Translation", "wp-html-mail")}
					initialOpen={false}
				>
					<PanelRow>
						<FormToggle
							checked={isTranslationEnabledForField(
								settings,
								"footer"
							)}
							onChange={(e) =>
								templateDesignerContext.updateSetting(
									"footer_enable_translation",
									!isTranslationEnabledForField(
										settings,
										"footer"
									)
								)
							}
						/>
						{__(
							"Enable translation for this field",
							"wp-html-mail"
						)}
					</PanelRow>
					<PanelRow>
						<p className="description">
							{__(
								"If enabled you can use individual settings depending on the current language selected at the top of the page in your admin bar.",
								"wp-html-mail"
							)}
						</p>
					</PanelRow>
				</PanelBody>
			)}
		</React.Fragment>
	);

	const onEditorSetup = (newEditor) => {
		setEditor(newEditor);
		if (footer) {
			newEditor.on("loadContent", () => newEditor.setContent(footer));
		}

		newEditor.on("blur", () => {
			templateDesignerContext.updateSetting(
				footer_field_key,
				newEditor.getContent()
			);

			return false;
		});
	};

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelTitle(elementTitle);
		}
	}, [templateDesignerContext.editingElement]);

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelOptions(options);
			window.setTimeout(() => {
				let availableFonts = "";
				window.mailTemplateDesigner.fonts.forEach((font) => {
					availableFonts += font.label + "=" + font.value + ";";
				});
				wp.oldEditor.initialize("footer-html", {
					tinymce: {
						inline: true,
						content_css: false,
						fixed_toolbar_container: "footer-editor-toolbar",
						toolbar1: "fontselect,fontsizeselect,|,undo,redo",
						toolbar2:
							"forecolor,|,bold,italic,|,alignleft,aligncenter,alignright,|,pastetext,|,link,unlink",
						font_formats: availableFonts,
						fontsize_formats:
							"10px 11px 12px 13px 14px 15px 16px 17px 18px 20px 22px 24px 26px 28px 30px 35px 40px",
						setup: onEditorSetup,
					},
				});
			}, 1000);
		} else {
			wp.oldEditor.remove("footer-html");
		}
	}, [templateDesignerContext.editingElement, settings]);

	return (
		<EditableElement elementTitle={elementTitle} elementName={elementName}>
			<div
				className="mail-footer"
				style={{
					backgroundColor: settings.footerbackground,
				}}
			>
				<div
					key="toolbar"
					id="footer-editor-toolbar"
					className="block-library-classic__toolbar"
					onKeyDown={(e) => {
						elementName.stopPropagation();
						e.nativeEvent.stopImmediatePropagation();
					}}
				/>
				<div
					key="editor"
					id="footer-html"
					className="wp-block-freeform block-library-rich-text__tinymce"
					dangerouslySetInnerHTML={{
						__html: footer,
					}}
				/>
			</div>
		</EditableElement>
	);
}
