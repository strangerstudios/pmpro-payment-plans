jQuery(document).ready(function(){

	if( jQuery("#pmpropp_payment_plans").length > 0 ){

		jQuery.each( payment_plans.plans, function( key, val ){
			
			jQuery("#pmpropp_payment_plans").append("<div class='pmpro_checkout-field'>"+val.html+"</div>");

			//Make sure the level cost text applies to the selected level
			if( val.default == "yes" ) {

				var data = {
					action: 'pmpropp_request_price_change',
					level: payment_plans.parent_level,
					plan: val.id
				}

				jQuery.post( payment_plans.ajaxurl, data, function( response ){
					if( response !== '' ){
						jQuery("#pmpro_level_cost").html(response);
					}
				});	

			}
			
		});

		jQuery("body").on("click", ".pmpropp_chosen_plan", function(){

			var value = jQuery(this).val();
			
			jQuery.each( payment_plans.plans, function( key, val ){
				
				if( val.id == value ){

					var data = {
						action: 'pmpropp_request_price_change',
						level: payment_plans.parent_level,
						plan: value
					}

					jQuery.post( payment_plans.ajaxurl, data, function( response ){
						if( response !== '' ){
							jQuery("#pmpro_level_cost").html(response);
						}
					});			
				}
			});

		});

	}

});