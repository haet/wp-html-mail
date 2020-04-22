import { useState, useEffect, useContext } from "@wordpress/element";

import { IsolatedEventContainer } from "@wordpress/components";

import { TemplateDesignerContext } from "../contexts/TemplateDesignerContext.jsx";

export default function EditableElement({
	children,
	elementTitle,
	elementName,
}) {
	const [isHover, setIsHover] = useState(false);
	const templateDesignerContext = useContext(TemplateDesignerContext);

	return (
		<div
			className="editable-element"
			onClick={(e) => {
				e.stopPropagation();
				templateDesignerContext.setEditingElement(elementName);
			}}
			onMouseEnter={(e) => {
				e.stopPropagation();
				setIsHover(true);
			}}
			onMouseLeave={(e) => {
				e.stopPropagation();
				setIsHover(false);
			}}
		>
			{children}
			<div
				className={
					templateDesignerContext.editingElement === elementName
						? "edit-frame-active"
						: isHover
						? "edit-frame-hover"
						: "edit-frame-default"
				}
			></div>
		</div>
	);
}
