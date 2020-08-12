var haet_mailbuilder = haet_mailbuilder || {};

haet_mailbuilder.serialize_content = function () {
	var $ = jQuery;
	var content_array = {};

	$("#mailbuilder-content .mb-contentelement").each(function () {
		var $contentelement = $(this);
		var element_content_array = $contentelement
			.find("input,select,textarea")
			.serializeArray();

		var indexed_element_content_array = {};

		for (var i = 0; i < element_content_array.length; i++) {
			indexed_element_content_array[element_content_array[i]["name"]] =
				element_content_array[i]["value"];
		}

		content_array[$contentelement.attr("id")] = {
			id: $contentelement.attr("id"),
			type: $contentelement.data("type"),
			settings: JSON.parse(
				$contentelement.find(".mb-element-settings").val()
			),
			content: indexed_element_content_array,
		};
	});
	$("#mailbuilder_json").val(JSON.stringify(content_array));
};

haet_mailbuilder.add_content_element = function (
	type,
	element_id,
	content_array,
	settings,
	animate
) {
	var $ = jQuery;
	var $contentelement = $("#mailbuilder-templates .mb-contentelement-" + type)
		.clone()
		.attr("id", element_id)
		.appendTo("#mailbuilder-content");

	if (animate) {
		var offset = $contentelement.offset();
		$("html, body").animate(
			{
				scrollTop: offset.top - 120,
			},
			500
		);
	}

	if (haet_mailbuilder["create_content_" + type])
		haet_mailbuilder["create_content_" + type](
			$contentelement,
			element_id,
			content_array
		);

	if (!settings || !settings.styles) {
		//console.log($contentelement.find('.mb-element-settings').val());
		settings = JSON.parse(
			$contentelement.find(".mb-element-settings").val()
		);
	}

	if (haet_mailbuilder["apply_element_settings_" + type])
		haet_mailbuilder["apply_element_settings_" + type](
			$contentelement,
			false,
			settings
		);
	else
		haet_mailbuilder.apply_element_settings(
			$contentelement,
			false,
			settings
		);
};

haet_mailbuilder.read_serialized_content = function () {
	var $ = jQuery;
	var raw_content = $("#mailbuilder_json").val();
	//console.log(raw_content);
	var content_array = [];

	if (raw_content.length) content_array = JSON.parse(raw_content);

	for (var i in content_array) {
		haet_mailbuilder.add_content_element(
			content_array[i]["type"],
			content_array[i]["id"],
			content_array[i]["content"],
			content_array[i]["settings"]
		);
	}
};

haet_mailbuilder.get_attachment_preview = function (attachment) {
	return (
		'<div class="mb-attachment-preview">\
                <img src="' +
		attachment.icon +
		'" class="icon">\
                <div class="mb-attachment-details">\
                    <a href="' +
		attachment.url +
		'" target="_blank" class="filename">' +
		attachment.filename +
		'</a><br>\
                    <span class="filesize">' +
		attachment.filesizeHumanReadable +
		'</span>\
                    <a class="remove-attachment" data-id="' +
		attachment.id +
		'"><span class="dashicons dashicons-no"></span></a>\
                </div>\
            </div>'
	);
};

haet_mailbuilder.get_attachment_ids = function () {
	var $ = jQuery;
	var raw_attachments = $("#mailbuilder_attachments").val();

	var attachment_ids = [];

	if (raw_attachments.length) attachment_ids = JSON.parse(raw_attachments);

	for (var i = 0; i < attachment_ids.length; i++) {
		if (attachment_ids[i] == "") {
			attachment_ids.splice(i, 1);
			i--;
		}
	}
	return attachment_ids;
};

haet_mailbuilder.disable_singular_elements = function () {
	var $ = jQuery;
	$('.mb-add-types a[data-once="once"]').each(function () {
		var type = $(this).data("type");
		if ($("#mailbuilder-content .mb-contentelement-" + type).length)
			$(this).addClass("disabled");
		else $(this).removeClass("disabled");
	});
};

