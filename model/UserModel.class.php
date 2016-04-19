<?php

class UserModel extends model{
	public function getUsers($start, $end){

	}

	public function getAll(){
		$data = $this->db->fetchAll("SELECT * FROM `sbbs_user`");
		return $data;
	}

	public function getByIds($idArr){
		$data = $this->db->fetchAll("SELECT * FROM `sbbs_user` WHERE `id` IN (". implode(', ', $idArr) .")");
	}

	public function getNameArrById($idArr){
		$data = $this->db->fetchColumn("SELECT * FROM `sbbs_user` WHERE `id` IN (". implode(', ', $idArr) .")");
		return $data;
	}

	public function getById($uid){
		$data = $this->db->fetchRow("SELECT * FROM `sbbs_user` WHERE id = {$uid}");
		return $data;
	}

	public function getByEmail($email){
		$data = $this->db->fetchRow("SELECT * FROM `sbbs_user` WHERE email = \"{$email}\"");
		return $data;
	}

	public function getByUserName($username){
		$data = $this->db->fetchRow("SELECT * FROM `sbbs_user` WHERE username = \"{$username}\"");
		return $data;
	}

	public function updateCustomColById($customCol, $setValue, $uid){
		$sql = "UPDATE `sbbs_user` set {$customCol} = '{$setValue}' WHERE id = {$uid}";
		return $this->db->exec($sql);
	}

	public function newUser(){

	}
}

?>
