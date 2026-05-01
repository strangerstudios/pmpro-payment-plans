jQuery(document).ready(function ($) {

	// Bail if no payment plans are present
	if ( payment_plans == undefined || payment_plans.plans.length === 0 ) {
		return;
	}

	// Are there any payment plans in the URL?
	const urlParams = new URLSearchParams( window.location.search );
	const payment_plan_query = urlParams.get( 'pmpropp_chosen_plan' );

	// Get the default payment plan
	const default_plan = payment_plans.plans.find( plan => plan.default === "yes" && plan.id !== payment_plans.parent_level );

	// Append the payment plans to the DOM
	payment_plans.plans.forEach( element => {
		$( "#pmpropp_payment_plans" ).append( element.html );
	});


	// If there is a payment plan in the URL, select it and ignore the default
	if ( payment_plan_query) {
		// Check if value is a number
		if ( /^\d+$/.test( payment_plan_query ) ) {
			appendPlanAndPriceByNumberId( payment_plan_query );
		} else {
			appendPlanAndPriceByPlanId( payment_plan_query );
		}
	// Apply the default plan if there is no plan in the URL
	} else if ( default_plan ) {
		appendPlanAndPrice( default_plan );
	// No default plan, no plan in the URL, just select the plan from the local storage (last selected plan) if it
	//exists, otherwise do nothing and parent level regular payment plan will be selected
	} else {
		const chosen_plan = localStorage.getItem( 'pmpropp_chosen_plan' );
		if ( chosen_plan !== "" ) {
			appendPlanAndPriceByPlanId( chosen_plan );
		}
	}

	$( ".pmpropp_chosen_plan" ).on( "click", function( ) {
		const planId = $(this).val();
		appendPlanAndPriceByPlanId( planId );
	});

});

/**
 * Append the plan and price by the numberId (2)
 *
 * @param {number} numberId Just the int. The plan id is constructed as 'L-' + payment_plans.parent_level + '-P-' + numberId
 * @since 0.5
 * @return {void}
 */
const appendPlanAndPriceByNumberId = ( numberId ) => {
	const planId = 'L-' + payment_plans.parent_level + '-P-' + numberId;
	appendPlanAndPriceByPlanId( planId );
};

/**
 * Append the plan and price by the planId (L-2-P-0)
 *
 * @param {string} planId The plan id is constructed as 'L-' + payment_plans.parent_level + '-P-' + planId
 * @since 0.5
 * @return {void}
 */
const appendPlanAndPriceByPlanId = ( planId ) => {
	const plan = payment_plans.plans.find( plan => plan.id === planId );
	appendPlanAndPrice( plan );
};


/**
 * Append the plan and price by the plan object
 *
 * @param {Object} plan  The plan object
 * @since 0.5
 * @return {void}
 */
const appendPlanAndPrice = ( plan ) => {
	// Bail if no plan is present
    if ( ! plan ) {
		return;
	}
	//id="pmpropp_chosen_plan_choice_L-2-P-0"
	jQuery( '#pmpropp_chosen_plan_choice_' + plan.id ).prop( 'checked', true );

	const data = {
		action: 'pmpropp_request_price_change',
		pmpro_level: payment_plans.parent_level,
		plan: plan.id,
	};

	jQuery.post( payment_plans.ajaxurl, data, ( response ) => {
		if ( response !== '' ) {
			localStorage.setItem( 'pmpropp_chosen_plan', plan.id );
			jQuery( '#pmpro_level_cost' ).html( response );
		}
	});
};