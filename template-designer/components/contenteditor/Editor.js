import { useState, useEffect, useContext } from "@wordpress/element";

import {
	Spinner,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import IsolatedBlockEditor, { EditorLoaded } from '@automattic/isolated-block-editor';

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";
import MailHeader from "../template/MailHeader";
import MailFooter from "../template/MailFooter";


export default function Editor({css}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);
	const { theme } = templateDesignerContext;
	const [isSaving, setIsSaving] = useState(false);
	const [showSaveSuccess, setShowSaveSuccess] = useState(false);
	

	useEffect(() => {
		templateDesignerContext.loadTheme();
	}, []);


	const renderEditorStyles = () => {
		return (
			<style>
				{css}
			</style>
		);
	}

	if (templateDesignerContext.isLoading || !templateDesignerContext.theme)
		return (
			<div className="mail-loader">
				<Spinner />
			</div>
		);
	else
		return (
			<div className="mail-content-editor">
				<IsolatedBlockEditor
					settings={ mailTemplateDesigner.editorSettings }
					onSaveContent={ ( content ) => console.log( content ) }
					// onLoad={ ( parser ) => ( textarea && textarea.nodeName === 'TEXTAREA' ? parser( textarea.value ) : [] ) }
					onError={() => document.location.reload()}
					renderMoreMenu={()=><button>my more button</button>}
				>
					{renderEditorStyles()}
				</IsolatedBlockEditor>
			</div>
		);
}


// return (
// 			<div className="mail-content-editor">
// 				<div className="mail-designer">
// 					<div
// 						className="mail-container"
// 						style={{
// 							backgroundColor: theme.background,
// 						}}
// 					>
// 						<div className="mail-content-wrap">
// 							<MailHeader />
// 							<IsolatedBlockEditor
// 								settings={ mailTemplateDesigner.editorSettings }
// 								onSaveContent={ ( content ) => console.log( content ) }
// 								// onLoad={ ( parser ) => ( textarea && textarea.nodeName === 'TEXTAREA' ? parser( textarea.value ) : [] ) }
// 								onError={() => document.location.reload()}
// 								//renderMoreMenu={()=><button>my more button</button>}
// 							>
// 							</IsolatedBlockEditor>
// 							<MailFooter />
// 						</div>
// 					</div>
// 				</div>
// 			</div>
// 		);