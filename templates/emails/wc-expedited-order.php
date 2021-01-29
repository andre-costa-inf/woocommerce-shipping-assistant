<?php


defined( 'ABSPATH' ) || exit;


do_action( 'woocommerce_email_header', $email_heading, $email ); ?>


<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<?php
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

if ($email_order_information) {
    do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
    do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
    do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
}



if ( $content_footer ) {
	echo wp_kses_post( wpautop( wptexturize( $content_footer ) ) );
} else {
do_action( 'woocommerce_email_footer', $email );
}
