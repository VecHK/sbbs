<?php
/*	sbbs v2
	又一个要坑的项目
*/

$GLOBALS['config'] = require("config.php");

require_once("model/MySQLPDO.class.php");
require_once("model/Model.php");

class HomeController{
	public function __construct(){
		$this->setBoardModelInstance();
		$this->getBoard();
	}

	public function setBoardModelInstance(){
		require_once("model/BoardModel.class.php");
		self::$boardModel = new BoardModel;
	}

	private static $boardModel;
	public function getBoard(){
		$this->boardArr = self::$boardModel->getAllBoard();
	}

	public function __destruct(){
		require_once("model/UserModel.class.php");
		require_once("user.php");
		$this->userInfo = userInfo();

		$this->config = require('config.php');

		$this->put();
	}
}

class HomeViewer extends HomeController{
	public function putBoard(){
		$html='';

		foreach( $this->boardArr as $key => $board ){
			$html .= "<li><a href=\"board.php?bid={$board["id"]}\">{$board['boardname']}</a></li>";
		}

		return $html;
	}
	public function put(){
		require('view/home.php');
	}
}

new HomeViewer;

?>
