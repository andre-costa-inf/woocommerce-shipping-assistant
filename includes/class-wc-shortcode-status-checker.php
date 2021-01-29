<?php
defined('ABSPATH') || exit;

class WC_Shortcode_Status_Checker {
    
    public function __construct() {
        
        add_shortcode('woocommerce-status-checker', array( $this ,'status_checker')); 
        add_action( 'wp_enqueue_scripts', array( $this , 'add_css_style' ));   
    }
    
    public function add_css_style(){
        wp_register_style( 'woocommerce-status-checker-css', plugin_dir_url(dirname(__FILE__)).'/assets/css/woocommerce-status-checker.css' );
        wp_enqueue_style('woocommerce-status-checker-css');
    }
    
    
    
    public static function status_checker() {
        global $woocommerce, $post;
        
        if ( is_view_order_page() ) {
            global $wp;
            $order_id = wc_clean( $wp->query_vars['view-order'] );
            //TODO Testar adicionar o shortcode em outras páginas em caso o usuario erre a pagina e corrigir possiveis bugs
        $_order = wc_get_order($order_id);
        $order_id = $_order->get_id();
     }
    
    $order = wc_get_order( $order_id );
    $order_status = $order->get_status();
    
    if ( empty($order_id) 
    || $order_id == 0 
    || $order_status == 'cancelled' 
    || $order_status == 'refunded'
    || $order_status == 'failed')
        return; // Nao mostrar a barra de status do pedido;
    
    $this->print_status_bar($order_status);
        
    }
    
    protected function print_status_bar($order_status) {
        echo('<p>
        <ol class="statuschecker" data-statuschecker-steps="4">
            <li class="statuschecker-'. $this->get_pedido_confirmado_status($order_status) . '">'.__( 'Pedido Recebido','woocommerce-shipping-assistant').'</li>
            <li class="statuschecker-'. $this->get_pagamento_status($order_status) . '">'.__( 'Pagamento','woocommerce-shipping-assistant').'</li>
            <li class="statuschecker-'. $this->get_enviado_status($order_status).'">'.__( 'Enviado','woocommerce-shipping-assistant').'</li>
            <li class="statuschecker-'. $this->get_completo_status($order_status).'">'.__( 'Completo','woocommerce-shipping-assistant').'</li>
        </ol>
        </p>');
    }
        
   protected function get_pedido_confirmado_status($order_status) {
        if ($order_status == 'pending' 
        || $order_status == 'on-hold' 
        || $order_status == 'processing' 
        || $order_status == 'shipped'
        || $order_status == 'completed'){
            return 'done';
        } else {
            return 'todo';
        }
    }

    protected function get_pagamento_status($order_status) {
        if ( $order_status == 'processing' 
        || $order_status == 'shipped'
        || $order_status == 'completed'){
            return 'done';
        } else {
            return 'todo';
        }
    }

    protected function get_enviado_status ($order_status) {
                if ( $order_status == 'shipped'
        || $order_status == 'completed'){
            return 'done';
        } else {
            return 'todo';
        }
        
    }

    protected function get_completo_status ($order_status) {
        if (  $order_status == 'completed'){
            return 'done';
        } else {
            return 'todo';
        }
    }

}

new WC_Shortcode_Status_Checker();
