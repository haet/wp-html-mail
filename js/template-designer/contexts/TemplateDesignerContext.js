import { useState, createContext, useEffect } from "@wordpress/element";

export const TemplateDesignerContext = createContext();

function TemplateDesignerContextProvider(props) {
	const [panelTitle, setPanelTitle] = useState("");
	const [panelOptions, setPanelOptions] = useState(null);
	const [editingElement, setEditingElement] = useState("container");
	const [settings, setSettings] = useState({});
	// const [errorMessage, setErrorMessage] = useState("");
	// const [infoMessage, setInfoMessage] = useState("");
	// const [confirmDialog, setConfirmDialog] = useState({});
	const [isLoading, setIsLoading] = useState(true);

	const updateSetting = (key, value) => {
		setSettings({ ...settings, [key]: value });
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
				settings,
				setSettings,
				updateSetting,
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
