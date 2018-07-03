<?php
/*
 * Class gọi lên webservice của Epay xử lý thanh toán, download mã thẻ,....
 * author: Nguyen Tat Loi
 * date: 31/3/2014
 */
class CDV_Service
{
    private $config;

    /*
     * Hàm khởi tạo
     * author: Nguyen Tat Loi
     * date: 31/3/2014
     */
    public function CDV_Service($config)
    {
        $this->config = $config;
    }

    /*
     * Hàm payment
     * author: Nguyen Tat Loi
     * date: 31/3/2014
     */
    public function paymentCDV($info)
    {
        $client = new SoapClient($this->config['ws_url']);
        $request_id = $this->config['partnerName'].'_'.time().rand(000, 999);
        $time_out = $this->config['time_out'];
        $data = array(
            'requestId' => $request_id,
            'partnerName' => $this->config['partnerName'],
            'provider' => $info['provider'],
            'type' => $info['type'],
            'account' => $info['account'],
            'amount' => $info['amount'],
            'timeOut' => $time_out,
            'sign' => $this->sign($request_id.$this->config['partnerName'].$info['provider'].$info['type'].$info['account'].$info['amount'].$time_out)
        );
		
		
		print_r($data);
		
        try{
            $result = $client->__soapCall("paymentCDV", $data);
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
    public function queryBalance()
    {
        $client = new SoapClient($this->config['ws_url']);
        $data = array(
            'partnerName' => $this->config['partnerName'],
            'sign' => $this->sign($this->config['partnerName'])
        );
        try{
            $result = $client->__soapCall("queryBalance", $data);
            return $result;
        }catch (Exception $ex){
            return false;
        }
    }

    /*
     * function download sofpin
     * author: Nguyen Tat Loi
     * date: 11/4/2014
     */
    public function downloadSofpin($info)
    {
        print_r($info);
        die('123');
        $client = new SoapClient($this->config['ws_url']);
        $request_id = $this->config['partnerName'].'_'.time().rand(000, 999);
        $data = array(
            'requestId' => $request_id,
            'partnerName' => $this->config['partnerName'],
            'provider' => $info['provider'],
            'amount' => $info['amount'],
            'quantity' => $info['quantity'],
            'sign' => $this->sign($request_id.$this->config['partnerName'].$info['provider'].$info['amount'].$info['quantity'])
        );
        try{
            $result = $client->__soapCall("downloadSoftpin", $data);
            return $result;
        }catch (Exception $ex){
            return false;
        }
    }

    /*
     * function topup
     * @param: $provider     //nha cung cap
     *      $account         //tai khoan nhan tien
     *      $amount          //so tien nap
     * author: Nguyen Tat Loi
     * date: 11/4/2014
     */
    public function topup($info)
    {
        $client = new SoapClient($this->config['ws_url']);
        $request_id = $this->config['partnerName'].'_'.time().rand(000, 999);
        $data = array(
            'requestId' => $request_id,
            'partnerName' => $this->config['partnerName'],
            'provider' => $info['provider'],
            'account' => $info['account'],
            'amount' => $info['amount'],
            'sign' => $this->sign($request_id.$this->config['partnerName'].$info['provider'].$info['account'].$info['amount'])
        );
        try{
            $result = $client->__soapCall("topup", $data);
            return $result;
        }catch (Exception $ex){
            return false;
        }
    }

    /*
     * function check Orders CVD
     * @param: $Request_id      //ma giao dich can check
     * author: Nguyen Tat Loi
     * date: 28/5/2014
     */
    public function checkOrdersCVD($request_id = null)
    {
        if(!empty($request_id)){
            $client = new SoapClient($this->config['ws_url']);
            $data = array(
                'requestId' => $request_id,
                'partnerName' => $this->config['partnerName'],
                'sign' => $this->sign($request_id.$this->config['partnerName'])
            );
            try{
                $result = $client->__soapCall("checkOrdersCDV", $data);
                return $result;
            }catch (Exception $ex){
                return false;
            }
        }else{
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
    public function checkTrans($request_id = null, $type = 1)
    {
        if(!empty($request_id)){
            $client = new SoapClient($this->config['ws_url']);
            $data = array(
                'requestId' => $request_id,
                'partnerName' => $this->config['partnerName'],
                'type' => $type,
                'sign' => $this->sign($request_id.$this->config['partnerName'].$type)
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
    public function reDownloadSoftpin($request_id = null)
    {
        if(!empty($request_id)){
            $client = new SoapClient($this->config['ws_url']);
            $data = array(
                'requestId' => $request_id,
                'partnerName' => $this->config['partnerName'],
                'sign' => $this->sign($request_id.$this->config['partnerName'])
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
        $private_key = file_get_contents("key/private_key.pem");
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
        $public_key = file_get_contents("key/public_key.pem");
        $verify = openssl_verify($data, base64_decode($sign), $public_key, OPENSSL_ALGO_SHA1);
        if($verify == 1){
            return true;
        }else{
            return false;
        }
    }

}
?>