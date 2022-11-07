import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	RangeControl,
	TextControl,
	FormToggle,
	Button,
	Toolbar,
	ResizableBox,
} from "@wordpress/components";

import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

import {
	getIntVal,
	isTranslationEnabledForField,
	getTranslateableThemeOption,
	getTranslateableThemeOptionsKey,
} from "../../functions/helper-functions";
import EditableElement from "./EditableElement.js";

export default function HeaderImage({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const elementTitle = __("Header image", "wp-html-mail");
	const elementName = "headerimage";
	const [fileFrame, setFileFrame] = useState();

	const headerimg = getTranslateableThemeOption(theme, "headerimg");
	const headerimg_field_key = getTranslateableThemeOptionsKey(
		theme,
		"headerimg"
	);

	const options = (
		<React.Fragment>
			<PanelBody title={__("Image", "wp-html-mail")} initialOpen={true}>
				<PanelRow>
					<Button
						className={"editor-post-featured-image__toggle"}
						style={
							headerimg
								? {
										backgroundImage:
											"url(" + headerimg + ")",
								  }
								: {}
						}
						onClick={(e) => {
							// If the media frame already exists, reopen it.
							if (fileFrame) {
								fileFrame.open();
								return;
							}
							// Create the media frame.
							const file_frame = (wp.media.frames.file_frame = wp.media(
								{
									multiple: false, // Set to true to allow multiple files to be selected
								}
							));
							// When an image is selected, run a callback.
							file_frame.on("select", function () {
								// We set multiple to false so only get one image from the uploader
								const attachment = file_frame
									.state()
									.get("selection")
									.first()
									.toJSON();

								const height =
									attachment.width > 600
										? Math.round(
												(attachment.height /
													attachment.width) *
													600
										  )
										: attachment.height;
								const width =
									attachment.width > 600
										? 600
										: attachment.width;
								templateDesignerContext.settheme({
									...theme,
									[headerimg_field_key]: attachment.url,
									headerimg_width: width,
									headerimg_height: height,
									headerimg_alt: attachment.alt,
									headerimg_notice: "",
								});
							});
							// Finally, open the modal
							file_frame.open();
							setFileFrame(file_frame);
						}}
					>
						{__("Select image", "wp-html-mail")}
					</Button>
				</PanelRow>
				<PanelRow>
					<TextControl
						name="headerimg"
						value={headerimg}
						help={__(
							"You can use this field to add an external image url.",
							"wp-html-mail"
						)}
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								headerimg_field_key,
								value
							);
						}}
					/>
				</PanelRow>
				{theme.headerimg_notice &&
					theme.headerimg_notice.length > 5 && (
						<PanelRow>
							<p
								dangerouslySetInnerHTML={{
									__html: theme.headerimg_notice,
								}}
							/>
						</PanelRow>
					)}
			</PanelBody>
			<PanelBody
				title={__("Size & Align", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headerimg_width",
								value
							);
						}}
						value={getIntVal(theme.headerimg_width, 400)}
						style={{ width: 70 }}
					/>
					<TextControl
						type="number"
						onChange={(value) => {
							templateDesignerContext.updateTheme(
								"headerimg_height",
								value
							);
						}}
						value={getIntVal(theme.headerimg_height, 100)}
						style={{ width: 70 }}
					/>
					<Toolbar
						controls={[
							{
								icon: "align-left",
								isActive: theme.headerimg_align === "left",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headerimg_align",
										"left"
									),
							},
							{
								icon: "align-center",
								isActive: theme.headerimg_align === "center",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headerimg_align",
										"center"
									),
							},
							{
								icon: "align-right",
								isActive: theme.headerimg_align === "right",
								onClick: () =>
									templateDesignerContext.updateTheme(
										"headerimg_align",
										"right"
									),
							},
						]}
					/>
				</PanelRow>
				{(theme.headerimg_placement === "above_text" ||
					theme.headerimg_placement === "below_text") && (
					<>
						<PanelRow>
							{__(
								"Spacing between image and text",
								"wp-html-mail"
							)}
						</PanelRow>
						<PanelRow>
							<RangeControl
								beforeIcon={"sort"}
								value={getIntVal(theme.header_spacer, 10)}
								onChange={(value) => {
									templateDesignerContext.updateTheme(
										"header_spacer",
										value
									);
								}}
								min={0}
								max={100}
							/>
						</PanelRow>
					</>
				)}
				<PanelRow>
					<p>
						{__(
							"If you would like to provide the header image in retina quality, upload an image with a higher resultion, such as for example 1200px x 400px and enter only half the size values in the input fields above (600 x 200).",
							"wp-html-mail"
						)}
					</p>
				</PanelRow>
			</PanelBody>
			{window.mailTemplateDesigner.isMultiLanguageSite == 1 && (
				<PanelBody
					title={__("Translation", "wp-html-mail")}
					initialOpen={true}
				>
					<PanelRow>
						<FormToggle
							checked={isTranslationEnabledForField(
								theme,
								"headerimg"
							)}
							onChange={(e) =>
								templateDesignerContext.updateTheme(
									"headerimg_enable_translation",
									!isTranslationEnabledForField(
										theme,
										"headerimg"
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
					<PanelRow>
						<p className="description">
							{__(
								"Please make sure all images have the same size because only the image url is language dependent.",
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

	const headerimg_align = theme.headerimg_align
		? theme.headerimg_align
		: theme.headeralign;

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
					textAlign: headerimg_align,
				}}
			>
				{theme.headerimg ? (
					<ResizableBox
						size={{
							height: parseInt(theme.headerimg_height),
							width: parseInt(theme.headerimg_width),
						}}
						lockAspectRatio={
							parseInt(theme.headerimg_width) /
							parseInt(theme.headerimg_height)
						}
						minHeight="20"
						minWidth="50"
						showHandle={
							templateDesignerContext.editingElement ===
							elementName
						}
						style={{
							marginTop:
								theme.headerimg_placement === "below_text"
									? theme.header_spacer + "px"
									: "0px",
							marginBottom:
								theme.headerimg_placement === "above_text"
									? theme.header_spacer + "px"
									: "0px",
						}}
						enable={{
							top: false,
							right: false,
							bottom: false,
							left: false,
							topRight: true,
							bottomRight: true,
							bottomLeft: true,
							topLeft: true,
						}}
						onResizeStop={(event, direction, elt, delta) => {
							templateDesignerContext.setTheme({
								...theme,
								headerimg_width:
									parseInt(theme.headerimg_width) +
									delta.width,
								headerimg_height:
									parseInt(theme.headerimg_height) +
									delta.height,
							});
						}}
					>
						<img src={headerimg} />
					</ResizableBox>
				) : (
					<div
						className="header-image-placeholder"
						style={{
							width: theme.headerimg_width
								? theme.headerimg_width + "px"
								: "400px",
							height: theme.headerimg_height
								? theme.headerimg_height + "px"
								: "100px",
							marginTop:
								theme.headerimg_placement === "below_text"
									? theme.header_spacer + "px"
									: "0px",
							marginBottom:
								theme.headerimg_placement === "above_text"
									? theme.header_spacer + "px"
									: "0px",
						}}
					/>
				)}
			</div>
		</EditableElement>
	);
}
