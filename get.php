<?php

$GLOBALS['config'] = require("config.php");

require("model/MySQLPDO.class.php");
require("model/Model.php");
require("model/BoardModel.class.php");
require("model/PostModel.class.php");
require("model/UserModel.class.php");


require_once("user.php");

class RequestRouter{
	public $pid;
	public $page;

	/* 入口 */
	public function __construct(){
		$this->requestRouter();
	}

	private function processGET(){
		$this->setPostModelInstance();
		$this->getPool();
		$this->getPostIndex();

		$this->setUserModelInstance();
		$this->setUser();

		$this->setBoardModelInstance();
		$this->getBoardName();
	}
	private function GET(){
		( isset($_GET['id']) && is_numeric($_GET['id']) ) || self::jump('location: index.php');

		$this->page = ( isset($_GET['page']) && is_numeric($_GET['page']) ) ? abs( (int)$_GET['page'] ) : 1;

		$this->pid = abs( (int)$_GET['id'] );

		$this->processGET();
	}

	private function POST(){
		if ( isset($_POST['repost']) ){
			isset($_POST['repost']) || $this->jump("location: get.php?id={$_GET['id']}");
		}
	}

	private function other(){
		die('Other Request Method.');
	}

	/* 处理请求的路由（POST和GET） */
	private function requestRouter(){
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			$this->POST();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			$this->GET();
		}else{
			$this->other();
		}
		return $_SERVER['REQUEST_METHOD'];
	}
}

class GetController extends RequestRouter{
	/* 设置header并结束程序 */
	private static function jump($headerStr){
		//print($headerStr);
		die(header($headerStr));
	}

	/* 获得帖子 */
	private static $postModel;
	public $pool;
	public function setPostModelInstance(){
		self::$postModel = new PostModel;
	}
	public function getPool(){
		$start = ($this->page -1) * $GLOBALS['config']['pageLimit'];
		$end = $start + $GLOBALS['config']['pageLimit'];

		$this->pool = self::$postModel->getPostById( $this->pid, $start, $end );

		$this->pool || die("啊偶，这个帖子好像消失了");
	}

	/* 获得当前页面显示的用户名（是一个数组） */
	public static $userModel;
	public $userNameList;
	public function setUserModelInstance(){
		self::$userModel = new UserModel;
	}
	public function setUser(){
		$idArr = array();
		foreach($this->pool as $item){
			array_push($idArr, $item['uid']);
		}
		$this->userNameList = self::$userModel->getNameArrById( $idArr );
	}

	/* 获得帖子在 `sbbs_postlist` 的信息 */
	public $postInfo;
	public function getPostIndex(){
		$this->postInfo = self::$postModel->getPostIndexById($this->pid);
		$this->postInfo || die("啊偶，这个帖子的Index似乎出了点问题");
	}

	/**
	* 获得当前帖子所在板块的名字
	*/
	public $boardName;
	private static $boardModel;
	public function setBoardModelInstance(){
		self::$boardModel = new BoardModel;
	}
	public function getBoardName(){
		$data = self::$boardModel->getById($this->postInfo['bid']);

		$data || die("这个帖子似乎被遗忘了……（GetController::getBoardName: bid not found.");

		$this->boardName = $data['boardname'];
	}

	private function __clone(){}
}

class GetViewer extends GetController{
	/* 返回位置导航条的HTML */
	public function nav(){
		return "当前位置 <a href=\"index.php\">主页</a> -> <a href=\"board.php?bid={$this->postInfo['bid']}\">{$this->boardName}</a>";
	}

	private function fetchPost(){
		$html = '';
		$c = count($this->pool);
		for ( $i=0; $i<$c; ++$i){
			$post = $this->pool[$i];
			$html .= '<li>';
			$html .= "<header>#{$post['floor']}</header>";
			$html .= "<div class=\"content\">{$post['content']}</div>";
			$html .= "<footer class=\"itemfooter\">
				作者: <auther>{$this->userNameList[$i]}</auther>
				时间：<date>{$post['time']}</date>

			</footer>";
			$html .= '</li>';
		}
		return $html;
	}
	private function put(){
		require('view/get.php');
	}

	function __destruct(){
		$this->userInfo = userInfo();
		$this->config = $GLOBALS['config'];
		$this->put();
	}
}

$post = new GetViewer;
?>
