<?php

if ( ! defined( 'ABSPATH' ) ) exit; 


class WC_Shipped_Order_Email extends WC_Email {
    
    public $content_footer;
    public $email_order_information;


	public function __construct() {
		
		$this->id = 'wc_shipped_order';
		
		$this->title = __( 'Email Pedido Enviado', 'woocommerce-shipping-assistant');
		$this->description = __( 'Este email é enviado sempre que o status de um pedido troca para enviado','woocommerce-shipping-assistant');
		$this->heading = __( 'Seu pedido foi enviado','woocommerce-shipping-assistant');
		$this->subject = __( 'Pedido enviado','woocommerce-shipping-assistant');
		
		$this->template_html  = 'emails/wc-expedited-order.php';
		
		//TODO Implementar plain email
		//$this->template_plain = 'emails/plain/wc-expedited-order.php';
		$this->template_base  = WC_SHIPPING_ASSISTANT_PLUGIN_PATH . 'templates/';

		add_action( 'woocommerce_order_status_pending_to_shipped', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_processing_to_shipped', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_completed_to_shipped', array( $this, 'trigger' ) );

		parent::__construct();
		$this->recipient = $this->get_option( 'recipient' );

		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
			
		$this->email_message = $this->get_option( 'email_message', $this->message );
	}



	public function trigger( $order_id ) {

		if ( ! $order_id )
			return;


		$this->object = new WC_Order( $order_id );

		$this->find[] = '{order_date}';
		$this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

		$this->find[] = '{order_number}';
		$this->replace[] = $this->object->get_order_number();

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;

		
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}
	
	
	public function is_email_order_information() {
        return $this->get_option( 'email_order_information', '' ) == 'yes' ? true : false;
	}
	
	
	public function get_content_footer() {
        return $this->get_option( 'content_footer', '' );
	}


	 

	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'email_order_information' => $this->is_email_order_information(),
				'content_footer' => $this->get_content_footer(),
				'sent_to_admin'      => false,
				'plain_text'         => false,
				'email'              => $this,
			), '', $this->template_base
		);
	}


	/*public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'email_order_information' => $this->is_email_order_information(),
				'content_footer' => $this->get_content_footer(),
				'sent_to_admin'      => false,
				'plain_text'         => true,
				'email'              => $this,
			), '', $this->template_base
		);
	}*/


	public function get_default_additional_content() {
		return __( 'We look forward to fulfilling your order soon.', 'woocommerce' );
	}



	 
	public function init_form_fields() {

		$this->form_fields = array(
		'enabled'            => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'woocommerce' ),
				'default' => 'yes',
			),
			'recipient'  => array(
				'title'       => __( 'Recipient(s)', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
				'placeholder' => '',
				'default'     => ''
			),
			'subject'    => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'description' => $placeholder_text,
				'placeholder' => '',
				'default'     => ''
			),
			'heading'    => array(
				'title'       => __( 'Cabeçalho', 'woocommerce-shipping-assistant' ),
				'type'        => 'text',
				'description' => $placeholder_text,
				'placeholder' => '',
				'default'     => ''
			),
			'additional_content' => array(
				'title'       => __( 'Conteúdo', 'woocommerce-shipping-assistant' ),
				'type'        => 'textarea',
				'description' => __( 'Campo que controla o conteúdo inicial do email.', 'woocommerce-shipping-assistant' ),
				'placeholder' => $this->message,
				'default'     => ''
			),
			'content_footer' => array(
				'title'       => __( 'Rodapé', 'woocommerce-shipping-assistant' ),
				'type'        => 'textarea',
				'description' => __( 'Campo que controla o rodapé do email', 'woocommerce-shipping-assistant' ),
				'placeholder' => $this->message,
				'default'     => ''
			),
			'email_order_information'    => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Habilita os campos de informações do pedido', 'woocommerce-shipping-assistant' ),
				'default' => 'yes'
			),
			'email_type' => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type',
				'options'     => array(
					/*'plain'	    => __( 'Plain text', 'woocommerce' ),*/
					'html' 	    => __( 'HTML', 'woocommerce' ),
				)
			)
		);
	}


} 