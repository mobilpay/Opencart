<?php

require_once DIR_APPLICATION .'Mobilpay/Payment/Request/Abstract.php';
require_once DIR_APPLICATION .'Mobilpay/Payment/Request/Card.php';
require_once DIR_APPLICATION .'Mobilpay/Payment/Request/Notify.php';
require_once DIR_APPLICATION .'Mobilpay/Payment/Invoice.php';
require_once DIR_APPLICATION .'Mobilpay/Payment/Address.php';

class ControllerPaymentMobilpay extends Controller {
	protected function index() {
		$this->load->model('checkout/order');
		
		$this->language->load('payment/mobilpay');
		
    	$this->data['button_confirm'] = $this->language->get('button_confirm');       		
		
		if (!$this->config->get('mobilpay_test')) {
    		$this->data['action'] = 'https://secure.mobilpay.ro';
  		} else {
			$this->data['action'] = 'http://sandboxsecure.mobilpay.ro';
		}
		
		#calea catre certificatul public
		$x509FilePath 	= DIR_APPLICATION .'Mobilpay/cert/public.cer';
		
		try
		{	
			$objPmReqCard = new Mobilpay_Payment_Request_Card();			
			
			$objPmReqCard->signature = $this->config->get('mobilpay_signature');
			
			$objPmReqCard->orderId   = $this->session->data['order_id'];
			
			$objPmReqCard->confirmUrl 			= $this->url->link('payment/mobilpay/callback');
			$objPmReqCard->returnUrl 			= $this->url->link('payment/mobilpay/status');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
			#detalii cu privire la plata: moneda, suma, descrierea
			$objPmReqCard->invoice = new Mobilpay_Payment_Invoice();
			$objPmReqCard->invoice->currency	= 'RON';
			$objPmReqCard->invoice->amount		= $order_info['total'];
			$objPmReqCard->invoice->installments= '1';
			$objPmReqCard->invoice->details		= 'Plata cu card-ul';						
			
			#detalii cu privire la adresa posesorului cardului	
			$billingAddress 				= new Mobilpay_Payment_Address();
			$billingAddress->type			= 'person';
			$billingAddress->firstName		= $order_info['payment_firstname'];
			$billingAddress->lastName		= $order_info['payment_lastname'];			
			$billingAddress->country		= $order_info['payment_iso_code_3'];			
			$billingAddress->county			= $order_info['payment_iso_code_2'];;
			$billingAddress->city			= $order_info['payment_city'];
			$billingAddress->zipCode		= $order_info['payment_postcode'];
			$billingAddress->address		= $order_info['payment_address_1'];
			$billingAddress->email			= $order_info['email'];
			$billingAddress->mobilePhone	= $order_info['telephone'];
			$objPmReqCard->invoice->setBillingAddress($billingAddress);
			
			#detalii cu privire la adresa de livrare
			$shippingAddress 				= new Mobilpay_Payment_Address();
			
			$shippingAddress->type			= 'person';
			$shippingAddress->firstName		= $order_info['shipping_firstname'];
			$shippingAddress->lastName		= $order_info['shipping_lastname'];
			$shippingAddress->country		= $order_info['shipping_country'];			
			$shippingAddress->city			= $order_info['shipping_city'];
			$shippingAddress->zipCode		= $order_info['shipping_postcode'];
			$shippingAddress->address		= $order_info['shipping_address_1'];
			$shippingAddress->email			= $order_info['email'];
			$shippingAddress->mobilePhone	= $order_info['telephone'];
			$objPmReqCard->invoice->setShippingAddress($shippingAddress);
			$objPmReqCard->encrypt($x509FilePath);
		}
		catch(Exception $e)
		{
		}
		
		$this->data['env_key'] = $objPmReqCard->getEnvKey();
		$this->data['data'] = $objPmReqCard->getEncData();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mobilpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/mobilpay.tpl';
		} else {
			$this->template = 'default/template/payment/mobilpay.tpl';
		}	
		
