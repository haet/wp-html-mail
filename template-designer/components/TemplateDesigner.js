import { useState, useEffect, useContext, lazy, Suspense } from "@wordpress/element";

import {
	TabPanel,
	Spinner,
	Snackbar,
	Icon,
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
import RemoteWebfontsComponentErrorBoundary from "./settings/RemoteWebfontsComponentErrorBoundary"
import RemoteWoocommerceComponentErrorBoundary from "./settings/RemoteWoocommerceComponentErrorBoundary"

import { PopoverPicker } from "./options/PopoverPicker";


const RemoteWoocommerceSettings = lazy(() => import("wphtmlmailwoocommerce/WoocommerceSettings"));
const RemoteWebfontsSettings = lazy(() => import("wphtmlmailwebfonts/WebfontsSettings"));

export default function TemplateDesigner() {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const [plugins, setPlugins] = useState([]);
	const [isOpen, setIsOpen] = useState(true);

	const handleCancel = () => {
		setIsOpen(false);
		templateDesignerContext.setConfirmDialog({})
	};

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
		templateDesignerContext.loadAvailableFonts();
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
			} else if (plugin.name === 'woocommerce' && plugin.active && plugin.is_addon_active) {
				const woocommerceTab = {
					name: 'woocommerce',
					title: 'WooCommerce',
					className: 'tab-woocommerce',
					component: null,
				};
				if (plugin.is_addon_lite) {
					woocommerceTab.lazyComponent = 'WoocommerceSettings';
				} else {
					// Create a new URLSearchParams object from the URL
					const searchParams = new URLSearchParams(window.location.search);
					searchParams.set('tab', 'woocommerce');
					const updatedURL = new URL(window.location.href);
					updatedURL.search = searchParams.toString();
					woocommerceTab.redirectUrl = updatedURL.href;
				}
				tabs.push(woocommerceTab);
			}
		})
	}
	// tabs.push({
	// 	name: 'content',
	// 	title: __('Content editor', 'wp-html-mail'),
	// 	className: 'tab-content',
	// 	component: <ContentEditor />
	// });
	tabs.push({
		name: 'webfonts',
		title: __('Webfonts', 'wp-html-mail'),
		className: 'tab-webfonts',
		component: null,
		lazyComponent: 'WebfontsSettings'
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
				{templateDesignerContext.errorMessage && <Snackbar className="snackbar error" status="error" isDismissible={false}>
					<Icon icon="warning" style={{ color: '#f78da7' }} />{templateDesignerContext.errorMessage}
				</Snackbar >}
				{templateDesignerContext.infoMessage && <Snackbar className="snackbar" status="success" isDismissible={false}>
					<Icon icon="yes-alt" style={{ color: 'green' }} />{templateDesignerContext.infoMessage}
				</Snackbar >}
				<ConfirmDialog
					isOpen={templateDesignerContext.confirmDialog && templateDesignerContext.confirmDialog.hasOwnProperty('message')}
					onCancel={handleCancel}
					onConfirm={() => {
						if (templateDesignerContext.confirmDialog.callback) {
							const callback = templateDesignerContext.confirmDialog.callback;
							setIsOpen(false);
							callback();
						}
					}}
				>
					{templateDesignerContext.confirmDialog.message}
				</ConfirmDialog>
			</div>
			<TabPanel
				className="wp-html-mail-tabs"
				tabs={tabs}
			>
				{(tab) => {
					if (tab.component)
						return tab.component;
					if (tab.lazyComponent && tab.lazyComponent === 'WoocommerceSettings') {
						return (<Suspense fallback={<Spinner />}>
							<RemoteWoocommerceComponentErrorBoundary>
								<RemoteWoocommerceSettings
									templateDesignerContext={templateDesignerContext}
									PopoverPicker={PopoverPicker}
								/>
							</RemoteWoocommerceComponentErrorBoundary>
						</Suspense>);
					}
					if (tab.lazyComponent && tab.lazyComponent === 'WebfontsSettings') {
						return (
							<Suspense fallback={<Spinner />}>
								<RemoteWebfontsComponentErrorBoundary>
									<RemoteWebfontsSettings
										templateDesignerContext={templateDesignerContext}
									/>
								</RemoteWebfontsComponentErrorBoundary>
							</Suspense>
						);
					}
					if (tab.redirectUrl) {
						window.location.replace(tab.redirectUrl);
					}
				}}
			</TabPanel>
		</>
	);
}
