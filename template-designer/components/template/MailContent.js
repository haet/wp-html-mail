import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	SelectControl,
	ColorPicker,
	Toolbar,
	TextControl,
} from "@wordpress/components";

import { PopoverPicker } from "../options/PopoverPicker";

import { __ } from "@wordpress/i18n";

import { arrowUp, arrowLeft, arrowRight, arrowDown } from "@wordpress/icons";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";
import { getIntVal } from "../../functions/helper-functions";

import EditableElement from "./EditableElement.js";

export default function MailContent({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
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
						color={theme.contentbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateTheme(
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
					<SelectControl
						value={theme.headlinefont}
						options={templateDesignerContext.availableFonts}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headlinefont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headlinefontsize",
								value
							);
						}}
						value={getIntVal(theme.headlinefontsize)}
					/>
					<PopoverPicker
						color={theme.headlinecolor}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headlinecolor",
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
								isActive: theme.headlinealign === "left",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlinealign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: theme.headlinealign === "center",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlinealign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: theme.headlinealign === "right",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlinealign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive:
									getIntVal(theme.headlinebold) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlinebold",
										getIntVal(theme.headlinebold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(theme.headlineitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlineitalic",
										getIntVal(theme.headlineitalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									theme.headlinetexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headlinetexttransform",
										theme.headlinetexttransform ===
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
					<SelectControl
						value={theme.subheadlinefont}
						options={templateDesignerContext.availableFonts}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"subheadlinefont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"subheadlinefontsize",
								value
							);
						}}
						value={getIntVal(theme.subheadlinefontsize)}
					/>
					<PopoverPicker
						color={theme.subheadlinecolor}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"subheadlinecolor",
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
								isActive: theme.subheadlinealign === "left",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlinealign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive:
									theme.subheadlinealign === "center",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlinealign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: theme.subheadlinealign === "right",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlinealign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive:
									getIntVal(theme.subheadlinebold) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlinebold",
										getIntVal(theme.subheadlinebold) ===
											1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive:
									getIntVal(theme.subheadlineitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlineitalic",
										getIntVal(
											theme.subheadlineitalic
										) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									theme.subheadlinetexttransform ===
									"uppercase",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"subheadlinetexttransform",
										theme.subheadlinetexttransform ===
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
					<SelectControl
						value={theme.textfont}
						options={templateDesignerContext.availableFonts}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"textfont",
								value
							);
						}}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"textfontsize",
								value
							);
						}}
						value={getIntVal(theme.textfontsize)}
					/>
					<PopoverPicker
						color={theme.textcolor}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"textcolor",
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
								isActive: theme.textalign === "left",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"textalign",
										"left"
									),
							},
							{
								icon: "editor-aligncenter",
								isActive: theme.textalign === "center",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"textalign",
										"center"
									),
							},
							{
								icon: "editor-alignright",
								isActive: theme.textalign === "right",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"textalign",
										"right"
									),
							},
							{
								icon: "editor-bold",
								isActive: getIntVal(theme.textbold) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"textbold",
										getIntVal(theme.textbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive: getIntVal(theme.textitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"textitalic",
										getIntVal(theme.textitalic) === 1
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
					<Toolbar
						controls={[
							{
								icon: "editor-bold",
								isActive: getIntVal(theme.linkbold) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"linkbold",
										getIntVal(theme.linkbold) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-italic",
								isActive: getIntVal(theme.linkitalic) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"linkitalic",
										getIntVal(theme.linkitalic) === 1
											? 0
											: 1
									),
							},
							{
								icon: "editor-underline",
								isActive:
									getIntVal(theme.linkunderline) === 1,
								onClick: () =>
									templateDesignerContext.updateTheme(
										"linkunderline",
										getIntVal(theme.linkunderline) === 1
											? 0
											: 1
									),
							},
							{
								icon: "arrow-up",
								isActive:
									theme.linktexttransform === "uppercase",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"linktexttransform",
										theme.linktexttransform ===
											"uppercase"
											? "none"
											: "uppercase"
									),
							},
						]}
					/>
					<PopoverPicker
						color={theme.linkcolor}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"linkcolor",
								value.hex
							);
						}}
					/>
				</PanelRow>
			</PanelBody>
			<PanelRow className="mail-info-panel">
				<p>
					{__(
						"Do you wonder why you can't change the email content?",
						"wp-html-mail"
					)}
					<br/>
					{__(
						"This is not a newsletter tool, this plugins catches all outgoing WordPress emails as well as mails from most of your plugins and wraps those messages in your nice template. So the content could come from your contact form, a comment notification your store sales and many more emails.",
						"wp-html-mail"
					)}
				</p>
			</PanelRow>
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

	const h1Styles = {
		fontSize: getIntVal(theme.headlinefontsize),
		fontFamily: theme.headlinefont,
		fontStyle:
			getIntVal(theme.headlineitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(theme.headlinebold) === 1 ? "bold" : "normal",
		textTransform: theme.headlinetexttransform,
		color: theme.headlinecolor,
		textAlign: theme.headlinealign,
		lineHeight: 1.5,
		marginTop: "0.67em",
		marginBottom: "0.67em",
	};

	const h2Styles = {
		fontSize: getIntVal(theme.subheadlinefontsize),
		fontFamily: theme.subheadlinefont,
		fontStyle:
			getIntVal(theme.subheadlineitalic) === 1 ? "italic" : "normal",
		fontWeight:
			getIntVal(theme.subheadlinebold) === 1 ? "bold" : "normal",
		textTransform: theme.subheadlinetexttransform,
		color: theme.subheadlinecolor,
		textAlign: theme.subheadlinealign,
		lineHeight: 1.5,
		marginTop: "0.83em",
		marginBottom: "0.83em",
	};

	const pStyles = {
		fontSize: getIntVal(theme.textfontsize),
		fontFamily: theme.textfont,
		fontStyle: getIntVal(theme.textitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(theme.textbold) === 1 ? "bold" : "normal",
		color: theme.textcolor,
		textAlign: theme.textalign,
		lineHeight: 1.5,
		marginTop: "0.83em",
		marginBottom: "0.83em",
	};

	const aStyles = {
		fontStyle: getIntVal(theme.linkitalic) === 1 ? "italic" : "normal",
		fontWeight: getIntVal(theme.linkbold) === 1 ? "bold" : "normal",
		textDecoration:
			getIntVal(theme.linkunderline) === 1 ? "underline" : "none",
		textTransform: theme.linktexttransform,
		color: theme.linkcolor,
	};

	return (
		<EditableElement elementTitle={elementTitle} elementName={elementName}>
			<div
				className="mail-content clearfix"
				style={{
					backgroundColor: theme.contentbackground,
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
