<?php
class ControllerExtensionPaymentDPSPxPost extends Controller {

	public function index() {
		$this->language->load('extension/payment/dps_pxpost');
		$this->load->model('extension/payment/dps_pxpost');
		
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');
		
		$data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_cvv2Miss'] = $this->language->get('entry_cc_cvv2Miss');
		
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => substr(strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),2)
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/dps_pxpost')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/dps_pxpost', $data);
		} else {
			return $this->load->view('default/template/extension/payment/dps_pxpost', $data);
		}
	}
	
	public function send() {

		$this->load->language('extension/payment/dps_pxpost');
		$this->load->model('checkout/order');
		$url = $this->config->get('payment_dps_pxpost_url');	

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$data = "";
		$data .= "<Txn>";
		$data .= "<PostUsername>".$this->config->get('payment_dps_pxpost_username')."</PostUsername>"; #Insert your DPS Username here
		$data .= "<PostPassword>".$this->config->get('payment_dps_pxpost_pass')."</PostPassword>"; #Insert your DPS Password here

		$data .= "<Amount>".$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE)."</Amount>";
		$data .= "<InputCurrency>".$order_info['currency_code']."</InputCurrency>";
		$data .= "<CardHolderName>".$this->request->post['cc_owner']."</CardHolderName>";
		$data .= "<CardNumber>".str_replace(' ', '', $this->request->post['cc_number'])."</CardNumber>";
		$data .= "<DateExpiry>".$this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year']."</DateExpiry>";
		$data .= "<Cvc2>".$this->request->post['cc_cvv2']."</Cvc2>";
		
		if ( strlen($this->request->post['cc_cvv2'])>0)
		{
			$data .= "<Cvc2Presence>1</Cvc2Presence>";
		} else {
			if ($this->request->post['cc_cvv2_miss']!="") {
				$data .= "<Cvc2Presence>".$this->request->post['cc_cvv2_miss']."</Cvc2Presence>";
			} else 
			{
				$data .= "<Cvc2Presence>0</Cvc2Presence>";
			}
		
		}
		
		
		
		$data .= "<TxnType>".$this->config->get('payment_dps_pxpost_txn_type')."</TxnType>";
		$data .= "<MerchantReference>".$order_info['order_id']."</MerchantReference>";
		$enable_add_bill_card = $this->config->get('payment_dps_pxpost_billing_vault');
		if ($enable_add_bill_card) {
			$data .= "<EnableAddBillCard>1</EnableAddBillCard>";
		}
		
		$avs_check = $this->config->get('payment_dps_pxpost_avs');
		if ($avs_check){
			$data .= "<EnableAvsData>1</EnableAvsData>";
			$data .= "<AvsAction>1</AvsAction>";
			$data .= "<AvsStreetAddress>".$order_info['payment_address_1']."</AvsStreetAddress>";
			$data .= "<AvsPostCode>".$order_info['payment_postcode']."</AvsPostCode>";
		}

		$data .= "</Txn>"; 		

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		
		$json = array();
		
		if (curl_error($curl)) {
			$json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);
			
			$this->log->write('DPS PxPost CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));	
		} elseif ($response) {
		
			$response_data = array();
		
			$response_data  = $this->parse_xml($response); 
			$success = $response_data["TXN"]["SUCCESS"];
			$comment = (string)$response_data['TXN'][$success]['CARDHOLDERRESPONSETEXT'];
			$order_id = (int)$response_data['TXN'][$success]['MERCHANTREFERENCE'];
			
		
			if ($success == '1')
			{
				$this->load->model('checkout/order');
				$this->model_checkout_order->confirm($order_id, $this->config->get('payment_dps_pxpost_processed_status_id'), $comment);
				$json['success'] = $this->url->link('checkout/success', '', 'SSL');
				
			} else {

				$json['error'] = $response_data['TXN'][$success]['CARDHOLDERRESPONSETEXT'];
				
				
			}
		
		} else {
			$json['error'] = 'Empty Gateway Response';
			
			$this->log->write('DPS PxPost CURL ERROR: Empty Gateway Response');
		}
		
		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function parse_xml($data)
	{
		 
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals);
		xml_parser_free($xml_parser);
			
		$params = array();
		$level = array();
		foreach ($vals as $xml_elem) {
			if ($xml_elem['type'] == 'open') {
				if (array_key_exists('attributes',$xml_elem)) {
				list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
			} 
			else {
				$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			if ($xml_elem['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params';
							
				while($start_level < $xml_elem['level']) {
					$php_stmt .= '[$level['.$start_level.']]';
					$start_level++;
				}
					
						
				 
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				if (isset($xml_elem['value']))		
					eval($php_stmt);
			}

		}
		 	
		return $params;
	}
}
?>