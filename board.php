<?php

$GLOBALS['config'] = require("config.php");

require("model/MySQLPDO.class.php");
require("model/Model.php");
require("model/BoardModel.class.php");
require("model/PostModel.class.php");
require("model/UserModel.class.php");

class RequestRouter{
	public $bid;
	public $page;

	public function __construct(){
		$this->requestRouter();
	}

	public function __destruct(){
		require_once("user.php");
		$this->userInfo = userInfo();
		$this->config = $GLOBALS['config'];

		/* BoardViewer::put */
		$this->put();
	}

	private function processGET(){
		$this->setBoardModelInstance();
		$this->setBoardName();
	}

	private function GET(){
		/* 检查不到bid或者bid不是数字，跳转 index.php */
		( isset($_GET['bid']) && is_numeric($_GET['bid']) ) || die( header("location: index.php") );

		$this->bid = abs($_GET['bid']);

		/* 检查不到page或者page不是数字，跳转 board.php?bid={$_GET['bid']}&page=1 */
		( isset($_GET['page']) && is_numeric($_GET['page']) ) || die( header("location: board.php?bid={$_GET['bid']}&page=1") );

		$this->page = abs($_GET['page']);

		$this->processGET();
	}

	private function POST(){
		if ( isset($_POST['newpost']) ){
			$this->newPost();
			die();
		}
	}

	private function other(){
		die('Other Request Method.');
	}

	public function requestRouter(){
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

class BoardController extends RequestRouter{
	private function checkBid($bid){
		$bid = (int)$bid;

		if ( !$bid ){
			return false;
		}
		return (new BoardModel)->getById($bid);
	}

	private function checkContentType($type){
		return count( array_keys($GLOBALS['config']['allowType'], $type, true) );
	}

	public function newPost(){
		header("Content-Type: text/html; charset=utf-8");

		$postInfo = array();

		$boardInfo = $this->checkBid($_POST['newpost']);
		$boardInfo || die("bid错误");

		$this->checkContentType($_POST['type']) || die('type错误');

		isset($_SESSION) || session_start();

		$postInfo['bid'] = (int) $_POST['newpost'];
		$postInfo['uid'] = $_SESSION['uid'];
		$postInfo['title'] = $_POST['title'];
		$postInfo['type'] = $_POST['type'];

		$postInfo['content'] = $_POST['content'];

		$postInfo['format'] = $_POST['content'];

		$postInfo['userInfo'] = (new UserModel)->getById($_SESSION['uid']);

		$pModel = new PostModel;

		$result = $pModel->createPost($postInfo);

		header("location: get.php?id={$result['postItem']['id']}");
		die('ok?');
		return $result;
	}


	public $boardName;

	private static $boardModel;
	public function setBoardModelInstance(){
		self::$boardModel = new BoardModel;
	}
	public function setBoardName(){
		$this->boardName = self::$boardModel->getById($this->bid)['boardname'];
	}

}

class BoardViewer extends BoardController{
	private static function outItemHtml($item){
		$html = "<li>
		<h3><a href=\"get.php?id={$item['id']}\">{$item['title']}</a></h3>
		</li>";
		return $html;
	}

	public function putPostListHtml(){
		$this->page = $_GET['page'];//( isset($_GET['page']) && is_numeric($_GET['page']) ) ? abs($_GET['page']) : 1;

		$this->start = ($this->page -1) * $GLOBALS['config']['pageLimit'];
		$this->end = $this->start + $GLOBALS['config']['pageLimit'];

		$postArr = (new PostModel)->getPostsIndexByBid($this->bid, $this->start, $this->end);

		$html = '';
		if ( $postArr ){
			foreach( $postArr as $item ){
				$html .= self::outItemHtml($item);
			}
		}else{
			$html = '暂无帖子';
		}
		return $html;
	}

	public function put(){
		require('view/board.php');
	}
}

new BoardViewer;
?>
