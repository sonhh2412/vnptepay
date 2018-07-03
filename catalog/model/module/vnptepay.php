<?php
class ModelModuleVnptepay extends Model {
	public function addHistoryTran($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "vnptepay_history` SET `request_id` = '" . $data['request_id'] . "', `listcards` = '" . $data['listcards'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `provider` = '" . $data['provider'] . "', `amount` = '" . $data['amount'] . "', `quantity` = '" . (int)$data['quantity'] . "', `note` = '" . $data['note'] . "',  `date_added` = NOW()");
	}

	public function gethistoryTran($data = array()) {	

		$sql = "SELECT * FROM `" . DB_PREFIX . "vnptepay_history` ORDER BY `date_added` DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);     
		return $query->rows;
	}

	public function getsearchhistoryTran($data = array()) {	
		$sql = "SELECT * FROM `" . DB_PREFIX . "vnptepay_history`";
		if(isset($data['datefrom']) && isset($data['dateto'])){
			$sql.= "where `date_added`>='".$data['datefrom']." 00:00:00' AND `date_added`<='".$data['dateto']."23:59:59'";
		}
		$sql .=" ORDER BY `date_added` DESC";
		$query = $this->db->query($sql);     
		return $query->rows;
	}

	public function getTotalhistoryTran($data = array()) {
		$sql = "SELECT count(*) as total FROM `" . DB_PREFIX . "vnptepay_history`";

		$query = $this->db->query($sql);

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getrequestid($request_id) {	
		$sql = "SELECT * FROM `" . DB_PREFIX . "vnptepay_history` where request_id = '".$request_id."' ORDER BY `date_added` DESC";
		 $query = $this->db->query($sql);
		return $query->rows;
	}
}
