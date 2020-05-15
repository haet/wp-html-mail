import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	FormToggle,
	ColorPicker,
	SelectControl,
	Toolbar,
	TextControl,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";
import { RichText } from "@wordpress/block-editor";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";

import {
	getIntVal,
	isTranslationEnabledForField,
	getTranslateableThemeOption,
	getTranslateableThemeOptionsKey,
} from "../functions/helper-functions";
import EditableElement from "./EditableElement.js";

export default function HeaderText({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Header text", "wp-html-mail");
	const elementName = "headertext";

	const options = (
		<React.Fragment>
			<PanelBody title={__("Color", "wp-html-mail")} initialOpen={false}>
				<PanelRow>
					<ColorPicker
						color={settings.headercolor}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"headercolor",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody title={__("Font", "wp-html-mail")} initialOpen={false}>
				<PanelRow>
					<SelectControl
						value={settings.headerfont}
						options={window.mailTemplateDesigner.fonts}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerfont",
								value
							);
						}}
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerfontsize",
								value
							);
						}}
						value={getIntVal(settings.headerfontsize)}
					/>
					<Toolbar
						controls={[
							{
								icon: "editor-alignleft",
								isActive: settings.headeralign === "left",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headeralign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: settings.headeralign === "center",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headeralign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: settings.headeralign === "right",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headeralign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive: getIntVal(settings.headerbold) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headerbold",
										getIntVal(settings.headerbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(settings.headeritalic) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headeritalic",
										getIntVal(settings.headeritalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									settings.headertexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headertexttransform",
										settings.headertexttransform ===
											"uppercase"
											? "none"
											: "uppercase"
									),
							},
						]}
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
								settings,
								"headertext"
							)}
							onChange={(e) =>
								templateDesignerContext.updateSetting(
									"headertext_enable_translation",
									!isTranslationEnabledForField(
										settings,
										"headertext"
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

	const headertext = getTranslateableThemeOption(settings, "headertext");
	const headertext_field_key = getTranslateableThemeOptionsKey(
		settings,
		"headertext"
	);
	return (
		<EditableElement
			elementTitle={elementTitle}
			elementName={elementName}
			frameSize="s"
			handleAlign="right"
		>
			<div
				className={elementName}
				style={{
					color: settings.headercolor,
					textAlign: settings.headeralign,
					fontSize: getIntVal(settings.headerfontsize),
					fontFamily: settings.headerfont,
					fontStyle:
						getIntVal(settings.headeritalic) === 1
							? "italic"
							: "normal",
					fontWeight:
						getIntVal(settings.headerbold) === 1
							? "bold"
							: "normal",
					textTransform: settings.headertexttransform,
				}}
			>
				<RichText
					tagName="div"
					value={headertext}
					allowedFormats={[]}
					onChange={(value) =>
						templateDesignerContext.updateSetting(
							headertext_field_key,
							value
						)
					}
					placeholder={__("Header text", "wp-html-mail")}
				/>
			</div>
		</EditableElement>
	);
}