haet_mailbuilder.padding_settings_init = function () {
	var $ = jQuery;
	$(".padding-settings").each(function () {
		var $padding_settings = $(this);
		$padding_settings.css(
			"border-top-width",
			parseInt($padding_settings.find(".padding-top").val()) / 3
		);
		$padding_settings.css(
			"border-right-width",
			parseInt($padding_settings.find(".padding-right").val()) / 3
		);
		$padding_settings.css(
			"border-bottom-width",
			parseInt($padding_settings.find(".padding-bottom").val()) / 3
		);
		$padding_settings.css(
			"border-left-width",
			parseInt($padding_settings.find(".padding-left").val()) / 3
		);

		$padding_settings.find("select").change(function () {
			var $padding_settings = $(this).parents(".padding-settings");
			$padding_settings.css(
				"border-top-width",
				parseInt($padding_settings.find(".padding-top").val()) / 3
			);
			$padding_settings.css(
				"border-right-width",
				parseInt($padding_settings.find(".padding-right").val()) / 3
			);
			$padding_settings.css(
				"border-bottom-width",
				parseInt($padding_settings.find(".padding-bottom").val()) / 3
			);
			$padding_settings.css(
				"border-left-width",
				parseInt($padding_settings.find(".padding-left").val()) / 3
			);
		});
	});
};

// this can be overridden by each element type using haet_mailbuilder.settings_dialog_<type>
haet_mailbuilder.settings_dialog = function (
	$contentelement,
	$settings_dialog,
	settings
) {
	var $ = jQuery;
	if (settings && settings.styles && settings.styles.desktop) {
		$settings_dialog
			.find(".mb-element-padding-desktop select")
			.each(function () {
				$(this).val(settings.styles.desktop[$(this).attr("class")]);
			});

		$settings_dialog
			.find(".background-color")
			.val(settings.styles.desktop["background-color"])
			.change();
	}
	if (settings && settings.styles && settings.styles.mobile) {
		$settings_dialog
			.find(".mb-element-padding-mobile select")
			.each(function () {
				$(this).val(settings.styles.mobile[$(this).attr("class")]);
			});
	}
	haet_mailbuilder.padding_settings_init();
};

// this can be overridden by each element type using haet_mailbuilder.apply_element_settings_<type>
haet_mailbuilder.apply_element_settings = function (
	$contentelement,
	$settings_dialog,
	old_settings
) {
	var $ = jQuery;
	var settings = old_settings;

	// refresh settings if dialog is set
	if ($settings_dialog) {
		$settings_dialog
			.find(".mb-element-padding-desktop select")
			.each(function () {
				settings.styles.desktop[$(this).attr("class")] = $(this).val();
			});

		$settings_dialog
			.find(".mb-element-padding-mobile select")
			.each(function () {
				settings.styles.mobile[$(this).attr("class")] = $(this).val();
			});

		settings.styles.desktop["background-color"] = $settings_dialog
			.find(".background-color")
			.val();
	}

	$contentelement.find(".mb-element-settings").val(JSON.stringify(settings));
	haet_mailbuilder.serialize_content();

	$.each(settings.styles.desktop, function (selector, value) {
		$contentelement.css(selector, value);
	});
};

haet_mailbuilder.close_wysiwyg_sidebar = function (
	$settings,
	$sidebar,
	$textarea,
	apply_changes
) {
	var $ = jQuery;
	$settings.removeClass("active");
	$sidebar.find(".mb-add-wrap").addClass("active");
	$sidebar.removeClass("sidebar-wide");
	$settings.find(".mb-apply,.mb-cancel").off("click");
	if (apply_changes) {
		mb_text.apply_content(
			$textarea,
			tinymce.editors["mb_wysiwyg_editor"].getContent()
		);
		haet_mailbuilder.serialize_content();
	}
};

