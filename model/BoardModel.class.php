<?php

class BoardModel extends model{
	public function getAllBoard(){
		$data = $this->db->fetchAll("SELECT * FROM `sbbs_board_list` ORDER BY id");
		return $data;
	}

	public function getById($bid){
		$bid = (int) $bid;
		$data = $this->db->fetchRow("SELECT * FROM `sbbs_board_list` WHERE id = {$bid}");
		return $data;
	}
}

?>
