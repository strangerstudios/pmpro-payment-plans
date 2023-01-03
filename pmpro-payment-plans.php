<?php
/**
 * Plugin Name: Paid Memberships Pro - Payment Plans Add On
 * Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-payment-plans/
 * Description: Integrates with Paid Memberships Pro to provide payment plans for membership levels.
 * Version: 0.1.1
 * Author: Paid Memberships Pro
 * Author URI: https://www.paidmembershipspro.com
 */

/**
 * Includes the cleanup script on uninstall.
 */
include plugin_dir_path( __FILE__ ) . 'includes/uninstall.php';
function pmpropp_activate(){
    register_uninstall_hook( __FILE__, 'pmpropp_uninstall' );
}
register_activation_hook( __FILE__, 'pmpropp_activate' );

/**
 * Load required scripts for admin settings.
 *
 * @since 0.1
 */
function pmpropp_load_admin_scripts() {

	if ( ! empty( $_REQUEST['page'] ) && $_REQUEST['page'] == 'pmpro-membershiplevels' && ! empty( $_REQUEST['edit'] ) ) {

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script( 'pmpro-payment-plans-admin', plugins_url( 'js/admin.js', __FILE__ ) );

		global $pmpro_currency_symbol;

		$gateway = pmpro_getOption( 'gateway' );

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'includes/admin-payment-plan.php';
		$output = ob_get_contents();
		ob_end_clean();

		$stored_plans = pmpropp_render_plans( $output, true );
		$plan_data = pmpropp_return_payment_plans( intval( $_REQUEST['edit'] ), true );

		$template_plan       = new StdClass();
		$template_plan->name = __( 'New Payment Plan', 'pmpro-payment-plans' );

		$output = pmpropp_replace_template_values( $output, $template_plan );

		wp_localize_script(
			'pmpro-payment-plans-admin',
			'pmpropp_plans',
			array(
				'stored_plans' => $stored_plans,
				'template'     => $output,
				'plan_data'    => $plan_data,
			)
		);

		wp_enqueue_style( 'pmpro-payment-plans-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
	}

}
add_action( 'admin_enqueue_scripts', 'pmpropp_load_admin_scripts' );

/**
 * Load required frontend scripts
 *
 * @since 0.1
 */
function pmpropp_load_frontend_scripts() {

	global $pmpro_pages, $post;

	if ( ! empty( $pmpro_pages['checkout'] ) && ! empty( $post->ID ) ) {
		if ( $pmpro_pages['checkout'] == $post->ID ) {

			wp_enqueue_script( 'pmpro-payment-plans-frontend-js', plugins_url( 'js/frontend.js', __FILE__ ) );

			wp_localize_script(
				'pmpro-payment-plans-frontend-js',
				'payment_plans',
				array(
					'plans'        => pmpropp_return_payment_plans( intval( $_REQUEST['level'] ) ),
					'ajaxurl'      => admin_url( 'admin-ajax.php' ),
					'parent_level' => ( ! empty( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 ),
				)
			);

			wp_enqueue_style( 'pmpro-payment-plans-frontend-css', plugins_url( 'css/frontend.css', __FILE__ ) );

		}
	}
}
add_action( 'wp_enqueue_scripts', 'pmpropp_load_frontend_scripts' );

/**
 * Load text domain for localizations.
 *
 * @since 0.1
 */
function pmpropp_load_text_domains() {

	load_plugin_textdomain( 'pmpro-payment-plans', false, basename( dirname( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'pmpropp_load_text_domains' );

/**
 * Add options to level settings to add payment plans.
 *
 * @since 0.1
 */
function pmpropp_membership_level_after_other_settings() {

	?>
	<h3 class="topborder"><?php esc_html_e( 'Payment Plans', 'pmpro-payment-plans' ); ?></h3>
	<?php if( $_REQUEST['edit'] !== "-1" ) { ?>	   
	   	<p><?php esc_html_e( 'Create multiple payment plans for this level, giving your members multiple options to pay for a membership', 'pmpro-payment-plans' ); ?></p>	
		<div class='pmpro_payment_plan_level_container'>
			<div class='pmpro_payment_plan_level_inner'>
				<div id="accordion"></div> 
			</div>
			<div>
				<button class='button button-primary' id='pmpropp_add_payment_plan'><?php esc_html_e( 'Add Payment Plan', 'pmpro-payment-plans' ); ?></button>
			</div>
		</div>
	<?php } else { ?>
		<p><?php esc_html_e( 'Please save your level first before creating a payment plan', 'pmpro-payment-plans' ); ?></p>
	<?php }	
}
add_action( 'pmpro_membership_level_after_billing_details_settings', 'pmpropp_membership_level_after_other_settings', 1 );

/**
 * Save the payment plan settings when the level is saved.
 *
 * @since 0.1
 */
function pmpropp_membership_level_save() {

	if ( isset( $_REQUEST['saveid'] ) ) { // TODO: Check page param as well as saveid may be used elsewhere.

		$payment_plans = pmpropp_pair_plan_fields( $_REQUEST );

		update_pmpro_membership_level_meta( $_REQUEST['saveid'], 'payment_plan', $payment_plans );

	}

}
add_action( 'admin_init', 'pmpropp_membership_level_save' );

/**
 * Helper function to convert the settings into Membership Level Objects.
 *
 * @param array $request The $_REQUEST parameters passed through to handle it.
 * @return array $payment_plans An array containing level objects for each payment plan.
 * @since 0.1
 */
function pmpropp_pair_plan_fields( $request ) {

	$payment_plans = array();

	if ( ! empty( $request['pmpropp_plan_name'] ) ) {

		$pmpropp_plan_name         = $request['pmpropp_plan_name'];
		$pmpropp_initial_amount    = $request['pmpropp_initial_amount'];
		$pmpropp_billing_amount    = $request['pmpropp_billing_amount'];
		$pmpropp_cycle_number      = $request['pmpropp_cycle_number'];
		$pmpropp_cycle_period      = $request['pmpropp_cycle_period'];
		$pmpropp_billing_limit     = $request['pmpropp_billing_limit'];
		$pmpropp_trial_amount      = $request['pmpropp_trial_amount'];
		$pmpropp_trial_limit       = $request['pmpropp_trial_limit'];
		$pmpropp_expiration_number = $request['pmpropp_expiration_number'];
		$pmpropp_expiration_period = $request['pmpropp_expiration_period'];
		$pmpropp_display_order     = $request['pmpropp_display_order'];
		$pmpropp_plan_status       = $request['pmpropp_plan_status'];
		$pmpropp_plan_default      = $request['pmpropp_plan_default'];

		// Checkboxes hidden values to see if these options are selected or not.
		$pmpropp_plan_expiration   = $request['pmpropp_plan_expiration'];
		$pmpropp_custom_trial = $request['pmpropp_custom_trial'];
		$pmpropp_recurring = $request['pmpropp_recurring']; // Remove the last value as it's a stray value.
		
		$size = count( $pmpropp_plan_name );

		for ( $i = 0; $i < $size; $i++ ) {

			// Clear out all recurring information if checkbox isn't selected or billing amount is empty.
			if ( empty( $pmpropp_recurring[$i] ) ) {
				$pmpropp_billing_amount[$i] = '';
				$pmpropp_cycle_number[$i] = '';
				$pmpropp_cycle_period[$i] = '';
				$pmpropp_billing_limit[$i] = '';
				$request['pmpropp_custom_trial'][$i] = ''; //Make sure we clear this out if recurring option is deselected.
			}

			// Clear out if trial checkbox isn't selected or trial amount is blanked out.
			if ( empty( $pmpropp_custom_trial[$i] ) ) {
				$pmpropp_trial_amount[$i] = '';
				$pmpropp_trial_limit[$i] = '';
			}

			// // Clear out the expiration data if checkbox isn't selected or the expiration number is blank/empty.
			if ( empty( $pmpropp_plan_expiration[$i] ) ) {
				$pmpropp_expiration_number[$i] = '';
				$pmpropp_expiration_period[$i] = '';
			}			

			$level                    = new stdClass();
			$level->id                = 'L-' . intval( $request['saveid'] ) . '-P-' . $i;
			$level->name              = sanitize_text_field( $pmpropp_plan_name[ $i ] );
			$level->description       = sanitize_text_field( $request['description'] );
			$level->confirmation      = sanitize_text_field( $request['confirmation'] );
			$level->billing_amount    = floatval( $pmpropp_billing_amount[ $i ] );
			$level->trial_amount      = floatval( $pmpropp_trial_amount[ $i ] );
			$level->initial_payment   = floatval( $pmpropp_initial_amount[ $i ] );
			$level->billing_limit     = intval( $pmpropp_billing_limit[ $i ] );
			$level->trial_limit       = intval( $pmpropp_trial_limit[ $i ] );
			$level->expiration_number = intval( $pmpropp_expiration_number[ $i ] );
			$level->expiration_period = $pmpropp_expiration_period[ $i ];
			$level->cycle_period      = $pmpropp_cycle_period[ $i ];
			$level->cycle_number      = intval( $pmpropp_cycle_number[ $i ] );
			$level->display_order     = intval( $pmpropp_display_order[ $i ] );
			$level->type              = 'payment_plan';
			$level->status            = $pmpropp_plan_status[ $i ];
			$level->default           = $pmpropp_plan_default[ $i ];

			$payment_plans[] = $level;

		}
	}

	return $payment_plans;

}

/**
 * Get a single plan by id.
 * @since 0.2
 * @param int    $level_id The membership level ID.
 * @param string $plan_id  The plan ID.
 * 
 * @return object|false $plan The plan if found. False if not.
 */
function pmpropp_get_plan( $level_id, $plan_id ) {
    $plans = pmpropp_return_payment_plans( $level_id );
    
    if ( ! empty( $plans ) ) {
        foreach ( $plans as $plan ) {
            if ( $plan->id === $plan_id ) {
                return $plan;
            }
        }
    }
    
    return false;
}

/**
 * Return payment plan array or single object if plan_id is specified
 *
 * @since 0.1
 * @param int $level_id The membership level ID.
 *
 * @return array $plan An array of the plans.
 */
function pmpropp_return_payment_plans( $level_id, $is_admin = false ) {
	global $pmpro_pages;

	if ( empty( $level_id ) ) {
        return array();
    }    

	global $pmpro_currency_symbol;

	$currency_position = pmpro_getCurrencyPosition();

	$payment_plans = get_pmpro_membership_level_meta( $level_id, 'payment_plan', true );

    if ( empty( $payment_plans ) ) {
        return array();
    }

	$ordered_plans = array();

	/**
	 * Include the default level billing price at checkout as a radio checkbox.
	 * 
	 * @param bool $include_level_price Should the levels default pricing be automatically included at checkout.
	 * @param int $level_id The level ID value of current checkout/level in question.
	 */
	$level_pricing_at_checkout = apply_filters( 'pmpropp_include_level_pricing_option_at_checkout', true, $level_id );
    
    if ( $level_pricing_at_checkout && ( pmpro_is_checkout() || wp_doing_ajax() ) && is_array( $payment_plans ) ) {
		$level = pmpro_getLevel( $level_id );
		$level->status = 'active';
		$level->default = 'yes'; //Default to yes, as it can be adjusted by "real" plans later on.
		$level->display_order = 0;
		array_unshift( $payment_plans, $level );
	}
			
	foreach ( $payment_plans as $plan ) {				
		/**
		 * Show plans that are active, or if we're on the level edit page show all
		 */
		if ( $plan->status === 'active' || $is_admin ) {
		
			$ordered_plans[] = $plan;

			$plan->html = sprintf(
				'<input type="radio" name="pmpropp_chosen_plan" class="%5$s" value="%1$s" id="%2$s" %3$s /> <label for="%2$s" class="pmpro_label-inline">%4$s</label>',
				esc_attr( $plan->id ),
				esc_attr( 'pmpropp_chosen_plan_choice_' . $plan->id ),
				checked( 'yes', $plan->default, false ),
				esc_html( $plan->name ) . ' - ' . trim( pmpro_no_quotes( pmpro_getLevelCost( $plan, true, true ) . ' ' . pmpro_getLevelExpiration( $plan ) ) ),
				pmpro_get_element_class( 'pmpropp_chosen_plan pmpro_alter_price', 'pmpropp_chosen_plan_choice_-' . $plan->id )
			);

			/**
			 * Allow filtering the plan HTML input.
			 *
			 * @since 0.1
			 *
			 * @param string $html     The plan HTML input.
			 * @param object $plan     The plan object.
			 * @param int    $level_id The level ID.
			 */
			$plan->html = apply_filters( 'pmpropp_plan_html_template', $plan->html, $plan, $level_id );
		}
	}

	//Lets order by the display order value
	array_multisort(
		array_column($ordered_plans, 'display_order'), 
		SORT_ASC, 
		$ordered_plans
	);

	return $ordered_plans;
}

/**
 * Perform registration checks to make sure a valid plan is being selected.
 *
 * @since TBD
 */
function pmpropp_registration_checks( $okay ) {

	global $pmpro_msg, $pmpro_msgt;

	if( empty( $_REQUEST['pmpropp_chosen_plan'] ) ) {
		return $okay;
	}

	$plan = pmpropp_get_plan( intval( $_REQUEST['level'] ), sanitize_text_field( $_REQUEST['pmpropp_chosen_plan'] ) );

	if( !empty( $plan ) ) {
		$okay = true;
	} else {
		$pmpro_msg = __( 'Please select a valid payment plan.', 'pmpro-payment-plans' );
		$pmpro_msgt = "pmpro_error";
		$okay = false;
	}

	return $okay;

}
add_filter( 'pmpro_registration_checks', 'pmpropp_registration_checks', 10, 1);


/**
 * Display payment plans section on checkout page.
 *
 * @since 0.1
 */
function pmpropp_render_payment_plans_checkout() {

	if ( ! empty( $_REQUEST['level'] ) ) {

		$plans = pmpropp_return_payment_plans( intval( $_REQUEST['level'] ) );

		if ( ! empty( $plans ) ) {
			?>
			<div id="pmpropp_select_payment_plan" class="pmpro_checkout">
				<hr />
				<h3>
					<span class="pmpro_checkout-h3-name"><?php _e( 'Select a Payment Plan', 'pmpro-payment-plans' ); ?></span>
				</h3>
				<div id="pmpropp_payment_plans" class="pmpro_checkout-fields">
					<!-- JavaScript populates plan options here -->
				</div> <!-- end pmpro_checkout-fields -->
			</div>
			<?php
		}
	}
}
add_action( 'pmpro_checkout_boxes', 'pmpropp_render_payment_plans_checkout', 10 );

/**
 * Override levels object with a payment plan - if chosen by the customer at checkout.
 *
 * @since 0.1
 */
function pmpropp_override_checkout_level( $level ) {

	if ( ! empty( $_REQUEST['pmpropp_chosen_plan'] ) ) {

		$plan = pmpropp_get_plan( intval( $level->id ), sanitize_text_field( $_REQUEST['pmpropp_chosen_plan'] ) );

		if( empty( $plan ) ) {
			return $level;
		}

		// If the plan ID is exactly same as the level ID just bail and return the current level object.
		if ( $plan->id === $level->id ) {
			return $level;
		}

		$level->name = $level->name . ' - ' . $plan->name;

		$level->type         = 'payment_plan';
		$level->payment_plan = $_REQUEST['pmpropp_chosen_plan'];

		$level->description       = $plan->description;
		$level->confirmation      = $plan->confirmation;
		$level->initial_payment   = $plan->initial_payment;
		$level->billing_amount    = $plan->billing_amount;
		$level->cycle_number      = $plan->cycle_number;
		$level->cycle_period      = $plan->cycle_period;
		$level->billing_limit     = $plan->billing_limit;
		$level->trial_amount      = $plan->trial_amount;
		$level->trial_limit       = $plan->trial_limit;
		$level->expiration_number = $plan->expiration_number;
		$level->expiration_period = $plan->expiration_period;

	}

	return $level;

}
add_filter( 'pmpro_checkout_level', 'pmpropp_override_checkout_level' );

/**
 * After checkout - Add note and meta of plan
 *
 * @since 0.1
 */
function pmpropp_after_checkout( $user_id, $morder ) {

	if ( ! empty( $_REQUEST['pmpropp_chosen_plan'] ) ) {

		$plan = pmpropp_get_plan( $morder->membership_id, sanitize_text_field( $_REQUEST['pmpropp_chosen_plan'] ) );

		if( !empty( $plan ) ) {
			update_pmpro_membership_order_meta( intval( $morder->id ), 'payment_plan', $plan );
		}

	}

}
add_action( 'pmpro_after_checkout', 'pmpropp_after_checkout', 10, 2 );

/**
 * Add payment plan column header to orders page.
 *
 * @since 0.1
 */
function pmpropp_payment_plan_header() {

	echo '<th>' . esc_html__( 'Payment Plan', 'pmpro-payment-plans' ) . '</th>';

}
add_action( 'pmpro_orders_extra_cols_header', 'pmpropp_payment_plan_header', 10 );

/**
 * Add payment plan column to the order page.
 *
 * @since 0.1
 */
function pmpropp_payment_plan_body( $morder ) {

	$plan = get_pmpro_membership_order_meta( $morder->id, 'payment_plan', true );

	if ( ! empty( $plan->name ) ) {
		echo '<td>' . $plan->name . '</td>';
	} else {
		echo '<td>' . __( '&#8212;', 'paid-memberships-pro' ) . '</td>';
	}

}
add_action( 'pmpro_orders_extra_cols_body', 'pmpropp_payment_plan_body', 10, 1 );

/**
 * Ajax request to handle price change on checkout.
 *
 * @since 0.1
 */
function pmpropp_request_price_change() {

	if ( ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'pmpropp_request_price_change' ) {

		$plan = pmpropp_get_plan( intval( $_REQUEST['level'] ), sanitize_text_field( $_REQUEST['plan'] ) );

		if( !empty( $plan ) ) {
			echo trim( pmpro_no_quotes( pmpro_getLevelCost( $plan, array( '"', "'", "\n", "\r" ) ) . ' '. pmpro_getLevelExpiration( $plan ) ) );
		}

		wp_die();
	}

}
add_action( 'wp_ajax_pmpropp_request_price_change', 'pmpropp_request_price_change' );
add_action( 'wp_ajax_nopriv_pmpropp_request_price_change', 'pmpropp_request_price_change' );

/**
 * Render admin plans in HTML
 *
 * @since 0.1
 */
function pmpropp_render_plans( $template, $is_admin = false ) {

	$ret = '';

	global $pmpro_currency_symbol;

	$plans = pmpropp_return_payment_plans( intval( $_REQUEST['edit'] ), $is_admin );

	if ( ! empty( $plans ) ) {
		foreach ( $plans as $plan ) {
			$temp = '';

			$temp = pmpropp_replace_template_values( $template, $plan );

			$ret .= $temp;

		}
	}

	return $ret;
}

/**
 * Helper function to str_replace variables with plan values.
 *
 * @param string $template The template content to replace.
 * @param string $values The dynamic values to replace the template variables with.
 * @return string The formatted data for the template values. String replaced for the dynamic content.
 * 
 * @since 0.1
 */
function pmpropp_replace_template_values( $template, $values ) {

	$template = str_replace( '!!plan_id!!', ( ! empty( $values->id ) ) ? $values->id : '', $template );

	$template = str_replace( '!!plan_name!!', ( ! empty( $values->name ) ) ? $values->name : '', $template );
	$template = str_replace( '!!display_order!!', ( ! empty( $values->display_order ) ) ? $values->display_order : '', $template );
	$template = str_replace( '!!billing_amount!!', ( ! empty( $values->billing_amount ) ) ? $values->billing_amount : '', $template );
	$template = str_replace( '!!plan_status!!', ( ! empty( $values->status ) ) ? $values->status : '', $template );
	$template = str_replace( '!!initial_amount!!', ( ! empty( $values->initial_payment ) ) ? $values->initial_payment : '', $template );
	$template = str_replace( '!!cycle_number!!', ( ! empty( $values->cycle_number ) ) ? $values->cycle_number : '', $template );
	$template = str_replace( '!!cycle_period!!', ( ! empty( $values->cycle_period ) ) ? $values->cycle_period : '', $template );
	$template = str_replace( '!!billing_limit!!', ( ! empty( $values->billing_limit ) ) ? $values->billing_limit : '', $template );
	$template = str_replace( '!!trial_amount!!', ( ! empty( $values->trial_amount ) ) ? $values->trial_amount : '', $template );
	$template = str_replace( '!!trial_limit!!', ( ! empty( $values->trial_limit ) ) ? $values->trial_limit : '', $template );
	$template = str_replace( '!!expiration_number!!', ( ! empty( $values->expiration_number ) ) ? $values->expiration_number : '', $template );
	$template = str_replace( '!!expiration_period!!', ( ! empty( $values->expiration_period ) ) ? $values->expiration_period : '', $template );
	$template = str_replace( '!!plan_default!!', ( ! empty( $values->default ) ) ? $values->default : '', $template );

	return $template;

}
