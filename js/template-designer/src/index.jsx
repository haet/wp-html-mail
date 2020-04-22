import React from "react";
import ReactDOM from "react-dom";
import MailTemplate from "../components/MailTemplate.jsx";

import TemplateDesignerContextProvider from "../contexts/TemplateDesignerContext.jsx";

document.addEventListener("DOMContentLoaded", function () {
	ReactDOM.render(
		<TemplateDesignerContextProvider>
			<MailTemplate />
		</TemplateDesignerContextProvider>,
		document.getElementById("wp-html-mail-template-designer")
	);
});
