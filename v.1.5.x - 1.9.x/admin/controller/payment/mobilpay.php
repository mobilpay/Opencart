<?php
class ControllerPaymentMobilpay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/mobilpay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mobilpay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
		
		$this->data['entry_signature'] = $this->language->get('entry_signature');
		$this->data['entry_test'] = $this->language->get('entry_test');	
		$this->data['entry_total'] = $this->language->get('entry_total');
		
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_order_status_confirmed_pending'] = $this->language->get('entry_order_status_confirmed_pending');
		$this->data['entry_order_status_paid_pending'] = $this->language->get('entry_order_status_paid_pending');
		$this->data['entry_order_status_paid'] = $this->language->get('entry_order_status_paid');
		$this->data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$this->data['entry_order_status_credit'] = $this->language->get('entry_order_status_credit');		
		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['signature'])) {
			$this->data['error_signature'] = $this->error['signature'];
		} else {
			$this->data['error_signature'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/mobilpay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/mobilpay', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['mobilpay_signature'])) {
			$this->data['mobilpay_signature'] = $this->request->post['mobilpay_signature'];
		} else {
			$this->data['mobilpay_signature'] = $this->config->get('mobilpay_signature');
		}
		
		if (isset($this->request->post['mobilpay_total'])) {
			$this->data['mobilpay_total'] = $this->request->post['mobilpay_total'];
		} else {
			$this->data['mobilpay_total'] = $this->config->get('mobilpay_total');
		}
		
		if (isset($this->request->post['mobilpay_test'])) {
			$this->data['mobilpay_test'] = $this->request->post['mobilpay_test'];
		} else {
			$this->data['mobilpay_test'] = $this->config->get('mobilpay_test');
		}
				
		if (isset($this->request->post['mobilpay_order_status_id'])) {
			$this->data['mobilpay_order_status_id'] = $this->request->post['mobilpay_order_status_id'];
		} else {
			$this->data['mobilpay_order_status_id'] = $this->config->get('mobilpay_order_status_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_confirmed_pending_id'])) {
			$this->data['mobilpay_order_status_confirmed_pending_id'] = $this->request->post['mobilpay_order_status_confirmed_pending_id'];
		} else {
			$this->data['mobilpay_order_status_confirmed_pending_id'] = $this->config->get('mobilpay_order_status_confirmed_pending_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_paid_pending_id'])) {
			$this->data['mobilpay_order_status_paid_pending_id'] = $this->request->post['mobilpay_order_status_paid_pending_id'];
		} else {
			$this->data['mobilpay_order_status_paid_pending_id'] = $this->config->get('mobilpay_order_status_paid_pending_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_paid_id'])) {
			$this->data['mobilpay_order_status_paid_id'] = $this->request->post['mobilpay_order_status_paid_id'];
		} else {
			$this->data['mobilpay_order_status_paid_id'] = $this->config->get('mobilpay_order_status_paid_id'); 
		}

		if (isset($this->request->post['mobilpay_order_status_canceled_id'])) {
			$this->data['mobilpay_order_status_canceled_id'] = $this->request->post['mobilpay_order_status_canceled_id'];
		} else {
			$this->data['mobilpay_order_status_canceled_id'] = $this->config->get('mobilpay_order_status_canceled_id'); 
		}

		if (isset($this->request->post['mobilpay_order_status_credit_id'])) {
			$this->data['mobilpay_order_status_credit_id'] = $this->request->post['mobilpay_order_status_credit_id'];
		} else {
			$this->data['mobilpay_order_status_credit_id'] = $this->config->get('mobilpay_order_status_credit_id'); 
		}		

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['mobilpay_geo_zone_id'])) {
			$this->data['mobilpay_geo_zone_id'] = $this->request->post['mobilpay_geo_zone_id'];
		} else {
			$this->data['mobilpay_geo_zone_id'] = $this->config->get('mobilpay_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['mobilpay_status'])) {
			$this->data['mobilpay_status'] = $this->request->post['mobilpay_status'];
		} else {
			$this->data['mobilpay_status'] = $this->config->get('mobilpay_status');
		}
		
		if (isset($this->request->post['mobilpay_sort_order'])) {
			$this->data['mobilpay_sort_order'] = $this->request->post['mobilpay_sort_order'];
		} else {
			$this->data['mobilpay_sort_order'] = $this->config->get('mobilpay_sort_order');
		}

		$this->template = 'payment/mobilpay.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/mobilpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mobilpay_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>