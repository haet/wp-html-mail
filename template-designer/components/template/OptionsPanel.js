import { useContext } from "@wordpress/element";
import { Panel } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

export default function OptionsPanel() {
	const templateDesignerContext = useContext(TemplateDesignerContext);

	if (
		templateDesignerContext.theme === {} ||
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
