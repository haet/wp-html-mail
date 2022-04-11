import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import MailTemplate from "../components/MailTemplate";

import TemplateDesignerContextProvider from "../contexts/TemplateDesignerContext";

domReady( function() {
	render(
		<TemplateDesignerContextProvider>
			<MailTemplate />
		</TemplateDesignerContextProvider>,
		document.getElementById("wp-html-mail-template-designer")
	);
});