<?php

class ControllerCommonHome extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		else{
			$this->response->redirect($this->url->link('module/vnptepay', '', true));			
		}
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		// $data['content_top'] = $this->load->controller('common/content_top');
		// $data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['listcart'] = '';
		$data['success_mess'] = '';
		$data['error_mess'] = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//print_r($this->request->post);
			// $this->session->data['provider'] = $this->request->post['provider'];
			// $this->session->data['amount'] = $this->request->post['amount'];
			// $this->session->data['quantity'] = $this->request->post['quantity'];
			$config = "";
			$config = array(
			    //link webservice
			    'ws_url' => html_entity_decode($this->config->get('vnptepay_ws_url')),
			    //partner username
			    'partnerName' => html_entity_decode($this->config->get('vnptepay_partner_name')),
			    //partner password
			    'partnerPassword' => html_entity_decode($this->config->get('vnptepay_partner_password')),
			    //key sofpin
			    'key_sofpin' => html_entity_decode($this->config->get('vnptepay_key_sofpin')),
			    //thời gian tối đa thực hiện giao dịch (tính bằng giây)
			    'time_out' => html_entity_decode($this->config->get('vnptepay_time_out')),			    
			    'request_id' => html_entity_decode($this->config->get('vnptepay_partner_name')).'_'.time().rand(000, 999)
			);
			// print_r($config);

        	$sofpin = $this->downloadSofpin($this->request->post,$config);
        	// echo "<pre>";
        	// print_r($sofpin);
        	// echo "</pre>";
        	if($sofpin->errorCode == "0"){
        		$key = substr(md5($config['key_sofpin']), 0, 24);
		        $cleartext = mcrypt_decrypt("tripledes", $key, base64_decode($sofpin->listCards), "ecb", "\0\0\0\0\0\0\0\0");
		        //echo "Sofpin decrypt: ". $cleartext . "<br>";			
				
				$cleartext = substr($cleartext, 0, stripos($cleartext,"]}") + 2);
				$listcard = json_decode($cleartext)->listCards;
			
				//echo "Sofpin decrypt: ". $cleartext . "<br>";
				// var_dump(json_decode($cleartext));
				$tmpval = array();
				foreach ($listcard as $key => $value) {
					$tmpval[$key] = explode("|", $value);
				}
				$data['listcart'] = $tmpval;
				$this->load->model('module/vnptepay');
				$datainsert = "";
				$datainsert = array(
					'request_id' => $config['request_id'], 
					'listcards' => $cleartext, 
					'customer_id' => $this->customer->getId(),
					'provider' =>$this->request->post['provider'],
					'amount' =>$this->request->post['amount'],
					'quantity' =>$this->request->post['quantity'],
				);
				$this->model_module_vnptepay->addHistoryTran($datainsert);
				$data['success_mess'] = $sofpin->message;
        	}else{
        		$data['error_mess'] = $sofpin->message;
        	}		

			//$this->response->redirect($this->url->link('common/home'));
			$this->response->setOutput($this->load->view('common/home', $data));
		}

		if (isset($this->error['provider'])) {
			$data['error_provider'] = $this->error['provider'];
		} else {
			$data['error_provider'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		if (isset($this->error['quantity'])) {
			$data['error_quantity'] = $this->error['quantity'];
		} else {
			$data['error_quantity'] = '';
		}


		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['action'] = $this->url->link('common/home', '', true);
        
        $data['vnptepay_value'] = html_entity_decode($this->config->get('vnptepay_ws_url')); 
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}

	private function validate() {		
		if ($this->request->post['provider'] =='0' ) {
			$this->error['provider'] = "Bạn chưa chọn nhà cung cấp";
		}
		if ($this->request->post['amount'] =='0' ) {
			$this->error['amount'] = "Bạn chưa chọn mệnh giá thẻ";
		}
		if ($this->request->post['quantity'] <= 0 ) {
			$this->error['quantity'] = "Số lượng thẻ phải lớn hơn 0";
		}
		if ($this->request->post['password'] == '' ) {
			$this->error['password'] = "Bạn chưa nhập mã quản lý";
		}else if($this->request->post['password'] != html_entity_decode($this->config->get('vnptepay_partner_password')) ){
			$this->error['password'] = "Mã quản lý chưa đúng";
		}
		
		return !$this->error;
	}

	
	private function downloadSofpin($info,$config)
    {
        $client = new SoapClient($config['ws_url']);
        $request_id = $config['request_id'];
        $data = array(
            'requestId' => $request_id,
            'partnerName' => $config['partnerName'],
            'provider' => $info['provider'],
            'amount' => $info['amount'],
            'quantity' => $info['quantity'],
            'sign' => $this->sign($request_id.$config['partnerName'].$info['provider'].$info['amount'].$info['quantity'])
        );
        try{
            $result = $client->__soapCall("downloadSoftpin", $data);
            return $result;
        }catch (Exception $ex){
            return false;
        }
    }
    /*
     * function sinh chữ ký
     * author: Nguyen Tat Loi
     * date: 31/3/2014
     */
    private function sign($data)
    {
        $private_key = file_get_contents("E:/xampp183/htdocs/vnptepay/catalog/controller/common/key/private_key.pem");
      
        //Sign
        openssl_sign($data, $binary_signature, $private_key, OPENSSL_ALGO_SHA1);
        $signature = base64_encode($binary_signature);

        return $signature;

    }

    /*
     * function verify chữ ký, dùng để check chữ ký có đúng ko
     * author: Nguyen Tat Loi
     * date: 11/4/2014
     */

    private function verify_sign($data, $sign)
    {
        $public_key = file_get_contents("E:/xampp183/htdocs/vnptepay/catalog/controller/common/key/public_key.pem");
        $verify = openssl_verify($data, base64_decode($sign), $public_key, OPENSSL_ALGO_SHA1);
        if($verify == 1){
            return true;
        }else{
            return false;
        }
    }
}
