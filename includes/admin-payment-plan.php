<div class="panel panel_template s_panel"> <!-- first panel -->
	<h3 class="acc-header">!!plan_name!!</h3>
	<div class="acc-body">		
		<table class='form-table'>
			<tr>
				<th><label for='pmpropp_plan_name'><?php _e('Payment Plan Name', 'pmpro-payment-plans'); ?>:</label></th>
				<td><input type='text' id='pmpropp_plan_name' name='pmpropp_plan_name[]' value='!!plan_name!!' /></td>
			</tr>
			<tr>
				<th><label for='pmpropp_display_order'><?php _e('Display Order', 'pmpro-payment-plans'); ?>:</label></th>
				<td><input type='number' id='pmpropp_display_order' class='pmpropp_display_order' name='pmpropp_display_order[]' value='!!display_order!!' /></td>
			</tr>
			<tr>
				<th><label for='pmpropp_plan_default'><?php _e('Is Default Payment Plan', 'pmpro-payment-plans'); ?>:</label></th>
				<td>
					<select id='pmpropp_plan_default' name='pmpropp_plan_default[]' selectval='!!plan_default!!'>
						<option value='no'><?php _e('No', 'pmpro-payment-plans'); ?></option>	
						<option value='yes' ><?php _e('Yes', 'pmpro-payment-plans'); ?></option>						
					</select>
				</td>
			</tr>
			<tr>
				<th><label for='pmpropp_plan_status'><?php _e('Payment Plan Status', 'pmpro-payment-plans'); ?>:</label></th>
				<td>
					<select id='pmpropp_plan_status' name='pmpropp_plan_status[]' selectval='!!plan_status!!'>
						<option value='active' ><?php _e('Active', 'pmpro-payment-plans'); ?></option>
						<option value='inactive'><?php _e('Inactive', 'pmpro-payment-plans'); ?></option>	
					</select>
				</td>
			</tr>
			<tr>
				<th><label for='pmpropp_initial_amount'><?php _e('Initial Amount', 'pmpro-payment-plans'); ?>:</label></th>
				<td><input type='text' id='pmpropp_initial_amount' name='pmpropp_initial_amount[]' value='!!initial_amount!!'/></td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php _e('Recurring Subscription', 'paid-memberships-pro' );?>:</label>
				</th>
				<td>
					<input id="pmpropp_recurring" name="pmpropp_recurring" type="checkbox" value="yes" /> 
					<label for="pmpropp_recurring"><?php _e('Check if this level has a recurring subscription payment.', 'paid-memberships-pro' );?></label>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="!!recurring_info_display!!">
				<th scope="row" valign="top">
					<label for="pmpropp_billing_amount"><?php _e('Billing Amount', 'paid-memberships-pro' );?>:</label>
				</th>
				<td>
					<?php
					if(pmpro_getCurrencyPosition() == "left")
						echo $pmpro_currency_symbol;
					?>
					<input name="pmpropp_billing_amount[]" type="text" value="!!billing_amount!!"  class="" />
					<?php
					if(pmpro_getCurrencyPosition() == "right")
						echo $pmpro_currency_symbol;
					?>
					<?php _e('per', 'paid-memberships-pro' );?>
					<input id="cycle_number" name="pmpropp_cycle_number[]" type="text" value="!!cycle_number!!" class="small-text" />
					<select id="cycle_period" name="pmpropp_cycle_period[]" selectval="!!cycle_period!!">
					  <?php
						$cycles = array( __('Day(s)', 'paid-memberships-pro' ) => 'Day', __('Week(s)', 'paid-memberships-pro' ) => 'Week', __('Month(s)', 'paid-memberships-pro' ) => 'Month', __('Year(s)', 'paid-memberships-pro' ) => 'Year' );
						foreach ( $cycles as $name => $value ) {
						  echo "<option value='$value'>$name</option>";
						}
					  ?>
					</select>
					<p class="description">
						<?php _e('The amount to be billed one cycle after the initial payment.', 'paid-memberships-pro' );?>
						<?php if($gateway == "braintree") { ?>
							<strong <?php if(!empty($pmpro_braintree_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Braintree integration currently only supports billing periods of "Month" or "Year".', 'paid-memberships-pro' );?></strong>
						<?php } elseif($gateway == "stripe") { ?>
							<p class="description"><strong <?php if(!empty($pmpro_stripe_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Stripe integration does not allow billing periods longer than 1 year.', 'paid-memberships-pro' );?></strong></p>
						<?php }?>
					</p>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="!!recurring_info_display!!">
				<th scope="row" valign="top">
					<label for="pmpropp_billing_limit"><?php _e('Billing Cycle Limit', 'paid-memberships-pro' );?>:</label>
				</th>
				<td>
					<input name="pmpropp_billing_limit[]" type="text" value="!!billing_limit!!" class="small-text" />
					<p class="description">
						<?php _e('The <strong>total</strong> number of recurring billing cycles for this level, including the trial period (if applicable) but not including the initial payment. Set to zero if membership is indefinite.', 'paid-memberships-pro' );?>
						<?php if ( ( $gateway == "stripe" ) && ! function_exists( 'pmprosbl_plugin_row_meta' ) ) { ?>
							<br /><strong <?php if(!empty($pmpro_stripe_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Stripe integration currently does not support billing limits. You can still set an expiration date below.', 'paid-memberships-pro' );?></strong>
							
						<?php } ?>
					</p>
				</td>
			</tr>
			<tr class="pmpropp_plan_recurring" style="!!recurring_info_display!!">
				<th scope="row" valign="top"><label><?php _e('Custom Trial', 'paid-memberships-pro' );?>:</label></th>
				<td>
					<input id="custom_trial" name="custom_trial" type="checkbox" value="yes" onclick="jQuery('.trial_info').toggle();" /> <label for="custom_trial"><?php _e('Check to add a custom trial period.', 'paid-memberships-pro' );?></label>
					<?php if($gateway == "twocheckout") { ?>
						<p class="description"><strong <?php if(!empty($pmpro_twocheckout_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('2Checkout integration does not support custom trials. You can do one period trials by setting an initial payment different from the billing amount.', 'paid-memberships-pro' );?></strong></p>
					<?php } ?>
				</td>
			</tr>
			<tr class="trial_info pmpropp_plan_recurring" style="!!recurring_info_display!!">
				<th scope="row" valign="top"><label for="pmpropp_trial_amount"><?php _e('Trial Billing Amount', 'paid-memberships-pro' );?>:</label></th>
				<td>
					<?php
					if(pmpro_getCurrencyPosition() == "left")
						echo $pmpro_currency_symbol;
					?>
					<input name="pmpropp_trial_amount[]" type="text" value="!!trial_amount!!" class="" />
					<?php
					if(pmpro_getCurrencyPosition() == "right")
						echo $pmpro_currency_symbol;
					?>
					<?php _e('for the first', 'paid-memberships-pro' );?>
					<input name="pmpropp_trial_limit[]" type="text" value="!!trial_limit!!" class="small-text" />
					<?php _e('subscription payments', 'paid-memberships-pro' );?>.
					<?php if($gateway == "stripe") { ?>
						<p class="description"><strong <?php if(!empty($pmpro_stripe_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Stripe integration currently does not support trial amounts greater than $0.', 'paid-memberships-pro' );?></strong></p>
					<?php } elseif($gateway == "braintree") { ?>
						<p class="description"><strong <?php if(!empty($pmpro_braintree_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Braintree integration currently does not support trial amounts greater than $0.', 'paid-memberships-pro' );?></strong></p>
					<?php } elseif($gateway == "payflowpro") { ?>
						<p class="description"><strong <?php if(!empty($pmpro_payflow_error)) { ?>class="pmpro_red"<?php } ?>><?php _e('Payflow integration currently does not support trial amounts greater than $0.', 'paid-memberships-pro' );?></strong></p>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label><?php _e('Membership Expiration', 'pmpro-payment-plans'); ?>:</label></th>
				<td><input id="pmpropp_plan_expiration" name="expiration" type="checkbox" value="yes"> <label for="pmpropp_plan_expiration">Check this to set when membership access expires.</label></td>
			</tr>
			<tr class="expiration_info" style="!!expiration_info_display!!">					
				<th scope="row" valign="top"><label for="expiration_number">Expires In:</label></th>
				<td>
					<input id="expiration_number" name="pmpropp_expiration_number[]" type="text" value="!!expiration_number!!" class="small-text">
					<select id="expiration_period" name="pmpropp_expiration_period[]" selectval="!!expiration_number!!">
				  		<option value="Day">Day(s)</option>
				  		<option value="Week">Week(s)</option>
				  		<option value="Month">Month(s)</option>
				  		<option value="Year">Year(s)</option>
			  		</select>
					<p class="description">Set the duration of membership access. Note that the any future payments (recurring subscription, if any) will be cancelled when the membership expires.</p>
					
					<div id="pmpro_expiration_warning" style="display: none;" class="notice error inline">
						<p>WARNING: This level is set with both a recurring billing amount and an expiration date. You only need to set one of these unless you really want this membership to expire after a certain number of payments. For more information, <a target="_blank" href="https://www.paidmembershipspro.com/important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=blog&amp;utm_content=important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels">see our post here</a>.</p>
					</div>					
				</td>
			</tr>
		</table>
	</div>
</div>