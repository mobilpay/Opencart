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

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		
		$data['entry_signature'] = $this->language->get('entry_signature');
		$data['entry_test'] = $this->language->get('entry_test');	
		$data['entry_total'] = $this->language->get('entry_total');
		$data['help_total'] = $this->language->get('help_total');
		
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_confirmed_pending'] = $this->language->get('entry_order_status_confirmed_pending');
		$data['entry_order_status_paid_pending'] = $this->language->get('entry_order_status_paid_pending');
		$data['entry_order_status_paid'] = $this->language->get('entry_order_status_paid');
		$data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$data['entry_order_status_credit'] = $this->language->get('entry_order_status_credit');		
		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/mobilpay', 'token=' . $this->session->data['token'], 'SSL'),
      		);
				
		$data['action'] = $this->url->link('payment/mobilpay', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['mobilpay_signature'])) {
			$data['mobilpay_signature'] = $this->request->post['mobilpay_signature'];
		} else {
			$data['mobilpay_signature'] = $this->config->get('mobilpay_signature');
		}
		
		if (isset($this->request->post['mobilpay_total'])) {
			$data['mobilpay_total'] = $this->request->post['mobilpay_total'];
		} else {
			$data['mobilpay_total'] = $this->config->get('mobilpay_total');
		}
		
		if (isset($this->request->post['mobilpay_test'])) {
			$data['mobilpay_test'] = $this->request->post['mobilpay_test'];
		} else {
			$data['mobilpay_test'] = $this->config->get('mobilpay_test');
		}
				
		if (isset($this->request->post['mobilpay_order_status_id'])) {
			$data['mobilpay_order_status_id'] = $this->request->post['mobilpay_order_status_id'];
		} else {
			$data['mobilpay_order_status_id'] = $this->config->get('mobilpay_order_status_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_confirmed_pending_id'])) {
			$data['mobilpay_order_status_confirmed_pending_id'] = $this->request->post['mobilpay_order_status_confirmed_pending_id'];
		} else {
			$data['mobilpay_order_status_confirmed_pending_id'] = $this->config->get('mobilpay_order_status_confirmed_pending_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_paid_pending_id'])) {
			$data['mobilpay_order_status_paid_pending_id'] = $this->request->post['mobilpay_order_status_paid_pending_id'];
		} else {
			$data['mobilpay_order_status_paid_pending_id'] = $this->config->get('mobilpay_order_status_paid_pending_id'); 
		}
		
		if (isset($this->request->post['mobilpay_order_status_paid_id'])) {
			$data['mobilpay_order_status_paid_id'] = $this->request->post['mobilpay_order_status_paid_id'];
		} else {
			$data['mobilpay_order_status_paid_id'] = $this->config->get('mobilpay_order_status_paid_id'); 
		}

		if (isset($this->request->post['mobilpay_order_status_canceled_id'])) {
			$data['mobilpay_order_status_canceled_id'] = $this->request->post['mobilpay_order_status_canceled_id'];
		} else {
			$data['mobilpay_order_status_canceled_id'] = $this->config->get('mobilpay_order_status_canceled_id'); 
		}

		if (isset($this->request->post['mobilpay_order_status_credit_id'])) {
			$data['mobilpay_order_status_credit_id'] = $this->request->post['mobilpay_order_status_credit_id'];
		} else {
			$data['mobilpay_order_status_credit_id'] = $this->config->get('mobilpay_order_status_credit_id'); 
		}		

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['mobilpay_geo_zone_id'])) {
			$data['mobilpay_geo_zone_id'] = $this->request->post['mobilpay_geo_zone_id'];
		} else {
			$data['mobilpay_geo_zone_id'] = $this->config->get('mobilpay_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['mobilpay_status'])) {
			$data['mobilpay_status'] = $this->request->post['mobilpay_status'];
		} else {
			$data['mobilpay_status'] = $this->config->get('mobilpay_status');
		}
		
		if (isset($this->request->post['mobilpay_sort_order'])) {
			$data['mobilpay_sort_order'] = $this->request->post['mobilpay_sort_order'];
		} else {
			$data['mobilpay_sort_order'] = $this->config->get('mobilpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/mobilpay.tpl', $data));
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
