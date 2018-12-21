var haet_mailbuilder = haet_mailbuilder || {};
var mb_twocol = mb_twocol || {};

/*************************************
*   GLOBAL FUNCTIONS haet_mailbuilder
* ***********************************/
haet_mailbuilder.create_content_twocol = function( $contentelement, element_id, content_array ){
    var $ = jQuery;
    for( var name in content_array ){
        mb_twocol.apply_content( $contentelement.find('[name="' + name + '"]'), content_array[name] );
    }
}


/*************************************
*   CONTENT TYPE INTERNAL FUNCTIONS
* ***********************************/
mb_twocol.apply_content = function( $element, content ){
    var $ = jQuery;
    
    var preview_content = content.replace(/\[([a-z\_]*)\]/gi, function fill_placeholder(placeholder){
        placeholder = placeholder.replace('[','').replace(']','');
        //console.log( 'placeholder: '+ placeholder+'->' + haet_mb_data.placeholders.productstable[( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' )][row_index][placeholder.toLowerCase()]);
        return haet_mb_data.placeholders.text[placeholder.toLowerCase()];
    });
    $element
        .val( content )
        .siblings( '.mb-content-preview' ).html( preview_content );

}




jQuery(document).ready(function($) {

});
