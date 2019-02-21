<?php
/**
 * WooCommerce Import Tracking
 *
 * @package WooCommerce\Tracks
 */

defined( 'ABSPATH' ) || exit;

/**
 * This class adds actions to track usage of WooCommerce Orders.
 */
class WC_Orders_Tracking {
	/**
	 * Init tracking.
	 */
	public static function init() {
		add_action( 'woocommerce_order_status_changed', array( __CLASS__, 'track_order_status_change' ), 10, 3 );
		add_action( 'load-edit.php', array( __CLASS__, 'track_orders_view' ), 10 );
	}

	/**
	 * Send a Tracks event when the Orders page is viewed.
	 */
	public static function track_orders_view() {
		WC_Tracks::record_event( 'orders_view' );
	}

	/**
	 * Send a Tracks event when an order status is changed.
	 *
	 * @param int    $id Order id.
	 * @param string $new_status WooCommerce order status.
	 */
	public static function track_order_status_change( $id, $previous_status, $next_status ) {
		$properties = array(
			'order_id'   => $id,
			'next_status' => $next_status,
			'previous_status' => $previous_status,
		);

		WC_Tracks::record_event( 'orders_edit_status_change', $properties );
	}
}

add_filter( 'http_request_args', function( $r, $url ) {
	error_log( $r['method'] . ' ' . $url );
	return $r;
},10, 2 );
