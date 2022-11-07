import { useState, useEffect, useContext } from "@wordpress/element";

import { Icon } from "@wordpress/components";
import { edit } from "@wordpress/icons";

import { TemplateDesignerContext } from "../../contexts/TemplateDesignerContext";

export default function EditableElement({
	children,
	elementTitle,
	elementName,
	frameSize = "normal",
	handleAlign = "left",
}) {
	const templateDesignerContext = useContext(TemplateDesignerContext);

	return (
		<div
			className="editable-element clearfix"
			onClick={(e) => {
				e.stopPropagation();
				templateDesignerContext.setEditingElement(elementName);
			}}
		>
			<div
				className={
					(templateDesignerContext.editingElement === elementName
						? "edit-frame-active"
						: "edit-frame-default") +
					" frame-size-" +
					frameSize +
					" handle-align-" +
					handleAlign
				}
			>
				<div
					className="edit-frame-handle"
					onClick={(e) => {
						e.stopPropagation();
						templateDesignerContext.setEditingElement(elementName);
					}}
				>
					{elementTitle}
					<Icon icon="edit" />
				</div>
			</div>
			{children}
		</div>
	);
}
