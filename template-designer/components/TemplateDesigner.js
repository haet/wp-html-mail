import { useState, useEffect, useContext, lazy, Suspense } from "@wordpress/element";

import {
	TabPanel,
	Notice,
	Spinner,
	__experimentalConfirmDialog as ConfirmDialog
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext";
import MailTemplate from "./template/MailTemplate";
import Sender from "./settings/Sender";
import Redirect from "./settings/Redirect";
import Plugins from "./settings/Plugins";
import ContentEditor from "./contenteditor/ContentEditor";
import AdvancedSettings from "./settings/AdvancedSettings";

import { PopoverPicker } from "./options/PopoverPicker";

const RemoteWoocommerceSettings = lazy(() => import("wphtmlmailwoocommerce/WoocommerceSettings"));

export default function TemplateDesigner() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [plugins, setPlugins] = useState([]);


	const loadPlugins = () => {
		templateDesignerContext.setIsLoading(true);
		const pluginsRequest = new Request(
			window.mailTemplateDesigner.restUrl + "plugins",
			{
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.mailTemplateDesigner.nonce
				},
				credentials: "same-origin"
			}
		);
		fetch(pluginsRequest)
			.then((resp) => resp.json())
			.then((data) => {
				setPlugins(data);
				templateDesignerContext.setIsLoading(false);
			});
	};

	useEffect(() => {
		// remove URL Paramaters to avoid advanced actions to be executed multiple times.
		const url = new URL(window.location);
		url.searchParams.delete('advanced-action');
		window.history.pushState({}, '', url);

		loadPlugins();
	}, []);
	const tabs = [
		{
			name: 'template',
			title: __('Template', 'wp-html-mail'),
			className: 'tab-template',
			component: <MailTemplate />
		},
		{
			name: 'sender',
			title: __('Sender', 'wp-html-mail'),
			className: 'tab-sender',
			component: <Sender />
		},
		{
			name: 'plugins',
			title: __('Plugin emails', 'wp-html-mail'),
			className: 'tab-plugins',
			component: <Plugins plugins={plugins} />
		}
	];
	if (plugins) {
		plugins.forEach(plugin => {
			if (plugin.react_component) {
				tabs.push({
					name: plugin.name,
					title: plugin.display_name,
					className: 'tab-' + plugin.name,
					component: null,
					lazyComponent: plugin.react_component.component
				});
			}
		})
	}
	tabs.push({
		name: 'content',
		title: __('Content editor', 'wp-html-mail'),
		className: 'tab-content',
		component: <ContentEditor />
	});
	tabs.push({
		name: 'redirect',
		title: __('Redirect emails', 'wp-html-mail'),
		className: 'tab-redirect',
		component: <Redirect />
	});
	tabs.push({
		name: 'advanced',
		title: __('Advanced', 'wp-html-mail'),
		className: 'tab-advanced',
		component: <AdvancedSettings />
	});
	return (
		<>
			<div>
				{ templateDesignerContext.errorMessage && <Notice status="error" isDismissible={false}>
					{templateDesignerContext.errorMessage}
				</Notice >}
				{ templateDesignerContext.infoMessage && <Notice status="success" isDismissible={false}>
					{templateDesignerContext.infoMessage}
				</Notice >}
				{templateDesignerContext.confirmDialog && templateDesignerContext.confirmDialog.message &&
					<ConfirmDialog
						onConfirm={() => {
							if (templateDesignerContext.confirmDialog.callback) {
								const callback = templateDesignerContext.confirmDialog.callback;
								callback();
							}
						}}
					>
            {templateDesignerContext.confirmDialog.message}
        </ConfirmDialog>
				}
			</div>
			<TabPanel
				className="wp-html-mail-tabs"
				tabs={tabs}
			>
				{(tab) => {
					if (tab.component)
						return tab.component;
					if (tab.lazyComponent) {
						return (<Suspense fallback={<Spinner/>}>
								<RemoteWoocommerceSettings
									templateDesignerContext={templateDesignerContext}
									PopoverPicker={PopoverPicker}
								/>
							</Suspense>);
					}
				}}
			</TabPanel>
		</>
	);
}
