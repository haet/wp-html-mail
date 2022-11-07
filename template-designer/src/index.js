import React from "react";
import ReactDOM from "react-dom";
import TemplateDesigner from "../components/TemplateDesigner";

import TemplateDesignerContextProvider from "../contexts/TemplateDesignerContext";

document.addEventListener("DOMContentLoaded", function () {
	ReactDOM.render(
		<TemplateDesignerContextProvider>
			<TemplateDesigner />
		</TemplateDesignerContextProvider>,
		document.getElementById("wp-html-mail-template-designer")
	);
});