		$this->render();
	}
	
	public function callback() {
	
		$errorCode 		= 0;
		$errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_NONE;
		$errorMessage	= '';

		if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0)
		{
			if(isset($_POST['env_key']) && isset($_POST['data']))
			{
				#calea catre cheia privata
				#cheia privata este generata de mobilpay, accesibil in Admin -> Conturi de comerciant -> Detalii -> Setari securitate
				$privateKeyFilePath = DIR_APPLICATION .'Mobilpay/cert/private.key';

				try
				{
					$objPmReq = Mobilpay_Payment_Request_Abstract::factoryFromEncrypted($_POST['env_key'], $_POST['data'], $privateKeyFilePath);
					
					$order_id = $objPmReq->orderId;
					$this->load->model('checkout/order');
					
					$order_info = $this->model_checkout_order->getOrder($order_id);									
					
					switch($objPmReq->objPmNotify->action)
					{
					#orice action este insotit de un cod de eroare si de un mesaj de eroare. Acestea pot fi citite folosind $cod_eroare = $objPmReq->objPmNotify->errorCode; respectiv $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;
					#pentru a identifica ID-ul comenzii pentru care primim rezultatul platii folosim $id_comanda = $objPmReq->orderId;										
					case 'confirmed':
						#cand action este confirmed avem certitudinea ca banii au plecat din contul posesorului de card si facem update al starii comenzii si livrarea produsului
						$errorMessage = $objPmReq->objPmNotify->getCrc();						
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_id'), '', TRUE);
						break;
					case 'confirmed_pending': 
						#cand action este confirmed_pending inseamna ca tranzactia este in curs de verificare antifrauda. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						$errorMessage = $objPmReq->objPmNotify->getCrc();
						$this->model_checkout_order->confirm($order_id, $this->config->get('mobilpay_order_status_confirmed_pending_id'));
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_confirmed_pending_id'), '', FALSE);
						break;
					case 'paid_pending':
						#cand action este paid_pending inseamna ca tranzactia este in curs de verificare. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						$errorMessage = $objPmReq->objPmNotify->getCrc();
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_paid_pending_id'), '', FALSE);
						break;
					case 'paid':
						#cand action este paid inseamna ca tranzactia este in curs de procesare. Nu facem livrare/expediere. In urma trecerii de aceasta procesare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						$errorMessage = $objPmReq->objPmNotify->getCrc();
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_paid_id'), '', FALSE);
						break;
					case 'canceled':
						#cand action este canceled inseamna ca tranzactia este anulata. Nu facem livrare/expediere.
						$errorMessage = $objPmReq->objPmNotify->getCrc();
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_canceled_id'), '', TRUE);
						break;
					case 'credit':
						#cand action este credit inseamna ca banii sunt returnati posesorului de card. Daca s-a facut deja livrare, aceasta trebuie oprita sau facut un reverse. 
						$errorMessage = $objPmReq->objPmNotify->getCrc();
						$this->model_checkout_order->update($order_id, $this->config->get('mobilpay_order_status_credit_id'), '', TRUE);
						break;
					default:
						$errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
						$errorCode 		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_ACTION;
						$errorMessage 	= 'mobilpay_refference_action paramaters is invalid';
						break;
					}
				}
				catch(Exception $e)
				{
					$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_TEMPORARY;
					$errorCode		= $e->getCode();
					$errorMessage 	= $e->getMessage();
				}
			}
			else
			{
				$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
				$errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;
				$errorMessage 	= 'mobilpay.ro posted invalid parameters';
			}
		}
		else 
		{
			$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
			$errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_METHOD;
			$errorMessage 	= 'invalid request metod for payment confirmation';
		}

		header('Content-type: application/xml');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		if($errorCode == 0)
		{
			echo "<crc>{$errorMessage}</crc>";
		}
		else
		{
			echo "<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\">{$errorMessage}</crc>";
		}

	}
	
	public function status() {
		$order_id = $this->session->data['order_id'];
		if (isset($order_id)) {
		
			$this->load->model('checkout/order');					
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			if ($order_info && $order_info['order_status_id']) {
				$this->redirect($this->url->link('checkout/success', '', 'SSL'));
			} else {
				$this->redirect($this->url->link('checkout/failed', '', 'SSL'));
			}
		} else {
			$this->redirect($this->url->link('checkout/failed', '', 'SSL'));
		}
	}
}
?>