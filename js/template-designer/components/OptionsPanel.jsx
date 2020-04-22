import { useState, useEffect, useContext } from "@wordpress/element";
import { Panel, PanelBody, PanelRow } from "@wordpress/components";
import { more } from "@wordpress/icons";
import { Card, CardBody } from "@wordpress/components";
import { BaseControl } from "@wordpress/components";
import { ColorPicker } from "@wordpress/components";
import { FontSizePicker } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext.jsx";

export default function OptionsPanel() {
	const templateDesignerContext = useContext(TemplateDesignerContext);

	if (
		templateDesignerContext.settings === {} ||
		!templateDesignerContext.editingElement
	)
		return null;
	else
		return (
			<Panel
				header={templateDesignerContext.panelTitle}
				className="mail-optionspanel"
			>
				{templateDesignerContext.panelOptions}
				{/* <PanelBody
					title="My Block Settings"
					icon={more}
					initialOpen={true}
				>
					<PanelRow>My Panel Inputs and Labels</PanelRow>
					<PanelRow>
						<BaseControl
							id="textarea-1"
							label="Text"
							help="Enter some text"
						>
							<textarea id="textarea-1" />
						</BaseControl>
					</PanelRow>
					<PanelRow>
						<FontSizePicker
							fontSizes={[
								{
									name: __("Small"),
									slug: "small",
									size: 12,
								},
								{
									name: __("Big"),
									slug: "big",
									size: 26,
								},
							]}
							value={
								templateDesignerContext.settings.headerfontsize
							}
							fallbackFontSize={40}
							onChange={(newFontSize) => {
								const newSettings =
									templateDesignerContext.settings;
								newSettings.headerfontsize = newFontSize;
								templateDesignerContext.setSettings(
									newSettings
								);
							}}
						/>
					</PanelRow>
					<PanelRow>
						<BaseControl
							id="color-1"
							label="Color"
							help="Enter some text"
						>
							<ColorPicker
								color={color}
								onChangeComplete={(value) =>
									setColor(value.hex)
								}
								disableAlpha
							/>
						</BaseControl>
					</PanelRow>
				</PanelBody> */}
			</Panel>
		);
}
