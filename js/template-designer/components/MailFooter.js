import { useState, useEffect, useContext } from "@wordpress/element";

import {
	PanelBody,
	PanelRow,
	RangeControl,
	RadioControl,
	ColorPicker,
} from "@wordpress/components";
import { RichText } from "@wordpress/block-editor";

import { __ } from "@wordpress/i18n";

import { arrowUp, arrowLeft, arrowRight, arrowDown } from "@wordpress/icons";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import { getIntVal } from "../functions/helper-functions";

import EditableElement from "./EditableElement.js";

export default function MailFooter({}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [editor, setEditor] = useState(false);
	const { settings } = templateDesignerContext;
	const elementTitle = __("Email footer", "wp-html-mail");
	const elementName = "footer";

	const options = (
		<React.Fragment>
			<PanelBody
				title={__("Background color", "wp-html-mail")}
				initialOpen={false}
			>
				<PanelRow>
					<ColorPicker
						color={settings.footerbackground}
						onChangeComplete={(value) => {
							templateDesignerContext.updateSetting(
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
						value={getIntVal(settings.headerpaddingtop, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
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
						value={getIntVal(settings.headerpaddingright, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
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
						value={getIntVal(settings.headerpaddingbottom, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
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
						value={getIntVal(settings.headerpaddingleft, 10)}
						onChange={(value) => {
							templateDesignerContext.updateSetting(
								"headerpaddingleft",
								value
							);
						}}
						min={0}
						max={100}
					/>
				</PanelRow>
			</PanelBody> */}
		</React.Fragment>
	);

	const onEditorSetup = (newEditor) => {
		setEditor(newEditor);
		if (settings.footer) {
			newEditor.on("loadContent", () =>
				newEditor.setContent(settings.footer)
			);
		}

		newEditor.on("blur", () => {
			templateDesignerContext.updateSetting(
				"footer",
				newEditor.getContent()
			);

			return false;
		});

		// editor.on("mousedown touchstart", () => {
		// 	bookmark = null;
		// });

		// editor.on("init", () => {
		// 	const rootNode = editor.getBody();

		// 	// Create the toolbar by refocussing the editor.
		// 	if (document.activeElement === rootNode) {
		// 		rootNode.blur();
		// 		this.editor.focus();
		// 	}
		// });
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
				window.mailTemplateDesigner.fonts.forEach((font) => {
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
	}, [templateDesignerContext.editingElement, settings]);

	return (
		<EditableElement elementTitle={elementTitle} elementName={elementName}>
			<div
				className="mail-footer"
				style={{
					backgroundColor: settings.footerbackground,
				}}
			>
				<div
					key="toolbar"
					id="footer-editor-toolbar"
					//ref={(ref) => (this.ref = ref)}
					className="block-library-classic__toolbar"
					//onClick={this.focus}
					data-placeholder={__("Classic")}
					onKeyDown={(e) => {
						// Prevent WritingFlow from kicking in and allow arrows navigation on the toolbar.
						elementName.stopPropagation();
						// Prevent Mousetrap from moving focus to the top toolbar when pressing `alt+f10` on this block toolbar.
						e.nativeEvent.stopImmediatePropagation();
					}}
				/>
				<div
					key="editor"
					id="footer-html"
					className="wp-block-freeform block-library-rich-text__tinymce"
					dangerouslySetInnerHTML={{
						__html: settings.footer,
					}}
				/>
			</div>
		</EditableElement>
	);
}
