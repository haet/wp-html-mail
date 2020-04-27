import { useState, useEffect, useContext } from "@wordpress/element";
import { Panel, PanelBody, PanelRow } from "@wordpress/components";
import { more } from "@wordpress/icons";
import { Card, CardBody } from "@wordpress/components";
import { BaseControl } from "@wordpress/components";
import { ColorPicker } from "@wordpress/components";
import { FontSizePicker } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";

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
			</Panel>
		);
}
