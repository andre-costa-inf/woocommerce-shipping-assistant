<?php
defined('ABSPATH') || exit;

class WC_Shipping_Assistant {

	
    public static function init() {
        self::includes();
		add_filter( 'woocommerce_email_classes', array( __CLASS__ , 'register_email' ), 90, 1 );
	}
	
	private static function includes () {
	    include_once dirname( __FILE__ ) . '/class-wc-shipped-status.php';
	    include_once dirname( __FILE__ ) . '/class-wc-shortcode-status-checker.php';
	}

	
	public static function register_email( $emails ) {
		require_once( 'class-wc-shipped-order-email.php' );
		$emails['WC_Shipped_Order_Email'] = new WC_Shipped_Order_Email();
		return $emails;
	}
}



