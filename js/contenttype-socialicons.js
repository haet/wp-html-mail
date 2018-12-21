var haet_mailbuilder = haet_mailbuilder || {};
var mb_socialicons = mb_socialicons || {};

/*************************************
*   GLOBAL FUNCTIONS haet_mailbuilder
* ***********************************/
haet_mailbuilder.create_content_socialicons = function( $contentelement, element_id, content_array ){
    var $ = jQuery;
    for( var name in content_array ){
        mb_socialicons.apply_content( $contentelement.find('[name="' + name + '"]'), content_array[name] );
    }
}


/*************************************
*   CONTENT TYPE INTERNAL FUNCTIONS
* ***********************************/
mb_socialicons.apply_content = function( $element, content ){
    var $ = jQuery;
    
    var preview_content = content.replace(/\[([a-z0-9\_]*)\]/gi, function fill_placeholder(placeholder){
        placeholder = placeholder.replace('[','').replace(']','');

        var placeholder_value
        if( placeholder.toLowerCase() in haet_mb_data.placeholders.text )
            placeholder_value = haet_mb_data.placeholders.text[placeholder.toLowerCase()];
        else
            placeholder_value = '['+placeholder+']';
        return placeholder_value;
    });
    $element
        .val( content )
        .siblings( '.mb-content-preview' ).html( preview_content );

}




jQuery(document).ready(function($) {

});
