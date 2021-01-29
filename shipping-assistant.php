<?php
/*
Plugin Name: Andre Costa - Woocommerce Shipping Assistant
Description: Adiciona o status Enviado, email automático para o status enviado e shortcode [woocommerce-status-checker] que pode ser adicionado em seu tema no arquivo: /woocommerce/myaccount/view-order.php.
Version: 1.0.2
Author: Andre Costa
Text Domain: woocommerce-shipping-assistant
*/
defined('ABSPATH') || exit;


define( 'WC_SHIPPING_ASSISTANT_PLUGIN_FILE', __FILE__ );
define( 'WC_SHIPPING_ASSISTANT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'WC_Shipping_Assistant' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-shipping-assistant.php';
	add_action( 'plugins_loaded', array( 'WC_Shipping_Assistant', 'init' ) );
}


//TODO Implementar activation hook com Checagem se Woocommerce está ativo assim como verificação da versão