switch( $transaction_details['txn_type'] ) {
    case 'subscr_signup':

        // Store PayPal Details
        update_post_meta( $order_id, 'Payer PayPal address', $transaction_details['payer_email']);
        update_post_meta( $order_id, 'Payer PayPal first name', $transaction_details['first_name']);
        update_post_meta( $order_id, 'Payer PayPal last name', $transaction_details['last_name']);
        update_post_meta( $order_id, 'PayPal Subscriber ID', $transaction_details['subscr_id']);

        // Payment completed
        $order->add_order_note( __( 'IPN subscription sign up completed.', WC_Subscriptions::$text_domain ) );

        if ( self::$debug )
            self::$log->add( 'paypal', 'IPN subscription sign up completed for order ' . $order_id );

        break;

    case 'subscr_payment':

        if ( 'completed' == strtolower( $transaction_details['payment_status'] ) ) {
            // Store PayPal Details
            update_post_meta( $order_id, 'PayPal Transaction ID', $transaction_details['txn_id'] );
            update_post_meta( $order_id, 'Payer PayPal first name', $transaction_details['first_name'] );
            update_post_meta( $order_id, 'Payer PayPal last name', $transaction_details['last_name'] );
            update_post_meta( $order_id, 'PayPal Payment type', $transaction_details['payment_type'] );

            // Subscription Payment completed
            $order->add_order_note( __( 'IPN subscription payment completed.', WC_Subscriptions::$text_domain ) );

            if ( self::$debug )
                self::$log->add( 'paypal', 'IPN subscription payment completed for order ' . $order_id );

            $subscriptions_in_order = WC_Subscriptions_Order::get_recurring_items( $order );
            $subscription_item      = array_pop( $subscriptions_in_order );
            $subscription_key       = WC_Subscriptions_Manager::get_subscription_key( $order->id, $subscription_item['id'] );
            $subscription           = WC_Subscriptions_Manager::get_subscription( $subscription_key, $order->customer_user );

            // First payment on order, process payment & activate subscription
            if ( empty( $subscription['completed_payments'] ) ) {

                $order->payment_complete();

                WC_Subscriptions_Manager::activate_subscriptions_for_order( $order );

            } else {

                WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );

            }

        } elseif ( 'failed' == strtolower( $transaction_details['payment_status'] ) ) {

            // Subscription Payment completed
            $order->add_order_note( __( 'IPN subscription payment failed.', WC_Subscriptions::$text_domain ) );

            if ( self::$debug )
                self::$log->add( 'paypal', 'IPN subscription payment failed for order ' . $order_id );

            WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );

        } else {

            if ( self::$debug )
                self::$log->add( 'paypal', 'IPN subscription payment notification received for order ' . $order_id  . ' with status ' . $transaction_details['payment_status'] );

        }

        break;

    case 'subscr_cancel':

        if ( self::$debug )
            self::$log->add( 'paypal', 'IPN subscription cancelled for order ' . $order_id );

        // Subscription Payment completed
        $order->add_order_note( __( 'IPN subscription cancelled for order.', WC_Subscriptions::$text_domain ) );

        WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );

        break;

    case 'subscr_eot': // Subscription ended, either due to failed payments or expiration

        // PayPal fires the 'subscr_eot' notice immediately if a subscription is only for one billing period, so ignore the request when we only have one billing period
        if ( 1 != WC_Subscriptions_Order::get_subscription_length( $order ) ) {

            if ( self::$debug )
                self::$log->add( 'paypal', 'IPN subscription end-of-term for order ' . $order_id );

            // Record subscription ended
            $order->add_order_note( __( 'IPN subscription end-of-term for order.', WC_Subscriptions::$text_domain ) );

            // Ended due to failed payments so cancel the subscription
            if ( time() < strtotime( WC_Subscriptions_Manager::get_subscription_expiration_date( WC_Subscriptions_Manager::get_subscription_key( $order->id ), $order->customer_user ) ) )
                WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
            else
                WC_Subscriptions_Manager::expire_subscriptions_for_order( $order );
        }
        break;

    case 'subscr_failed': // Subscription sign up failed

        if ( self::$debug )
            self::$log->add( 'paypal', 'IPN subscription sign up failure for order ' . $order_id );

        // Subscription Payment completed
        $order->add_order_note( __( 'IPN subscription sign up failure.', WC_Subscriptions::$text_domain ) );

        WC_Subscriptions_Manager::failed_subscription_sign_ups_for_order( $order );

        break;
}
