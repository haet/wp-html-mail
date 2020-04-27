import React from "react";
import ReactDOM from "react-dom";
import MailTemplate from "../components/MailTemplate";

import TemplateDesignerContextProvider from "../contexts/TemplateDesignerContext";

document.addEventListener("DOMContentLoaded", function () {
	ReactDOM.render(
		<TemplateDesignerContextProvider>
			<MailTemplate />
		</TemplateDesignerContextProvider>,
		document.getElementById("wp-html-mail-template-designer")
	);
});
