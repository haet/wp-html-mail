import { useState, useEffect, useContext } from "@wordpress/element";

import {
	TabPanel,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import MailTemplate from "./template/MailTemplate";
import Sender from "./settings/Sender";
import Redirect from "./settings/Redirect";
import Plugins from "./settings/Plugins";
import ContentEditor from "./contenteditor/ContentEditor";


export default function TemplateDesigner() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	
	
	return (
		<TabPanel
			className="wp-html-mail-tabs"
			tabs={ [
				{
					name: 'template',
					title: __('Template','wp-html-mail'),
					className: 'tab-template',
					component: <MailTemplate />
				},
				{
					name: 'sender',
					title: __('Sender','wp-html-mail'),
					className: 'tab-sender',
					component: <Sender/>
				},
				{
					name: 'plugins',
					title: __('Plugin emails','wp-html-mail'),
					className: 'tab-plugins',
					component: <Plugins/>
				},
				{
					name: 'content',
					title: __('Content editor','wp-html-mail'),
					className: 'tab-content',
					component: <ContentEditor/>
				},
				{
					name: 'redirect',
					title: __('Redirect emails','wp-html-mail'),
					className: 'tab-redirect',
					component: <Redirect/>
				},
			] }
		>
			{ ( tab ) => tab.component }
		</TabPanel>
	);
}
