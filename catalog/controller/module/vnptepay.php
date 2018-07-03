<?php
class ControllerModuleVnptepay extends Controller {
    private $error = array();
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
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
                
                $tmpval= array();
                foreach ($listcard as $key => $value) {
                    $tmpval[$key+1] = explode("|", $value);
                    array_push($tmpval[$key+1],$this->request->post['note']);
                }

                $data['listcart'] = $tmpval;
                
                // print_r($data['listcart']);
                // die();
                $this->load->model('module/vnptepay');
                $datainsert = "";
                $datainsert = array(
                    'request_id' => $config['request_id'], 
                    'listcards' => $cleartext, 
                    'customer_id' => $this->customer->getId(),
                    'provider' =>$this->request->post['provider'],
                    'amount' =>$this->request->post['amount'],
                    'quantity' =>$this->request->post['quantity'],
                    'note' => $this->request->post['note']
                );
                $this->model_module_vnptepay->addHistoryTran($datainsert);
                $data['success_mess'] = $sofpin->message;
            }else{
                $data['error_mess'] = $sofpin->message;
            }       

            //$this->response->redirect($this->url->link('common/home'));
            $this->response->setOutput($this->load->view('module/vnptepay', $data));
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
        if (isset($this->error['note'])) {
            $data['error_note'] = $this->error['note'];
        } else {
            $data['error_note'] = '';
        }

        $data['action'] = $this->url->link('module/vnptepay', '', true);
        
        $data['vnptepay_value'] = html_entity_decode($this->config->get('vnptepay_ws_url')); 
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('module/vnptepay', $data));
    }
    public function historytransaction(){
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $url = '';
        
        $limit = 10;
        $this->load->model('module/vnptepay');
        $total = $this->model_module_vnptepay->getTotalhistoryTran();
        
        if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $filter_data = array(
                'datefrom'=>$this->request->post['datefrom'],
                'dateto'=>$this->request->post['dateto']
            );
            
            $data['datefrom'] = $this->request->post['datefrom'];
            $data['dateto'] = $this->request->post['dateto'];
            $listhistory = $this->model_module_vnptepay->getsearchhistoryTran($filter_data);
        }else{
            $filter_data = array(
                'start' => ($page - 1) * $limit,
                'limit' => $limit,
            );
            $listhistory = $this->model_module_vnptepay->gethistoryTran($filter_data);
            $pagination = new Pagination();
            $pagination->total = $total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('module/vnptepay/historytransaction', $url ."&page={page}");

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));
            
            if ($page == 1) {
                $this->document->addLink($this->url->link('module/vnptepay/historytransaction', '', true), 'canonical');
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('module/vnptepay/historytransaction', '', true), 'prev');
            } else {
                $this->document->addLink($this->url->link('module/vnptepay/historytransaction', 'page='. ($page - 1), true), 'prev');
            }
        }

        foreach ($listhistory as $history) {         
            $data['historys'][] = array(
                'id'        => $history['id'],
                'request_id'=> $history['request_id'],
                'listcards' => $history['listcards'],
                'customer_id'=>$history['customer_id'],
                'provider'  => $history['provider'],
                'amount' => $history['amount'],
                'quantity' => $history['quantity'],
                'note' => $history['note'],
                'date_added' => $history['date_added'],
                'error_code' => $history['error_code']
            );  
        }

        $data['action'] = $this->url->link('module/vnptepay/historytransaction', '', true);
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('module/historytransaction', $data));
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
        if (!isset($this->request->post['password']) || $this->request->post['password'] == '' ) {
            $this->error['password'] = "Bạn chưa nhập mã quản lý";
        }else if($this->request->post['password'] != html_entity_decode($this->config->get('vnptepay_partner_password')) ){
            $this->error['password'] = "Mã quản lý chưa đúng";
        }
        if (!isset($this->request->post['note']) || $this->request->post['note'] == '' ) {
            $this->error['note'] = "Bạn chưa nhập ghi chú";
        }
        
        return !$this->error;
    }

    public function balance() {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        if (isset($this->request->get['route'])) {
            $this->document->addLink($this->config->get('config_url'), 'canonical');
        }

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
            'time_out' => html_entity_decode($this->config->get('vnptepay_time_out'))           
        );
         $data['error_mess'] = '';

        $balance = $this->queryBalance($config);
        $data['balance_detail'] = '';
        if($balance->errorCode == 0){
            $data['balance_detail'] =  $balance;
        }else{
            $data['error_mess'] = $balance->message;
        }
       
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('module/balance', $data));
    }

    // public function redownloadpin(){
    //     if (!$this->customer->isLogged()) {
    //         $this->response->redirect($this->url->link('account/login', '', true));
    //     }
    //     $this->document->setTitle($this->config->get('config_meta_title'));
    //     $this->document->setDescription($this->config->get('config_meta_description'));
    //     $this->document->setKeywords($this->config->get('config_meta_keyword'));

    //     if (isset($this->request->get['route'])) {
    //         $this->document->addLink($this->config->get('config_url'), 'canonical');
    //     }

    //     $data['listcart'] = '';
    //     $data['success_mess'] = '';
    //     $data['error_mess'] = '';
    // if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateredowload()) {
    //     $config = array(
    //         //link webservice
    //         'ws_url' => html_entity_decode($this->config->get('vnptepay_ws_url')),
    //         //partner username
    //         'partnerName' => html_entity_decode($this->config->get('vnptepay_partner_name')),
    //         //partner password
    //         'partnerPassword' => html_entity_decode($this->config->get('vnptepay_partner_password')),
    //         //key sofpin
    //         'key_sofpin' => html_entity_decode($this->config->get('vnptepay_key_sofpin')),
    //         //thời gian tối đa thực hiện giao dịch (tính bằng giây)
    //         'time_out' => html_entity_decode($this->config->get('vnptepay_time_out'))              
    //     );
    //     // print_r($config);

    //     $sofpin = $this->reDownloadSoftpin($this->request->post['request_id'],$config);
    //     // echo "<pre>";
    //     // print_r($sofpin);
    //     // echo "</pre>";
    //     if($sofpin->errorCode == "0"){
    //         $key = substr(md5($config['key_sofpin']), 0, 24);
    //         $cleartext = mcrypt_decrypt("tripledes", $key, base64_decode($sofpin->listCards), "ecb", "\0\0\0\0\0\0\0\0");
    //         //echo "Sofpin decrypt: ". $cleartext . "<br>";         
            
    //         $cleartext = substr($cleartext, 0, stripos($cleartext,"]}") + 2);
    //         $listcard = json_decode($cleartext)->listCards;
        
    //         //echo "Sofpin decrypt: ". $cleartext . "<br>";
    //         // var_dump(json_decode($cleartext));
    //         $tmpval = array();
    //         foreach ($listcard as $key => $value) {
    //             $tmpval[$key] = explode("|", $value);
    //         }
    //             $data['listcart'] = $tmpval;                
    //         }else{
    //             $data['error_mess'] = $sofpin->message;
    //         }       

    //         //$this->response->redirect($this->url->link('common/home'));
    //         $this->response->setOutput($this->load->view('module/redownloadpin', $data));
    //     }

    //     if (isset($this->error['request_id'])) {
    //         $data['error_request_id'] = $this->error['request_id'];
    //     } else {
    //         $data['error_request_id'] = '';
    //     }

    //     if (isset($this->error['password'])) {
    //         $data['error_password'] = $this->error['password'];
    //     } else {
    //         $data['error_password'] = '';
    //     }

    //     $data['action'] = $this->url->link('module/vnptepay/redownloadpin', '', true);
        
    //     $data['footer'] = $this->load->controller('common/footer');
    //     $data['header'] = $this->load->controller('common/header');

    //     $this->response->setOutput($this->load->view('module/redownloadpin', $data));
    // }

    public function redownloadpin(){
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        if (isset($this->request->get['route'])) {
            $this->document->addLink($this->config->get('config_url'), 'canonical');
        }

        $data['listdata'] = '';
        $data['success_mess'] = '';
        $data['error_mess'] = '';
        $data['listcards'] = '';
        if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $request_id=$this->request->post['request_id'];
            $this->load->model('module/vnptepay');
            $listdata = $this->model_module_vnptepay->getrequestid($request_id); 
            $tmpval = array();
            foreach ($listdata as $value) {
                $error_code=$value['error_code'];
                $note=$value['note'];
                $date_added=$value['date_added'];

                $listcards = json_decode($value['listcards'],true);
                if($listcards!=''){
                    foreach ($listcards as $values) {
                        foreach ($values as $key => $value) {
                            $tmpval[$key] = explode("|", $value);
                            array_push($tmpval[$key],$error_code,$note,$date_added);
                        }
                    }
                }  
            }
            $data['listdata'] =$tmpval;
        }
            if (isset($this->error['request_id'])) {
                $data['error_request_id'] = $this->error['request_id'];
            } else {
                $data['error_request_id'] = '';
            }    
        
        

        $data['action'] = $this->url->link('module/vnptepay/redownloadpin', '', true);
        
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('module/redownloadpin', $data));
    }


    public function checktransaction(){
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        if (isset($this->request->get['route'])) {
            $this->document->addLink($this->config->get('config_url'), 'canonical');
        }

        $data['listcart'] = '';
        $data['success_mess'] = '';
        $data['error_mess'] = '';
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateredowload()) {
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
                'time_out' => html_entity_decode($this->config->get('vnptepay_time_out'))              
            );
            // print_r($config);

            $sofpin = $this->checkTrans($this->request->post['request_id'],2,$config);
            // echo "<pre>";
            // print_r($sofpin);
            // echo "</pre>";
            if($sofpin->errorCode == "0"){               
               $data['success_mess'] = $sofpin->message;            
            }else{
                $data['error_mess'] = $sofpin->message;
            }       

            //$this->response->redirect($this->url->link('common/home'));
            $this->response->setOutput($this->load->view('module/checktrans', $data));
        }

        if (isset($this->error['request_id'])) {
            $data['error_request_id'] = $this->error['request_id'];
        } else {
            $data['error_request_id'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        $data['action'] = $this->url->link('module/vnptepay/checktransaction', '', true);
        
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('module/checktrans', $data));
    }

    private function validateredowload() {       
        if ($this->request->post['request_id'] == '' ) {
            $this->error['request_id'] = "Bạn chưa nhập mã giao dịch";
        }
        if (!isset($this->request->post['password']) || $this->request->post['password'] == '' ) {
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
     * function query balance
     * get balance of partner with partner id in config
     * author: Nguyen Tat Loi
     * date: 11/4/2014
     */
    public function queryBalance($config)
    {
        $client = new SoapClient($config['ws_url']);
        $data = array(
            'partnerName' => $config['partnerName'],
            'sign' => $this->sign($config['partnerName'])
        );
        try{
            $result = $client->__soapCall("queryBalance", $data);
            return $result;
        }catch (Exception $ex){
            return false;
        }
    }

    /*
     * function check transaction
     * @params: $request_id         //ma giao dich can check
     *          $type               //loai giao dich can check
     * author: Nguyen Tat Loi
     * date: 28/5/2014
     */
    public function checkTrans($request_id = null, $type = 2, $config)
    {
        if(!empty($request_id)){
            $client = new SoapClient($config['ws_url']);
            $data = array(
                'requestId' => $request_id,
                'partnerName' => $config['partnerName'],
                'type' => $type,
                'sign' => $this->sign($request_id.$config['partnerName'].$type)
            );
            try{
                $result = $client->__soapCall("checkTrans", $data);
                return $result;
            }catch (Exception $ex){
                return false;
            }
        }else{
            return false;
        }
    }


    /*
     * function redowload sofpin
     * author: Nguyen Tat Loi
     * date: 28/5/2014
     */
    public function reDownloadSoftpin($request_id = null, $config)
    {
        if(!empty($request_id)){
            $client = new SoapClient($config['ws_url']);
            $data = array(
                'requestId' => $request_id,
                'partnerName' => $config['partnerName'],
                'sign' => $this->sign($request_id.$config['partnerName'])
            );
            try{
                $result = $client->__soapCall("reDownloadSoftpin", $data);
                return $result;
            }catch (Exception $ex){
                return false;
            }
        }else{
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
        $private_key = file_get_contents("/usr/share/nginx/www/vnptepay/catalog/controller/common/key/private_key.pem");
      
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
        $public_key = file_get_contents("/usr/share/nginx/www/vnptepay/catalog/controller/common/key/public_key.pem");
        $verify = openssl_verify($data, base64_decode($sign), $public_key, OPENSSL_ALGO_SHA1);
        if($verify == 1){
            return true;
        }else{
            return false;
        }
    }
}