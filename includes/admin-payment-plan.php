<div class="panel panel_template s_panel"> <!-- first panel -->
	<h3 class="acc-header">!!plan_name!! <span class="pmpropp_small_text">(<?php esc_html_e( 'Click to view', 'pmpro-payment-plans' ); ?>)</span><span class="pmpropp_remove_plan"><?php esc_html_e( 'delete', 'pmpro-payment-plans' ); ?></span></h3>
	<div class="acc-body">		
		<table class='form-table'>
			<tr>
				<th><label for='pmpropp_plan_name'><?php esc_html_e( 'Payment Plan Name', 'pmpro-payment-plans' ); ?>:</label></th>
				<td><input type='text' id='pmpropp_plan_name' name='pmpropp_plan_name[]' value='!!plan_name!!' /></td>
			</tr>
			<tr>
				<th><label for='pmpropp_display_order'><?php esc_html_e( 'Display Order', 'pmpro-payment-plans' ); ?>:</label></th>
				<td><input type='number' id='pmpropp_display_order' class='pmpropp_display_order' name='pmpropp_display_order[]' value='!!display_order!!' /></td>
			</tr>
			<tr>
				<th><label for='pmpropp_plan_default'><?php esc_html_e( 'Is Default Payment Plan', 'pmpro-payment-plans' ); ?>:</label></th>
				<td>
					<select id='pmpropp_plan_default' name='pmpropp_plan_default[]' selectval='!!plan_default!!'>
						<option value='no'><?php esc_html_e( 'No', 'pmpro-payment-plans' ); ?></option>	
						<option value='yes' ><?php esc_html_e( 'Yes', 'pmpro-payment-plans' ); ?></option>						
					</select>
				</td>
			</tr>
			<tr>
				<th><label for='pmpropp_plan_status'><?php esc_html_e( 'Payment Plan Status', 'pmpro-payment-plans' ); ?>:</label></th>
				<td>
					<select id='pmpropp_plan_status' name='pmpropp_plan_status[]' selectval='!!plan_status!!'>
						<option value='active' ><?php esc_html_e( 'Active', 'pmpro-payment-plans' ); ?></option>
						<option value='inactive'><?php esc_html_e( 'Inactive', 'pmpro-payment-plans' ); ?></option>	
					</select>
				</td>
			</tr>
			<tr>
				<th><label for='pmpropp_initial_amount'><?php esc_html_e( 'Initial Amount', 'pmpro-payment-plans' ); ?>:</label></th>
				<td><input type='text' id='pmpropp_initial_amount' name='pmpropp_initial_amount[]' value='!!initial_amount!!'/></td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_html_e( 'Recurring Subscription', 'pmpro-payment-plans' ); ?>:</label>
				</th>
				<td>
					<input id="pmpropp_recurring" name="pmpropp_recurring[]" type="checkbox" value="yes"/> 
					<label for="pmpropp_recurring"><?php esc_html_e( 'Check if this level has a recurring subscription payment.', 'pmpro-payment-plans' ); ?></label>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="display:none;">
				<th scope="row" valign="top">
					<label for="pmpropp_billing_amount"><?php esc_html_e( 'Billing Amount', 'pmpro-payment-plans' ); ?>:</label>
				</th>
				<td>
					<?php
					if ( pmpro_getCurrencyPosition() == 'left' ) {
						echo $pmpro_currency_symbol;
					}
					?>
					<input name="pmpropp_billing_amount[]" type="text" value="!!billing_amount!!"  class="" />
					<?php
					if ( pmpro_getCurrencyPosition() == 'right' ) {
						echo $pmpro_currency_symbol;
					}
					?>
					<?php esc_html_e( 'per', 'pmpro-payment-plans' ); ?>
					<input id="cycle_number" name="pmpropp_cycle_number[]" type="text" value="!!cycle_number!!" class="small-text" />
					<select id="cycle_period" name="pmpropp_cycle_period[]" selectval="!!cycle_period!!">
					  <?php
						$cycles = array(
							esc_html__( 'Day(s)', 'pmpro-payment-plans' )   => 'Day',
							esc_html__( 'Week(s)', 'pmpro-payment-plans' )  => 'Week',
							esc_html__( 'Month(s)', 'pmpro-payment-plans' ) => 'Month',
							esc_html__( 'Year(s)', 'pmpro-payment-plans' )  => 'Year',
						);
						foreach ( $cycles as $name => $value ) {
							echo "<option value='$value'>$name</option>";
						}
						?>
					</select>
					<p class="description">
						<?php esc_html_e( 'The amount to be billed one cycle after the initial payment.', 'pmpro-payment-plans' ); ?>
						<?php if ( $gateway == 'braintree' ) { ?>
							<strong 
							<?php
							if ( ! empty( $pmpro_braintree_error ) ) {
								?>
								class="pmpro_red"<?php } ?>><?php esc_html_e( 'Braintree integration currently only supports billing periods of "Month" or "Year".', 'pmpro-payment-plans' ); ?></strong>
						<?php } elseif ( $gateway == 'stripe' ) { ?>
							<p class="description"><strong 
							<?php
							if ( ! empty( $pmpro_stripe_error ) ) {
								?>
								class="pmpro_red"<?php } ?>><?php esc_html_e( 'Stripe integration does not allow billing periods longer than 1 year.', 'pmpro-payment-plans' ); ?></strong></p>
						<?php } ?>
					</p>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="display:none;">
				<th scope="row" valign="top">
					<label for="pmpropp_billing_limit"><?php esc_html_e( 'Billing Cycle Limit', 'pmpro-payment-plans' ); ?>:</label>
				</th>
				<td>
					<input name="pmpropp_billing_limit[]" type="text" value="!!billing_limit!!" class="small-text" />
					<p class="description">
						<?php esc_html_e( 'The <strong>total</strong> number of recurring billing cycles for this level, including the trial period (if applicable) but not including the initial payment. Set to zero if membership is indefinite.', 'pmpro-payment-plans' ); ?>
						<?php if ( ( $gateway == 'stripe' ) && ! function_exists( 'pmprosbl_plugin_row_meta' ) ) { ?>
							<br /><strong 
							<?php
							if ( ! empty( $pmpro_stripe_error ) ) {
								?>
								class="pmpro_red"<?php } ?>><?php esc_html_e( 'Stripe integration currently does not support billing limits. You can still set an expiration date below.', 'pmpro-payment-plans' ); ?></strong>
							
						<?php } ?>
					</p>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="display:none;">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Custom Trial', 'pmpro-payment-plans' ); ?>:</label></th>
				<td>
					<input id="pmpropp_custom_trial" name="pmpropp_custom_trial" type="checkbox" value="yes" onclick="jQuery('.pmpropp_trial_info').toggle();"/> <label for="pmpropp_custom_trial"><?php _e( 'Check to add a custom trial period.', 'pmpro-payment-plans' ); ?></label>
					<?php if ( $gateway == 'twocheckout' ) { ?>
						<p class="description"><strong 
						<?php
						if ( ! empty( $pmpro_twocheckout_error ) ) {
							?>
							class="pmpro_red"<?php } ?>><?php esc_html_e( '2Checkout integration does not support custom trials. You can do one period trials by setting an initial payment different from the billing amount.', 'pmpro-payment-plans' ); ?></strong></p>
					<?php } ?>
				</td>
			</tr>
			<tr class="pmpropp_trial_info" style="display:none;">
				<th scope="row" valign="top"><label for="pmpropp_trial_amount"><?php esc_html_e( 'Trial Billing Amount', 'pmpro-payment-plans' ); ?>:</label></th>
				<td>
					<?php
					if ( pmpro_getCurrencyPosition() == 'left' ) {
						echo $pmpro_currency_symbol;
					}
					?>
					<input name="pmpropp_trial_amount[]" type="text" value="!!trial_amount!!" class="" />
					<?php
					if ( pmpro_getCurrencyPosition() == 'right' ) {
						echo $pmpro_currency_symbol;
					}
					?>
					<?php esc_html_e( 'for the first', 'pmpro-payment-plans' ); ?>
					<input name="pmpropp_trial_limit[]" type="text" value="!!trial_limit!!" class="small-text" />
					<?php esc_html_e( 'subscription payments', 'pmpro-payment-plans' ); ?>.
					<?php if ( $gateway == 'stripe' ) { ?>
						<p class="description"><strong 
						<?php
						if ( ! empty( $pmpro_stripe_error ) ) {
							?>
							class="pmpro_red"<?php } ?>><?php _e( 'Stripe integration currently does not support trial amounts greater than $0.', 'pmpro-payment-plans' ); ?></strong></p>
					<?php } elseif ( $gateway == 'braintree' ) { ?>
						<p class="description"><strong 
						<?php
						if ( ! empty( $pmpro_braintree_error ) ) {
							?>
							class="pmpro_red"<?php } ?>><?php _e( 'Braintree integration currently does not support trial amounts greater than $0.', 'pmpro-payment-plans' ); ?></strong></p>
					<?php } elseif ( $gateway == 'payflowpro' ) { ?>
						<p class="description"><strong 
						<?php
						if ( ! empty( $pmpro_payflow_error ) ) {
							?>
							class="pmpro_red"<?php } ?>><?php esc_html_e( 'Payflow integration currently does not support trial amounts greater than $0.', 'pmpro-payment-plans' ); ?></strong></p>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label><?php esc_html_e( 'Membership Expiration', 'pmpro-payment-plans' ); ?>:</label></th>
				<td><input id="pmpropp_plan_expiration" name="pmpropp_plan_expiration[]" type="checkbox" value="yes"> <label for="pmpropp_plan_expiration"><?php esc_html_e( 'Check this to set when membership access expires.', 'pmpro-payment-plans' ); ?></label></td>
			</tr>
			<tr class="pmpropp_plan_expiration_info" style="display:none;">					
				<th scope="row" valign="top"><label for="pmpropp_expiration_number"><?php esc_html_e( 'Expires In:', 'pmpro-payment-plans' ); ?></label></th>
				<td>
					<input id="pmpropp_expiration_number" name="pmpropp_expiration_number[]" type="text" value="!!expiration_number!!" class="small-text">
					<select id="expiration_period" name="pmpropp_expiration_period[]" selectval="!!expiration_period!!">
						  <option value="Day"><?php esc_html_e( 'Day(s)', 'pmpro-payment-plans' ); ?></option>
						  <option value="Week"><?php esc_html_e( 'Week(s)', 'pmpro-payment-plans' ); ?></option>
						  <option value="Month"><?php esc_html_e( 'Month(s)', 'pmpro-payment-plans' ); ?></option>
						  <option value="Year"><?php esc_html_e( 'Year(s)', 'pmpro-payment-plans' ); ?></option>
					  </select>
					<p class="description"><?php esc_html_e( 'Set the duration of membership access. Note that the any future payments (recurring subscription, if any) will be cancelled when the membership expires.', 'pmpro-payment-plans' ); ?></p>
					
					<div id="pmpropp_plan_expiration_warning" style="display: none;" class="notice error inline">
						<p><?php echo sprintf( __( 'WARNING: This level is set with both a recurring billing amount and an expiration date. You only need to set one of these unless you really want this membership to expire after a certain number of payments. For more information, %s.', 'pmpro-payment-plans') , '<a target="_blank" href="https://www.paidmembershipspro.com/important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=blog&amp;utm_content=important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels">' . __( 'see our post here', 'pmpro-payment-plans' ) . '</a>'); ?></p>
					</div>					
				</td>
			</tr>
		</table>
	</div>
</div>