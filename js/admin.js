jQuery(document).ready(function(){
    
    if( pmpropp_plans.stored_plans !== "" ){
        jQuery("#accordion").append( pmpropp_plans.stored_plans );
        initializeAccordion();
    }


    jQuery("body").on("click", "#pmpropp_add_payment_plan", function( e ){

        e.preventDefault();

        var plan = jQuery(".panel_template").html();

        jQuery('#accordion').accordion('destroy');

        jQuery("#accordion").append( pmpropp_plans.template ); 
        
        jQuery( function(){ initializeAccordion() });

        return false;

    });

    jQuery("body").on("click", ".s_panel", function(){

        var id = jQuery(this).attr('id');
        var menu_order = jQuery(this).attr('menu_order');
        console.log(id);
        jQuery('#'+id+' #pmpropp_display_order').val(menu_order);
        
        jQuery('#'+id+' #pmpropp_recurring').attr( 'menu_order', menu_order );
        jQuery('#'+id+' #pmpropp_plan_expiration').attr( 'menu_order', menu_order );

        jQuery('#'+id+' tr.expiration_info').addClass( 'pmpropp_expirations_'+menu_order );
        jQuery('#'+id+' tr.pmpropp_plan_recurring').addClass( 'pmpropp_recurring_'+menu_order );
        
    });

    jQuery("body").on("click", "#pmpropp_recurring", function(){

        var menu_order = jQuery(this).attr('menu_order');

        if( jQuery(this).is(':checked') ){
            jQuery(".pmpropp_recurring_"+menu_order).show();
        } else {
            jQuery(".pmpropp_recurring_"+menu_order).hide();
        }        
        
    });

    jQuery("body").on("click", "#pmpropp_plan_expiration", function(){

        var menu_order = jQuery(this).attr('menu_order');

        if( jQuery(this).is(':checked') ){
            jQuery(".pmpropp_expirations_"+menu_order).show();
        } else {
            jQuery(".pmpropp_expirations_"+menu_order).hide();
        }        
        
    });

});

function initializeAccordion(){
    
    jQuery('#accordion').accordion({
        collapsible: true,
        active: false,
        heightStyle: 'content',
        header: 'h3'
    }).sortable({
        items: '.s_panel',
        update: function( event, ui ) {        
            var counter = 0;
            jQuery('.s_panel').each( function( key, val ){
                jQuery( this ).attr( 'menu_order', counter )
                jQuery( this ).attr( 'id', 'pmpropp_plan_'+counter );
                counter++;
            });
        }
    });

    jQuery('#accordion').on('accordionactivate', function (event, ui) {
        if (ui.newPanel.length) {
            jQuery('#accordion').sortable('disable');
        } else {
            jQuery('#accordion').sortable('enable');
        }
    });

    var counter = 0;

    jQuery(".s_panel").each( function( key, val ){
        jQuery( this ).attr( 'menu_order', counter )
        jQuery( this ).attr( 'id', 'pmpropp_plan_'+counter );
        counter++;
    });

}