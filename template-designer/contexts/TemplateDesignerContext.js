import { useState, createContext, useEffect } from "@wordpress/element";

export const TemplateDesignerContext = createContext();

function TemplateDesignerContextProvider(props) {
	const [panelTitle, setPanelTitle] = useState("");
	const [panelOptions, setPanelOptions] = useState(null);
	const [editingElement, setEditingElement] = useState("container");
	const [theme, setTheme] = useState({});
	const [settings, setSettings] = useState({});
	// const [errorMessage, setErrorMessage] = useState("");
	// const [infoMessage, setInfoMessage] = useState("");
	// const [confirmDialog, setConfirmDialog] = useState({});
	const [isLoading, setIsLoading] = useState(true);
	const [isSaving, setIsSaving] = useState(false);
	
	const updateTheme = (key, value) => {
		setTheme({ ...theme, [key]: value });
	};

	const updateSetting = (key, value) => {
		setSettings({ ...settings, [key]: value });
	};

	const loadTheme = (successCallback) => {
		if (Object.entries(theme).length === 0) {
			var request = new Request(
				window.mailTemplateDesigner.restUrl + "themesettings",
				{
					method: "GET",
					headers: {
						"Content-Type": "application/json",
						"X-WP-Nonce": window.mailTemplateDesigner.nonce
					},
					credentials: "same-origin"
				}
			);
			fetch(request)
				.then((resp) => resp.json())
				.then((data) => {
					if( successCallback )
						successCallback();
					setTheme(data);
					setIsLoading(false);
				});
		}
	};


	const saveTheme = (successCallback) => {
		setIsSaving(true);
		var request = new Request(
			window.mailTemplateDesigner.restUrl + "themesettings",
			{
				method: "POST",
				body: JSON.stringify(theme),
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.mailTemplateDesigner.nonce
				},
			}
		);
		fetch(request)
			.then((resp) => resp.json())
			.then((data) => {
				setIsSaving(false);
				if( successCallback )
					successCallback();
				if (data.preview) {
					haet_mail.previewMail(data.preview);
				}
			});
	};


	const loadSettings = () => {
		if ( Object.entries(settings).length === 0 ) {
			var request = new Request(
				window.mailTemplateDesigner.restUrl + "settings",
				{
					method: "GET",
					headers: {
						"Content-Type": "application/json",
						"X-WP-Nonce": window.mailTemplateDesigner.nonce
					},
					credentials: "same-origin"
				}
			);
			fetch(request)
				.then((resp) => resp.json())
				.then((data) => {
					setSettings(data);
					setIsLoading(false);
				});
		}
	};

	const saveSettings = () => {
		setIsSaving(true);
		var request = new Request(
			window.mailTemplateDesigner.restUrl + "settings",
			{
				method: "POST",
				body: JSON.stringify(settings),
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": window.mailTemplateDesigner.nonce
				},
			}
		);
		fetch(request)
			.then((resp) => resp.json())
			.then((data) => {
				setIsSaving(false);
				setShowSaveSuccess(true);
				setTimeout(() => {
					setShowSaveSuccess(false);
				}, 4000);
				setSettings(data);
			});
	};

	return (
		<TemplateDesignerContext.Provider
			value={{
				panelTitle,
				setPanelTitle,
				panelOptions,
				setPanelOptions,
				editingElement,
				setEditingElement,
				theme,
				setTheme,
				updateTheme,
				loadTheme,
				saveTheme,
				settings,
				setSettings,
				updateSetting,
				loadSettings,
				saveSettings,
				// errorMessage,
				// setErrorMessage,
				// infoMessage,
				// setInfoMessage,
				// confirmDialog,
				// setConfirmDialog,
				isLoading,
				setIsLoading,
			}}
		>
			{props.children}
		</TemplateDesignerContext.Provider>
	);
}

export default TemplateDesignerContextProvider;