jQuery(document).ready(function ($) {
	haet_mailbuilder.read_serialized_content();

	$("#mailbuilder-content").sortable({
		stop: function (event, ui) {
			haet_mailbuilder.serialize_content();
		},
	});

	//the "add"-Menu
	haet_mailbuilder.disable_singular_elements();

	// add content element
	$(".mb-add-types a").on("click", function (e) {
		e.preventDefault();
		if (!$(this).hasClass("disabled")) {
			var type = $(this).data("type");
			var element_id = "mb-" + Date.now();
			haet_mailbuilder.add_content_element(
				type,
				element_id,
				{},
				{},
				true
			);
			haet_mailbuilder.disable_singular_elements();
		}
	});

	// edit content element
	$("#mailbuilder-content").on("click", ".mb-edit-element", function (e) {
		e.preventDefault();
		var $contentelement = $(this).parents(".mb-contentelement");
		var settings = JSON.parse(
			$contentelement.find(".mb-element-settings").val()
		);
		var type = $contentelement.data("type");

		var $sidebar = $(".mailbuilder-settings-sidebar");
		var $settings = $sidebar.find(".mb-element-settings-" + type);
		$sidebar.addClass("sidebar-wide");

		$sidebar
			.find(".mailbuilder-sidebar-element.active")
			.removeClass("active");

		$settings.addClass("active");

		if (haet_mailbuilder["settings_dialog_" + type])
			haet_mailbuilder["settings_dialog_" + type](
				$contentelement,
				$settings,
				settings
			);
		else
			haet_mailbuilder.settings_dialog(
				$contentelement,
				$settings,
				settings
			);

		$settings.find(".mb-apply").one("click", function () {
			$settings.removeClass("active");
			$sidebar.find(".mb-add-wrap").addClass("active");
			$sidebar.removeClass("sidebar-wide");

			if (haet_mailbuilder["apply_element_settings_" + type])
				haet_mailbuilder["apply_element_settings_" + type](
					$contentelement,
					$settings,
					settings
				);
			else
				haet_mailbuilder.apply_element_settings(
					$contentelement,
					$settings,
					settings
				);

			haet_mailbuilder.serialize_content();
		});

		$settings.find(".mb-cancel").one("click", function () {
			$settings.removeClass("active");
			$sidebar.find(".mb-add-wrap").addClass("active");
			$sidebar.removeClass("sidebar-wide");
		});
	});

	// remove content element
	$("#mailbuilder-content").on("click", ".mb-remove-element", function (e) {
		e.preventDefault();
		$(this)
			.parents(".mb-contentelement")
			.slideUp(500, function () {
				$(this).remove();
				haet_mailbuilder.serialize_content();
				haet_mailbuilder.disable_singular_elements();
			});
	});

	// make content editable
	$("#mailbuilder-content").on(
		"click",
		".mb-contentelement-content",
		function (e) {
			var $element_content = $(this);

			// WYSIWYG Editor
			$element_content.find(".mb-edit-wysiwyg").each(function () {
				var $settings = $("#mb-wysiwyg-edit");
				var $sidebar = $(".mailbuilder-settings-sidebar");

				// if somone didnt apply the changes to the previous element we do this here before we edit a new one
				if ($settings.hasClass("active")) {
					var $previous_textarea = $(
						"#mailbuilder-content .mb-contentelement-content.editing .mb-edit-wysiwyg textarea"
					);
					mb_text.apply_content(
						$previous_textarea,
						tinymce.editors["mb_wysiwyg_editor"].getContent()
					);
					haet_mailbuilder.serialize_content();
					$settings.find(".mb-apply,.mb-cancel").off("click");
					$(
						"#mailbuilder-content .mb-contentelement-content.editing"
					).removeClass("editing");
				}

				$element_content.addClass("editing");

				var $textarea = $(this).find("textarea");
				tinymce.editors["mb_wysiwyg_editor"].setContent(
					$textarea.val()
				);

				$sidebar.addClass("sidebar-wide");

				$sidebar
					.find(".mailbuilder-sidebar-element.active")
					.removeClass("active");

				$settings.addClass("active");

				$settings.find(".mb-apply").on("click", function () {
					haet_mailbuilder.close_wysiwyg_sidebar(
						$settings,
						$sidebar,
						$textarea,
						true
					);
					$element_content.removeClass("editing");
				});

				$settings.find(".mb-cancel").on("click", function () {
					haet_mailbuilder.close_wysiwyg_sidebar(
						$settings,
						$sidebar,
						$textarea,
						false
					);
					$element_content.removeClass("editing");
				});
			});
		}
	);

	// Uploading files
	var attachments_file_frame;
	$(".upload_attachment_button").on("click", function (event) {
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if (attachments_file_frame) {
			attachments_file_frame.open();
			return;
		}
		// Create the media frame.
		attachments_file_frame = wp.media.frames.attachments_file_frame = wp.media(
			{
				title: jQuery(this).data("uploader_title"),
				button: {
					text: jQuery(this).data("uploader_button_text"),
				},
				multiple: true, // Set to true to allow multiple files to be selected
			}
		);
		// When an image is selected, run a callback.
		attachments_file_frame.on("select", function () {
			var attachment_ids = haet_mailbuilder.get_attachment_ids();

			$.each(
				attachments_file_frame.state().get("selection").models,
				function (idx, attachment) {
					//console.log( attachment.attributes );
					if (
						attachment_ids.indexOf(
							attachment.attributes.id.toString()
						) == -1
					) {
						attachment_ids.push(
							attachment.attributes.id.toString()
						);
						$(".mb-preview-attachments").append(
							haet_mailbuilder.get_attachment_preview(
								attachment.attributes
							)
						);

						$("#mailbuilder_attachments").val(
							JSON.stringify(attachment_ids)
						);
					}
				}
			);
		});
		// Finally, open the modal
		attachments_file_frame.open();
	});

	$(".mb-preview-attachments").on("click", ".remove-attachment", function (
		e
	) {
		e.preventDefault();
		var $button = $(this);
		var attachment_id = $button.data("id");
		$button.parents(".mb-attachment-preview").hide(400);

		var attachment_ids = haet_mailbuilder.get_attachment_ids();
		attachment_index = attachment_ids.indexOf(attachment_id.toString());
		if (attachment_index > -1) attachment_ids.splice(attachment_index, 1);
		$("#mailbuilder_attachments").val(JSON.stringify(attachment_ids));
	});

	// preview saved attachments
	var attachment_ids = haet_mailbuilder.get_attachment_ids();
	if (attachment_ids.length) {
		$.each(attachment_ids, function (idx, id) {
			wp.media
				.attachment(id)
				.fetch()
				.then(function (data) {
					// preloading finished
					// after this you can use your attachment normally
					//wp.media.attachment(id).get('url');
					$(".mb-preview-attachments").append(
						haet_mailbuilder.get_attachment_preview(
							wp.media.attachment(id).attributes
						)
					);
					// console.log(wp.media.attachment(id));
				});
		});
	}

	// custom CSS
	$(".mailbuilder-custom-css-button").click(function (e) {
		e.preventDefault();

		var $settings = $("#mb-css-edit");

		var $sidebar = $(".mailbuilder-settings-sidebar");
		$sidebar.addClass("sidebar-wide");

		$sidebar
			.find(".mailbuilder-sidebar-element.active")
			.removeClass("active");

		$settings.addClass("active");

		$settings.find(".mb-apply").one("click", function () {
			$settings.removeClass("active");
			$sidebar.find(".mb-add-wrap").addClass("active");
			$sidebar.removeClass("sidebar-wide");
		});
	});

	var mailbuilder_css_editor_desktop = wp.codeEditor.initialize(
		$(".mailbuilder-css-desktop"),
		{
			codemirror: {
				mode: "css",
				lineNumbers: true,
			},
		}
	);

	var mailbuilder_css_editor_mobile = wp.codeEditor.initialize(
		$(".mailbuilder-css-mobile"),
		{
			codemirror: {
				mode: "css",
				lineNumbers: true,
			},
		}
	);

	// Mailbuilder General Layout
	$(".post-type-wphtmlmail_mail #post-body")
		.removeClass("columns-2")
		.addClass("columns-1");

	// if header metabox is invisible, click the toggle
	if ($("#header .inside:visible").length == 0)
		$("#header .handlediv").click();

	// hide header or footer
	$(
		"#mailbuilder-header  .mailbuilder-hide-button,#mailbuilder-footer .mailbuilder-hide-button"
	).click(function (e) {
		e.preventDefault();
		var $button = $(this);
		var $element = $button.parent();
		$("#" + $button.data("status-field")).val(
			$element.hasClass("mailbuilder-hidden") ? 0 : 1
		);
		$element.toggleClass("mailbuilder-hidden");
	});
});
