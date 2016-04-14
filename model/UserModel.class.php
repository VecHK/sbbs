<?php

class userModel extends model{
	public function getUsers($start, $end){

	}

	public function getAll(){
		$data = $this->db->fetchAll("SELECT * FROM `sbbs_user`");
		return $data;
	}

	public function getById($uid){
		$data = $this->db->fetchRow("SELECT * FROM `sbbs_user` WHERE uid = {$uid}");
		return $data;
	}

	public function newUser(){

	}
}

?>
