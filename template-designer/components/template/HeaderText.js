import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	FormToggle,
	SelectControl,
	Toolbar,
	TextControl,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";
import { RichText } from "@wordpress/block-editor";

import { PopoverPicker } from "../options/PopoverPicker";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

import {
	getIntVal,
	isTranslationEnabledForField,
	getTranslateableThemeOption,
	getTranslateableThemeOptionsKey,
} from "../../functions/helper-functions";
import EditableElement from "./EditableElement.js";

export default function HeaderText({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const elementTitle = __("Header text", "wp-html-mail");
	const elementName = "headertext";

	const options = (
		<React.Fragment>
			<PanelBody title={__("Font", "wp-html-mail")} initialOpen={true}>
				<PanelRow>
					<SelectControl
						value={theme.headerfont}
						options={templateDesignerContext.availableFonts}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headerfont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headerfontsize",
								value
							);
						}}
						value={getIntVal(theme.headerfontsize)}
					/>
					<PopoverPicker
						color={theme.headercolor}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headercolor",
								value.hex
							);
						}}
					/>
				</PanelRow>
				<PanelRow>
					<Toolbar
						controls={[
							{
								icon: "editor-alignleft",
								isActive: theme.headeralign === "left",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headeralign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: theme.headeralign === "center",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headeralign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: theme.headeralign === "right",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headeralign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive: getIntVal(theme.headerbold) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headerbold",
										getIntVal(theme.headerbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(theme.headeritalic) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headeritalic",
										getIntVal(theme.headeritalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									theme.headertexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headertexttransform",
										theme.headertexttransform ===
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
								theme,
								"headertext"
							)}
							onChange={(e) =>
								templateDesignerContext.updateTheme(
									"headertext_enable_translation",
									!isTranslationEnabledForField(
										theme,
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
	}, [templateDesignerContext.editingElement, theme]);

	const headertext = getTranslateableThemeOption(theme, "headertext");
	const headertext_field_key = getTranslateableThemeOptionsKey(
		theme,
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
					color: theme.headercolor,
					textAlign: theme.headeralign,
					fontSize: getIntVal(theme.headerfontsize),
					fontFamily: theme.headerfont,
					fontStyle:
						getIntVal(theme.headeritalic) === 1
							? "italic"
							: "normal",
					fontWeight:
						getIntVal(theme.headerbold) === 1
							? "bold"
							: "normal",
					textTransform: theme.headertexttransform,
				}}
			>
				<RichText
					tagName="div"
					value={headertext}
					allowedFormats={[]}
					onChange={(value) =>
						templateDesignerContext.updateTheme(
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
