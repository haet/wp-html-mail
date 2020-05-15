import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	RangeControl,
	RadioControl,
	ColorPicker,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import { getIntVal } from "../functions/helper-functions";

import EditableElement from "./EditableElement.js";
import HeaderText from "./HeaderText.js";
import HeaderImage from "./HeaderImage.js";

export default function MailHeader({}) {
	const [rerenderKey, setRerenderKey] = useState(1);
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Email header", "wp-html-mail");
	const elementName = "header";

	let headerimg_placement = settings.headerimg_placement;
	if (!headerimg_placement) {
		if (settings.headerimg) headerimg_placement = "replace_text";
		else headerimg_placement = "just_text";
	}

	const options = (
		<React.Fragment>
			<PanelBody
				title={__("Image and text placement", "wp-html-mail")}
				initialOpen={true}
			>
				<PanelRow>
					<RadioControl
						selected={headerimg_placement}
						options={[
							{
								value: "just_text",
								label: __(
									"Show just a text header",
									"wp-html-mail"
								),
							},
							{
								value: "replace_text",
								label: __("Show image only", "wp-html-mail"),
							},
							{
								value: "above_text",
								label: __(
									"Show image above text",
									"wp-html-mail"
								),
							},
							{
								value: "below_text",
								label: __(
									"Show image below text",
									"wp-html-mail"
								),
							},
						]}
						onChange={(value) => {
							console.log(value, settings.headerimg_placement);
							templateDesignerContext.updateSetting(
								"headerimg_placement",
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
				<PanelRow>
					<RangeControl
						beforeIcon="arrow-down"
						value={getIntVal(settings.headerpaddingtop, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingtop",
								value
							);
							forceRerender();
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon="arrow-left"
						value={getIntVal(settings.headerpaddingright, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingright",
								value
							);
							forceRerender();
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon="arrow-up"
						value={getIntVal(settings.headerpaddingbottom, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingbottom",
								value
							);
							forceRerender();
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
				<PanelRow>
					<RangeControl
						beforeIcon="arrow-right"
						value={getIntVal(settings.headerpaddingleft, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingleft",
								value
							);
							forceRerender();
						}}
						min={0}
						max={100}
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
				className="mail-header"
				style={{
					backgroundColor: settings.headerbackground,
					paddingTop: getIntVal(settings.headerpaddingtop),
					paddingRight: getIntVal(settings.headerpaddingright),
					paddingBottom: getIntVal(settings.headerpaddingbottom),
					paddingLeft: getIntVal(settings.headerpaddingleft),
				}}
			>
				{headerimg_placement === "replace_text" && <HeaderImage />}
				{headerimg_placement === "above_text" && (
					<>
						<HeaderImage />
						<HeaderText />
					</>
				)}
				{headerimg_placement === "below_text" && (
					<>
						<HeaderText />
						<HeaderImage />
					</>
				)}
				{headerimg_placement === "just_text" && <HeaderText />}
			</div>
		</EditableElement>
	);
}
