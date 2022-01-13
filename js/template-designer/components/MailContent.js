import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	SelectControl,
	ColorPicker,
	Toolbar,
	TextControl,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { arrowUp, arrowLeft, arrowRight, arrowDown } from "@wordpress/icons";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import { getIntVal } from "../functions/helper-functions";

import EditableElement from "./EditableElement.js";

export default function MailContent({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Email content", "wp-html-mail");
	const elementName = "content";

	const options = (
		<React.Fragment>
			<PanelBody
				title={__("Background color", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.contentbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"contentbackground",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__("Headline Font", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.headlinecolor}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"headlinecolor",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
				<PanelRow>
					<SelectControl
						value={settings.headlinefont}
						options={window.mailTemplateDesigner.fonts}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headlinefont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headlinefontsize",
								value
							);
						}}
						value={getIntVal(settings.headlinefontsize)}
					/>
				</PanelRow>
				<PanelRow>
					<Toolbar
						controls={[
							{
								icon: "editor-alignleft",
								isActive: settings.headlinealign === "left",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlinealign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: settings.headlinealign === "center",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlinealign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: settings.headlinealign === "right",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlinealign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive:
									getIntVal(settings.headlinebold) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlinebold",
										getIntVal(settings.headlinebold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(settings.headlineitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlineitalic",
										getIntVal(settings.headlineitalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									settings.headlinetexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"headlinetexttransform",
										settings.headlinetexttransform ===
											"uppercase"
											? "none"
											: "uppercase"
									),
							},
						]}
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__("Subheadline Font", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.subheadlinecolor}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"subheadlinecolor",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
				<PanelRow>
					<SelectControl
						value={settings.subheadlinefont}
						options={window.mailTemplateDesigner.fonts}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"subheadlinefont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"subheadlinefontsize",
								value
							);
						}}
						value={getIntVal(settings.subheadlinefontsize)}
					/>
				</PanelRow>
				<PanelRow>
					<Toolbar
						controls={[
							{
								icon: "editor-alignleft",
								isActive: settings.subheadlinealign === "left",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlinealign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive:
									settings.subheadlinealign === "center",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlinealign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: settings.subheadlinealign === "right",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlinealign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive:
									getIntVal(settings.subheadlinebold) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlinebold",
										getIntVal(settings.subheadlinebold) ===
											1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(settings.subheadlineitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlineitalic",
										getIntVal(
											settings.subheadlineitalic
										) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									settings.subheadlinetexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"subheadlinetexttransform",
										settings.subheadlinetexttransform ===
											"uppercase"
											? "none"
											: "uppercase"
									),
							},
						]}
					/>
				</PanelRow>
			</PanelBody>

			<PanelBody
				title={__("Content Font", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.textcolor}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"textcolor",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
				<PanelRow>
					<SelectControl
						value={settings.textfont}
						options={window.mailTemplateDesigner.fonts}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"textfont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"textfontsize",
								value
							);
						}}
						value={getIntVal(settings.textfontsize)}
					/>
				</PanelRow>
				<PanelRow>
					<Toolbar
						controls={[
							{
								icon: "editor-alignleft",
								isActive: settings.textalign === "left",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"textalign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: settings.textalign === "center",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"textalign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: settings.textalign === "right",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"textalign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive: getIntVal(settings.textbold) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"textbold",
										getIntVal(settings.textbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive: getIntVal(settings.textitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"textitalic",
										getIntVal(settings.textitalic) === 1
											? 0
											: 1
									),
							},
						]}
					/>
				</PanelRow>
			</PanelBody>

			<PanelBody
				title={__("Link styling", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.linkcolor}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"linkcolor",
								value.hex
							);
						}}
						disableAlpha
					/>
				</PanelRow>
				<PanelRow>
					<Toolbar
						controls={[
							{
								icon: "editor-bold",
								isActive: getIntVal(settings.linkbold) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"linkbold",
										getIntVal(settings.linkbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive: getIntVal(settings.linkitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"linkitalic",
										getIntVal(settings.linkitalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-underline",
								isActive:
									getIntVal(settings.linkunderline) === 1,
								onClick: () =>
									templateDesignerContext.updateSetting(
										"linkunderline",
										getIntVal(settings.linkunderline) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									settings.linktexttransform === "uppercase",
								onClick: () =>
									templateDesignerContext.updateSetting(
										"linktexttransform",
										settings.linktexttransform ===
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

	const h1Styles = {
		fontSize: getIntVal(settings.headlinefontsize),
		fontFamily: settings.headlinefont,
		fontStyle:
			getIntVal(settings.headlineitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(settings.headlinebold) === 1 ? "bold" : "normal",
		textTransform: settings.headlinetexttransform,
		color: settings.headlinecolor,
		textAlign: settings.headlinealign,
		lineHeight: 1.5,
		marginTop: "0.67em",
		marginBottom: "0.67em",
	};

	const h2Styles = {
		fontSize: getIntVal(settings.subheadlinefontsize),
		fontFamily: settings.subheadlinefont,
		fontStyle:
			getIntVal(settings.subheadlineitalic) === 1 ? "italic" : "normal",
		fontWeight:
			getIntVal(settings.subheadlinebold) === 1 ? "bold" : "normal",
		textTransform: settings.subheadlinetexttransform,
		color: settings.subheadlinecolor,
		textAlign: settings.subheadlinealign,
		lineHeight: 1.5,
		marginTop: "0.83em",
		marginBottom: "0.83em",
	};

	const pStyles = {
		fontSize: getIntVal(settings.textfontsize),
		fontFamily: settings.textfont,
		fontStyle: getIntVal(settings.textitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(settings.textbold) === 1 ? "bold" : "normal",
		color: settings.textcolor,
		textAlign: settings.textalign,
		lineHeight: 1.5,
		marginTop: "0.83em",
		marginBottom: "0.83em",
	};

	const aStyles = {
		fontStyle: getIntVal(settings.linkitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(settings.linkbold) === 1 ? "bold" : "normal",
		textDecoration:
			getIntVal(settings.linkunderline) === 1 ? "underline" : "none",
		textTransform: settings.linktexttransform,
		color: settings.linkcolor,
	};

	return (
		<EditableElement elementTitle={elementTitle} elementName={elementName}>
			<div
				className="mail-content clearfix"
				style={{
					backgroundColor: settings.contentbackground,
				}}
			>
				<h1 style={h1Styles}>Lorem ipsum dolor sit amet</h1>
				<p style={pStyles}>
					Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed{" "}
					<a href="#" style={aStyles}>
						diam nonumy
					</a>{" "}
					eirmod tempor invidunt ut labore et dolore magna aliquyam
					erat, sed diam voluptua. At vero eos et accusam et justo duo
					dolores et ea rebum.
					<br />
					Stet clita kasd gubergren, no sea takimata sanctus est Lorem
					ipsum dolor sit amet.
				</p>
				<h2 style={h2Styles}>Sed diam nonumy eirmod tempor</h2>
				<p style={pStyles}>
					Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
					diam nonumy eirmod tempor invidunt ut labore et dolore magna
					aliquyam erat, sed diam voluptua.
				</p>
			</div>
		</EditableElement>
	);
}
