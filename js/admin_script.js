var haet_mail = haet_mail || {};

haet_mail.previewMail = function(template_code){
	var $=jQuery;
	if( !$("#mailtemplatepreview").length )
		return;

	$("#mailtemplatepreview").contents().find("html").html(template_code); 
	
	var iframe = document.getElementById('mailtemplatepreview');
	iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
	iframe.document.open();
	iframe.document.write(template_code);
	iframe.document.close();
}

haet_mail.ajaxSave = function(){
	var $ = jQuery;
	$.post(
		$('#haet_mail_form').attr('action'), 
		$('#haet_mail_form').serialize(),
		function(data){
			haet_mail.previewMail( $('#haet_mailtemplate',data).val() );
		}
	);
}

haet_mail.createCustomTemplate = function(){
	var $ = jQuery;
	$('input[name="haet_mail_create_template"]').val(1);
	haet_mail.ajaxSave();
	
	$( "#haet_mail_template_created" ).dialog({
		dialogClass: "no-close",
		modal: true,
		buttons: [
			{
				text: "OK",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
}



/*************************************
*   EDIT MODE means either setting 1 
*   template for alle WooCommerce 
*   mails or create each email 
*   individually
* ***********************************/
haet_mail.switch_edit_mode = function(){
	var $ = jQuery;
	if( $("input[name='haet_mail_plugins\[woocommerce\]\[edit_mode\]']:checked").val() == 'mailbuilder' ){
		$('.haet-mail-woocommerce-mailbuilder').slideDown(400);
		$('.haet-mail-woocommerce-global-template').not('.has-addon-emails').slideUp(400);
	}else{
		$('.haet-mail-woocommerce-global-template').slideDown(400);
		$('.haet-mail-woocommerce-mailbuilder').slideUp(400);
	}
}



jQuery(document).ready(function($) {
	$('input,textarea,select').change(function(){
		haet_mail.ajaxSave();
	});

	haet_mail.switch_edit_mode();
	$("input[name='haet_mail_plugins\[woocommerce\]\[edit_mode\]']").change( function(){
		haet_mail.switch_edit_mode();
	} );

	var self = this;
	$('input.color').wpColorPicker({
        change: function (event) {
        	window.setTimeout(function(){
        		haet_mail.ajaxSave();	
        	},50);
        	
        }
    });

	// Uploading files
	var file_frame;
	$('.upload_image_button').live('click', function( event ){
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			$('#haet_mailheaderimg').val(attachment.url);
			$('#haet_mailheaderimg_width').val(attachment.width);
			$('#haet_mailheaderimg_height').val(attachment.height);
			haet_mail.ajaxSave();
		});
		// Finally, open the modal
		file_frame.open();
	});

	$('#haet_mail_test_submit').click(function(){
		var email = $('#haet_mail_test_address').val();
		$.post(ajaxurl, { 'action':'haet_mail_send_test', 'email':email} , function(response) {
			$( "#haet_mail_test_sent" ).dialog({
				dialogClass: "no-close",
				modal: true,
				buttons: [
					{
						text: "OK",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
		});
	});

	$('#haet_mail_create_template_button').click(function(){
		if(!$(this).hasClass('button-disabled') && confirm( $(this).data('haet-confirm') ) ){
			haet_mail.createCustomTemplate();
		}
	});
	haet_mail.previewMail($("#haet_mailtemplate").val());



	$('a[data-haet-confirm]').click( function( e ){
		return confirm( $(this).data('haet-confirm') );
	} )


	/*************************************
	* SURVEY
	* ***********************************/
	$('.haet-star-rating a')
		.mouseenter(function(){
			var $current_star = $(this);
			$current_star.removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
			$current_star.prevAll('a').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
		})
		.mouseleave(function(){
			var current_rating = $(this).siblings('input').val();
			if( current_rating == 0 )
				$(this).parent().find('a').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
			else{
				var $current_star = $(this).siblings('a[data-rating="' + current_rating + '"]');
				$current_star.removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
				$current_star.prevAll('a').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
				$current_star.nextAll('a').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
			}
		})
		.click(function(e){
			if( $(this).parents('.update-nag').length )
				return true;
			e.preventDefault();
			var $current_star = $(this);
			$(this).siblings('input').val( $current_star.data('rating') );
			$current_star.removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
			$current_star.prevAll('a').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
			$current_star.nextAll('a').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
			$current_star.blur();
		})
}); 


