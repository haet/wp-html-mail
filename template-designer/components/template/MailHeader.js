import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	RangeControl,
	RadioControl,
	ColorPicker,
	Icon
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";
import { getIntVal } from "../../functions/helper-functions";

import EditableElement from "./EditableElement.js";
import HeaderText from "./HeaderText.js";
import HeaderImage from "./HeaderImage.js";

export default function MailHeader({}) {
	const [rerenderKey, setRerenderKey] = useState(1);
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const elementTitle = __("Email header", "wp-html-mail");
	const elementName = "header";

	let headerimg_placement = theme.headerimg_placement;
	if (!headerimg_placement) {
		if (theme.headerimg) headerimg_placement = "replace_text";
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
						className="mail-header-type"
						options={[
							{
								value: "just_text",
								label:
									<Icon
										icon={
											<svg xmlns="http://www.w3.org/2000/svg" width="278" height="45" viewBox="0 0 278 45"><g transform="translate(-80 -463)"><rect width="278" height="45" transform="translate(80 463)" fill="#9cbecd"/><text transform="translate(219 488)" fill="#fff" fontSize="18" fontFamily="OpenSans-Extrabold, Open Sans" fontWeight="800"><tspan x="-28.208" y="0">YOUR </tspan><tspan x="-44.868" y="15">SITE TITLE</tspan></text></g></svg>
										}
										className="mail-header-type-icon"
									/>,
							},
							{
								value: "replace_text",
								label: 
									<Icon
										icon={
											<svg xmlns="http://www.w3.org/2000/svg" width="278" height="71" viewBox="0 0 278 71"><g transform="translate(-81 -358)"><rect width="278" height="71" transform="translate(81 358)" fill="#9cbecd"/><g transform="translate(-136 -101)"><path d="M20,0,40,30H0Z" transform="translate(347 490)" fill="#fff"/><path d="M14,0,28,21H0Z" transform="translate(327 499)" fill="#fff"/><circle cx="7" cy="7" r="7" transform="translate(326 478)" fill="#fff"/></g></g></svg>
										}
										className="mail-header-type-icon"
									/>,
							},
							{
								value: "above_text",
								label: 
									<Icon
										icon={
											<svg xmlns="http://www.w3.org/2000/svg" width="278" height="90" viewBox="0 0 278 90"><g transform="translate(-80 -109)"><rect width="278" height="71" transform="translate(80 109)" fill="#9cbecd"/><g transform="translate(-137 -350)"><path d="M20,0,40,30H0Z" transform="translate(347 490)" fill="#fff"/><path d="M14,0,28,21H0Z" transform="translate(327 499)" fill="#fff"/><circle cx="7" cy="7" r="7" transform="translate(326 478)" fill="#fff"/></g><rect width="278" height="15" transform="translate(80 184)" fill="#9cbecd"/><text transform="translate(220 195)" fill="#fff" fontSize="12" fontFamily="OpenSans-Extrabold, Open Sans" fontWeight="800"><tspan x="-48.718" y="0">YOUR SITE TITLE</tspan></text></g></svg>
										}
										className="mail-header-type-icon"
									/>,
							},
							{
								value: "below_text",
								label: 
									<Icon
										icon={
											<svg xmlns="http://www.w3.org/2000/svg" width="278" height="92" viewBox="0 0 278 92"><g transform="translate(-80 -229)"><rect width="278" height="71" transform="translate(80 250)" fill="#9cbecd"/><g transform="translate(-137 -209)"><path d="M20,0,40,30H0Z" transform="translate(347 490)" fill="#fff"/><path d="M14,0,28,21H0Z" transform="translate(327 499)" fill="#fff"/><circle cx="7" cy="7" r="7" transform="translate(326 478)" fill="#fff"/></g><rect width="278" height="15" transform="translate(80 231)" fill="#9cbecd"/><text transform="translate(220 242)" fill="#fff" fontSize="12" fontFamily="OpenSans-Extrabold, Open Sans" fontWeight="800"><tspan x="-48.718" y="0">YOUR SITE TITLE</tspan></text></g></svg>
										}
										className="mail-header-type-icon"
									/>,
							},
						]}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						color={theme.headerbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingtop, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingright, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingbottom, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
						value={getIntVal(theme.headerpaddingleft, 10)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
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
	}, [templateDesignerContext.editingElement, theme]);

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
					backgroundColor: theme.headerbackground,
					paddingTop: getIntVal(theme.headerpaddingtop),
					paddingRight: getIntVal(theme.headerpaddingright),
					paddingBottom: getIntVal(theme.headerpaddingbottom),
					paddingLeft: getIntVal(theme.headerpaddingleft),
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
