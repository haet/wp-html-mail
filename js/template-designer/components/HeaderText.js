import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
	TextControl,
	ColorPicker,
	SelectControl,
	Toolbar,
	__experimentalNumberControl as NumberControl,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";
import { RichText } from "@wordpress/block-editor";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";

import { getIntVal } from "../functions/helper-functions";
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
					<NumberControl
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
					value={settings.headertext}
					allowedFormats={[]}
					onChange={(value) =>
						templateDesignerContext.updateSetting(
							"headertext",
							value
						)
					}
					placeholder={__("Header text", "wp-html-mail")}
				/>
			</div>
		</EditableElement>
	);
}
