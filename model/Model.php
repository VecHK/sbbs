<?php

class model{
	protected $db;

	public function __construct(){
		$this->initDB();
	}

	private function initDB(){
		$this->db = mySQLPDO::getInstance();
	}
}

?>
