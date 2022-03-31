import React, { useCallback, useRef, useState } from "react";
import {
	ColorPicker
} from "@wordpress/components";

import useClickOutside from "./useClickOutside";

export const PopoverPicker = ({ color, onChange }) => {
  const popover = useRef();
  const [isOpen, toggle] = useState(false);

  const close = useCallback(() => toggle(false), []);
  useClickOutside(popover, close);

  return (
    <div className="color-picker-popoper">
      <div
        className="swatch"
        style={{ backgroundColor: color }}
        onClick={() => toggle(true)}
      />

      {isOpen && (
        <div className="popover" ref={popover}>
          <ColorPicker color={color} onChangeComplete={onChange} disableAlpha/>
        </div>
      )}
    </div>
  );
};
