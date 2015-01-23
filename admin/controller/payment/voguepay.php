<?php 
class ControllerPaymentVoguepay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/voguepay');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('voguepay', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['keywords_hints'] = $this->language->get('keywords_hints');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['error_merchant_id'])) {
			$data['error_merchant_id'] = $this->error['error_merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}
		
  		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/voguepay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('payment/voguepay', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['voguepay_merchant_id'])) {
			$data['voguepay_merchant_id'] = $this->request->post['voguepay_merchant_id'];
		} else {
			$data['voguepay_merchant_id'] = $this->config->get('voguepay_merchant_id');
		}
		
		if(!$data['voguepay_merchant_id'])$data['voguepay_merchant_id']='';
		
		if (isset($this->request->post['voguepay_total'])) {
			$data['voguepay_total'] = $this->request->post['voguepay_total'];
		} else {
			$data['voguepay_total'] = $this->config->get('voguepay_total');
		}
		 
		if (isset($this->request->post['voguepay_order_status_id'])) {
			$data['voguepay_order_status_id'] = $this->request->post['voguepay_order_status_id'];
		} else {
			$data['voguepay_order_status_id'] = $this->config->get('voguepay_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['voguepay_geo_zone_id'])) {
			$data['voguepay_geo_zone_id'] = $this->request->post['voguepay_geo_zone_id'];
		} else {
			$data['voguepay_geo_zone_id'] = $this->config->get('voguepay_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['voguepay_status'])) {
			$data['voguepay_status'] = $this->request->post['voguepay_status'];
		} else {
			$data['voguepay_status'] = $this->config->get('voguepay_status');
		}

		if (isset($this->request->post['voguepay_sort_order'])) {
			$data['voguepay_sort_order'] = $this->request->post['bkash_sort_order'];
		} else {
			$data['voguepay_sort_order'] = $this->config->get('voguepay_sort_order');
		}
		
		/*$this->template = 'payment/voguepay.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);*/
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('payment/voguepay.tpl', $data));
		//$this->response->setOutput($this->render());
	}

	protected function validate() {

		if (!$this->user->hasPermission('modify', 'payment/voguepay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['voguepay_merchant_id']) {
			$this->error['error_merchant_id'] = $this->language->get('error_merchant_id');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
