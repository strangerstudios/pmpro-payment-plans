<?php
/**
 * Admin display: Payment Plan columns and sections on PMPro admin screens.
 *
 * @since TBD
 */

/**
 * Add the Payment Plan column to the Orders list table.
 *
 * @since TBD
 */
function pmpropp_orderslist_columns( $columns ) {
	$columns['pmpropp_payment_plan'] = esc_html__( 'Payment Plan', 'pmpro-payment-plans' );
	return $columns;
}
add_filter( 'pmpro_manage_orderslist_columns', 'pmpropp_orderslist_columns' );

/**
 * Render the Payment Plan column on the Orders list table.
 *
 * Reads from subscription meta first so recurring orders display the plan;
 * falls back to order meta for one-time payments and legacy data.
 *
 * @since TBD
 */
function pmpropp_orderslist_custom_column( $column_name, $order_id ) {

	if ( 'pmpropp_payment_plan' !== $column_name ) {
		return;
	}

	$plan = null;

	$morder = new MemberOrder( $order_id );
	if ( ! empty( $morder->id ) ) {
		$subscription = is_callable( array( $morder, 'get_subscription' ) ) ? $morder->get_subscription() : null;
		if ( ! empty( $subscription ) ) {
			$plan = get_pmpro_subscription_meta( $subscription->get_id(), 'payment_plan', true );
		}
	}

	if ( empty( $plan ) ) {
		$plan = get_pmpro_membership_order_meta( $order_id, 'payment_plan', true );
	}

	if ( ! empty( $plan->name ) ) {
		echo esc_html( $plan->name );
	} else {
		echo '&#8212;';
	}
}
add_action( 'pmpro_manage_orderlist_custom_column', 'pmpropp_orderslist_custom_column', 10, 2 );

/**
 * Add the Payment Plan column to the Subscriptions list table.
 *
 * @since TBD
 */
function pmpropp_subscriptionslist_columns( $columns ) {
	$columns['pmpropp_payment_plan'] = esc_html__( 'Payment Plan', 'pmpro-payment-plans' );
	return $columns;
}
add_filter( 'pmpro_manage_subscriptionslist_columns', 'pmpropp_subscriptionslist_columns' );

/**
 * Render the Payment Plan column on the Subscriptions list table.
 *
 * @since TBD
 *
 * @param string             $column_name The column being rendered.
 * @param PMPro_Subscription $item        The subscription for this row.
 */
function pmpropp_subscriptionslist_custom_column( $column_name, $item ) {

	if ( 'pmpropp_payment_plan' !== $column_name ) {
		return;
	}

	$plan = empty( $item ) ? null : get_pmpro_subscription_meta( $item->get_id(), 'payment_plan', true );

	if ( ! empty( $plan->name ) ) {
		echo esc_html( $plan->name );
	} else {
		echo '&#8212;';
	}
}
add_action( 'pmpro_manage_subscriptionlist_custom_column', 'pmpropp_subscriptionslist_custom_column', 10, 2 );

/**
 * Add the Payment Plan column header to the Edit Member → Subscriptions panel.
 *
 * Hook is provided by a pending PMPro core PR.
 *
 * @since TBD
 */
function pmpropp_edit_member_subscriptions_extra_cols_header() {
	echo '<th>' . esc_html__( 'Payment Plan', 'pmpro-payment-plans' ) . '</th>';
}
add_action( 'pmpro_edit_member_subscriptions_extra_cols_header', 'pmpropp_edit_member_subscriptions_extra_cols_header' );

/**
 * Render the Payment Plan column on the Edit Member → Subscriptions panel.
 *
 * @since TBD
 *
 * @param PMPro_Subscription $subscription The subscription for this row.
 */
function pmpropp_edit_member_subscriptions_extra_cols_body( $subscription ) {

	$plan = empty( $subscription ) ? null : get_pmpro_subscription_meta( $subscription->get_id(), 'payment_plan', true );

	if ( ! empty( $plan->name ) ) {
		echo '<td>' . esc_html( $plan->name ) . '</td>';
	} else {
		echo '<td>&#8212;</td>';
	}
}
add_action( 'pmpro_edit_member_subscriptions_extra_cols_body', 'pmpropp_edit_member_subscriptions_extra_cols_body' );

/**
 * Render a Payment Plan section on the single subscription view page.
 *
 * Hook is provided by a pending PMPro core PR. Bails (renders nothing) when
 * the subscription has no plan attached.
 *
 * @since TBD
 *
 * @param PMPro_Subscription $subscription The subscription being viewed.
 */
function pmpropp_after_subscription_view_main( $subscription ) {

	if ( empty( $subscription ) ) {
		return;
	}

	$plan = get_pmpro_subscription_meta( $subscription->get_id(), 'payment_plan', true );
	if ( empty( $plan->name ) ) {
		return;
	}
	?>
	<div id="pmpropp_subscription-view-payment-plan" class="pmpro_section" data-visibility="shown" data-activated="true">
		<div class="pmpro_section_toggle">
			<button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
				<span class="dashicons dashicons-arrow-up-alt2"></span>
				<?php esc_html_e( 'Payment Plan', 'pmpro-payment-plans' ); ?>
			</button>
		</div>
		<div class="pmpro_section_inside">
			<ul class="pmpro_list pmpro_list-plain pmpro_list-with-labels pmpro_cols-2">
				<li class="pmpro_list_item">
					<span class="pmpro_list_item_label"><?php esc_html_e( 'Plan Name', 'pmpro-payment-plans' ); ?></span>
					<?php echo esc_html( $plan->name ); ?>
				</li>
				<?php if ( ! empty( $plan->description ) ) { ?>
					<li class="pmpro_list_item">
						<span class="pmpro_list_item_label"><?php esc_html_e( 'Description', 'pmpro-payment-plans' ); ?></span>
						<?php echo wp_kses_post( $plan->description ); ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<?php
}
add_action( 'pmpro_after_subscription_view_main', 'pmpropp_after_subscription_view_main' );

/**
 * Add the Payment Plan column header to the per-member orders tables.
 *
 * Covers the Edit Member → Orders panel and the legacy user-edit profile
 * orders table.
 *
 * @since TBD
 */
function pmpropp_member_orders_extra_cols_header() {
	echo '<th>' . esc_html__( 'Payment Plan', 'pmpro-payment-plans' ) . '</th>';
}
add_action( 'pmpromh_orders_extra_cols_header', 'pmpropp_member_orders_extra_cols_header' );

/**
 * Render the Payment Plan column on the per-member orders tables.
 *
 * Reads from subscription meta first (recurring orders), falls back to order
 * meta for one-time payments and legacy data.
 *
 * @since TBD
 *
 * @param MemberOrder $order The order for this row.
 */
function pmpropp_member_orders_extra_cols_body( $order ) {

	$plan = null;

	if ( ! empty( $order ) ) {
		$subscription = is_callable( array( $order, 'get_subscription' ) ) ? $order->get_subscription() : null;
		if ( ! empty( $subscription ) ) {
			$plan = get_pmpro_subscription_meta( $subscription->get_id(), 'payment_plan', true );
		}

		if ( empty( $plan ) && ! empty( $order->id ) ) {
			$plan = get_pmpro_membership_order_meta( $order->id, 'payment_plan', true );
		}
	}

	if ( ! empty( $plan->name ) ) {
		echo '<td>' . esc_html( $plan->name ) . '</td>';
	} else {
		echo '<td>&#8212;</td>';
	}
}
add_action( 'pmpromh_orders_extra_cols_body', 'pmpropp_member_orders_extra_cols_body' );
