import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
	TextControl,
	ColorPicker,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { arrowUp } from "@wordpress/icons";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext.jsx";

import { getIntVal } from "../functions/helper-functions.jsx";
import EditableElement from "./EditableElement.jsx";

export default function HeaderText({}) {
	const [rerenderKey, setRerenderKey] = useState(1);
	const [headertext, setHeadertext] = useState("");
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Header text", "wp-html-mail");
	const elementName = "headertext";

	useEffect(() => {
		setHeadertext(settings.headertext);
	}, []);

	const options = (
		<React.Fragment>
			<PanelBody title={elementTitle} initialOpen={true}>
				<PanelRow>
					<TextControl
						name="headertext"
						value={headertext}
						onChange={(value) => {
							setHeadertext(value);
							templateDesignerContext.updateSetting(
								"headertext",
								value
							);
							forceRerender();
						}}
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__("Background color", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.headerbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
								"headerbackground",
								value.hex
							);
							forceRerender();
						}}
						disableAlpha
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__("Padding", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow></PanelRow>
			</PanelBody>
		</React.Fragment>
	);

	useEffect(() => {
		if (templateDesignerContext.editingElement === elementName) {
			templateDesignerContext.setPanelTitle(elementTitle);
			templateDesignerContext.setPanelOptions(options);
		}
	}, [templateDesignerContext.editingElement]);

	const forceRerender = () => {
		setRerenderKey(
			Math.random()
				.toString(36)
				.replace(/[^a-z]+/g, "")
				.substr(0, 5)
		);
	};

	return (
		<EditableElement
			elementTitle={elementTitle}
			elementName={elementName}
			key={rerenderKey}
		>
			<div
				className="headertext"
				style={{
					color: settings.headercolor,
					textAlign: settings.headeralign,
					fontSize: getIntVal(settings.headerfontsize),
					fontFamily: settings.headerfont,
					fontStyle: settings.headeritalic == 1 ? "italic" : "normal",
					fontWeight: settings.headerbold == 1 ? "bold" : "normal",
				}}
			>
				{/* next steps: 
				1.move getIntVal to a helper functions file and use for fontSize 
				2.make a components settings local state variables to update faster??*/}
				{settings.headertext}
			</div>
		</EditableElement>
	);
}
