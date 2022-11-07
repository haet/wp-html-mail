import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	ColorPicker,
	FormToggle,
	TextareaControl,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { arrowUp, arrowLeft, arrowRight, arrowDown } from "@wordpress/icons";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";
import {
	isTranslationEnabledForField,
	getTranslateableThemeOption,
	getTranslateableThemeOptionsKey,
} from "../../functions/helper-functions";

import EditableElement from "./EditableElement.js";

export default function MailFooter({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [editor, setEditor] = useState(false);
	const { theme } = templateDesignerContext;
	const elementTitle = __("Email footer", "wp-html-mail");
	const elementName = "footer";
	let footer = getTranslateableThemeOption(theme, "footer")
	if (footer)
		footer.replace(
			/\\(.)/gm,
			"$1"
		); // this is some kind of stripslashes
	const footer_field_key = getTranslateableThemeOptionsKey(
		theme,
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
						color={theme.footerbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingtop, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingright, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingbottom, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingleft, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headerpaddingleft",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
			</PanelBody> */}
			<PanelBody
				title={__("Edit HTML", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<TextareaControl
						help={__(
							"Just in case the editor on the left is not flexible enough you can use this field to edit the HTML code.",
							"wp-html-mail"
						)}
						className="footer-html"
						value={footer}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								footer_field_key,
								value
							);
						}}
						placeholder={__("your code snippet…")}
					/>
				</PanelRow>
			</PanelBody>
			{window.mailTemplateDesigner.isMultiLanguageSite == 1 && (
				<PanelBody
					title={__("Translation", "wp-html-mail")}
					initialOpen={false}
				>
					<PanelRow>
						<FormToggle
							checked={isTranslationEnabledForField(
								theme,
								"footer"
							)}
							onChange={(e) =>
								templateDesignerContext.updateTheme(
									"footer_enable_translation",
									!isTranslationEnabledForField(
										theme,
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
			templateDesignerContext.updateTheme(
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
				templateDesignerContext.availableFonts.forEach((font) => {
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
	}, [templateDesignerContext.editingElement, theme]);

	return (
		<EditableElement elementTitle={elementTitle} elementName={elementName}>
			<div
				className="mail-footer clearfix"
				style={{
					backgroundColor: theme.footerbackground,
				}}
			>
				
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
