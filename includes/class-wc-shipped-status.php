<?php

class WC_Shipped_Status {
    
    public function __construct () {
        add_action( 'init', array( __CLASS__ ,'register_custom_post_status'), 10 );
        add_filter( 'wc_order_statuses', array( __CLASS__ ,'enable_custom_wc_order_statuses_and_sort') );
        add_filter( 'bulk_actions-edit-shop_order', array( __CLASS__ ,'custom_dropdown_bulk_actions_shop_order'), 20, 1 );
        add_filter( 'woocommerce_email_actions', array( __CLASS__ ,'filter_woocommerce_email_actions') );
    }
    
    
static function register_custom_post_status() {
    register_post_status( 'wc-shipped', array(
        'label'                     => _x( 'Enviado' , 'Order status' , 'woocommerce-shipping-assistant' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Enviado <span class="count">(%s)</span>', 'Enviado <span class="count">(%s)</span>', 'woocommerce-shipping-assistant' )
    ) );

}


static function enable_custom_wc_order_statuses_and_sort( $order_statuses ) {
    $sorted_order_statuses = [];

    foreach( $order_statuses as $key => $label ) {
        $sorted_order_statuses[$key] = $order_statuses[$key];
        if( 'wc-processing' === $key ){
            $sorted_order_statuses['wc-shipped'] = _x( 'Enviado' , 'Order status' , 'woocommerce-shipping-assistant' );
        }
    }
    return $sorted_order_statuses;
}



static function custom_dropdown_bulk_actions_shop_order( $actions ) {
    $actions['mark_shipped'] = __( 'Marcar como Enviado', 'woocommerce-shipping-assistant' );
    return $actions;
}



static function filter_woocommerce_email_actions( $actions ){
    $actions[] = 'woocommerce_order_status_wc-shipped';
    return $actions;
}
    
}

new WC_Shipped_Status();