<?php

class ControllerExtensionPaymentDPSPxPost extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/payment/dps_pxpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('payment_dps_pxpost', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$text_array = array('heading_title', 'text_enabled','text_disabled','text_auth','text_purchase','text_all_zones',
			'text_yes',	'text_no','text_successful','text_declined','text_off',	'text_edit', 'entry_username', 'entry_pass',
			'entry_url', 'entry_txn_type', 'entry_billing_vault', 'entry_processed_status',	'entry_avs', 'entry_failed_status',
			'entry_geo_zone', 'entry_status', 'entry_sort_order', 'button_save', 'button_cancel', 'tab_general',
			'help_pass', 'help_url', 'help_billing_vault', 'help_txn_type');

		foreach($text_array as $key){
			$data[$key] = $this->language->get($key);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['pass'])) {
			$data['error_pass'] = $this->error['pass'];
		} else {
			$data['error_pass'] = '';
		}

		if (isset($this->error['url'])) {
			$data['error_url'] = $this->error['url'];
		} else {
			$data['error_url'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => FALSE
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/dps_pxpost', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/payment/dps_pxpost', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['payment_dps_pxpost_username'])) {
			$data['payment_dps_pxpost_username'] = $this->request->post['payment_dps_pxpost_username'];
		} else {
			$data['payment_dps_pxpost_username'] = $this->config->get('payment_dps_pxpost_username');
		}

		if (isset($this->request->post['payment_dps_pxpost_pass'])) {
			$data['payment_dps_pxpost_pass'] = $this->request->post['payment_dps_pxpost_pass'];
		} else {
			$data['payment_dps_pxpost_pass'] = $this->config->get('payment_dps_pxpost_pass');
		}

		if (isset($this->request->post['payment_dps_pxpost_url'])) {
			$data['payment_dps_pxpost_url'] = $this->request->post['payment_dps_pxpost_url'];
		} else {
			$data['payment_dps_pxpost_url'] = $this->config->get('payment_dps_pxpost_url');
		}
		if ($data['payment_dps_pxpost_url'] == '') {
			$data['payment_dps_pxpost_url'] = 'https://sec.paymentexpress.com/pxpost.aspx';
		}		

		if (isset($this->request->post['payment_dps_pxpost_txn_type'])) {
			$data['payment_dps_pxpost_txn_type'] = $this->request->post['payment_dps_pxpost_txn_type'];
		} else {
			$data['payment_dps_pxpost_txn_type'] = $this->config->get('payment_dps_pxpost_txn_type');
		}

		if (isset($this->request->post['payment_dps_pxpost_billing_vault'])) {
			$data['payment_dps_pxpost_billing_vault'] = $this->request->post['payment_dps_pxpost_billing_vault'];
		} else {
			$data['payment_dps_pxpost_billing_vault'] = $this->config->get('payment_dps_pxpost_billing_vault');
		}
		
		if (isset($this->request->post['payment_dps_pxpost_avs'])) {
			$data['payment_dps_pxpost_avs'] = $this->request->post['payment_dps_pxpost_avs'];
		} else {
			$data['payment_dps_pxpost_avs'] = $this->config->get('payment_dps_pxpost_avs');
		}

		if (isset($this->request->post['payment_dps_pxpost_processed_status_id'])) {
			$data['payment_dps_pxpost_processed_status_id'] = $this->request->post['payment_dps_pxpost_processed_status_id'];
		} else {
			$data['payment_dps_pxpost_processed_status_id'] = $this->config->get('payment_dps_pxpost_processed_status_id');
		}
		if ( ! $data['payment_dps_pxpost_processed_status_id']) $data['payment_dps_pxpost_processed_status_id'] = 15;  # "Processed"

		if (isset($this->request->post['payment_dps_pxpost_failed_status_id'])) {
			$data['payment_dps_pxpost_failed_status_id'] = $this->request->post['payment_dps_pxpost_failed_status_id'];
		} else {
			$data['payment_dps_pxpost_failed_status_id'] = $this->config->get('payment_dps_pxpost_failed_status_id');
		}
		if ( ! $data['payment_dps_pxpost_failed_status_id']) $data['payment_dps_pxpost_failed_status_id'] = 10;  # "Failed"

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_dps_pxpost_geo_zone_id'])) {
			$data['payment_dps_pxpost_geo_zone_id'] = $this->request->post['payment_dps_pxpost_geo_zone_id'];
		} else {
			$data['payment_dps_pxpost_geo_zone_id'] = $this->config->get('payment_dps_pxpost_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_dps_pxpost_status'])) {
			$data['payment_dps_pxpost_status'] = $this->request->post['payment_dps_pxpost_status'];
		} else {
			$data['payment_dps_pxpost_status'] = $this->config->get('payment_dps_pxpost_status');
		}

		if (isset($this->request->post['payment_dps_pxpost_sort_order'])) {
			$data['payment_dps_pxpost_sort_order'] = $this->request->post['payment_dps_pxpost_sort_order'];
		} else {
			$data['payment_dps_pxpost_sort_order'] = $this->config->get('payment_dps_pxpost_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/dps_pxpost', $data));
	}

	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'extension/payment/dps_pxpost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ( ! $this->request->post['payment_dps_pxpost_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}


		if ( ! $this->request->post['payment_dps_pxpost_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

?>