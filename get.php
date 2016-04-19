<?php

$GLOBALS['config'] = require("config.php");

require("model/MySQLPDO.class.php");
require("model/Model.php");
require("model/BoardModel.class.php");
require("model/PostModel.class.php");
require("model/UserModel.class.php");


require_once("user.php");

class GetController{
	public $pid;

	public $page;

	public $title;

	public $content;

	private static function diee($headerStr){
		//print($headerStr);
		die(header($headerStr));
	}

	private function processGET(){
		( isset($_GET['id']) && is_numeric($_GET['id']) ) || self::$die('location: index.php');

		$this->page = ( isset($_GET['page']) && is_numeric($_GET['page']) ) ? abs( (int)$_GET['page'] ) : 1;

		$this->pid = abs( (int)$_GET['id'] );
	}

	private static $postModel;
	public $pool;
	private function getPool(){
		$start = ($this->page -1) * $GLOBALS['config']['pageLimit'];
		$end = $start + $GLOBALS['config']['pageLimit'];

		$this->pool = self::$postModel->getPostById( $this->pid, $start, $end );

		$this->pool || die("啊偶，这个帖子好像消失了");
	}

	public static $userModel;
	public $userNameList;
	private function setUser(){
		$idArr = array();
		foreach($this->pool as $item){
			array_push($idArr, $item['uid']);
		}

		$this->userNameList = self::$userModel->getNameArrById( $idArr );
	}

	public $postInfo;
	private function getPostIndex(){
		$this->postInfo = self::$postModel->getPostIndexById($this->pid);
		$this->postInfo || die("啊偶，这个帖子的Index似乎出了点问题");
	}

	public $boardName;
	private static $boardModel;
	private function setBoardName(){
		$data = self::$boardModel->getById($this->postInfo['bid']);

		$data || die("这个帖子似乎被遗忘了……（GetController::setBoardName: bid not found.");

		$this->boardName = $data['boardname'];
	}

	public function __construct(){
		$this->processGET();

		self::$postModel = new PostModel;

		$this->getPool();
		$this->getPostIndex();

		self::$userModel = new UserModel;
		$this->setUser();


		self::$boardModel = new BoardModel;
		$this->setBoardName();
	}

	private function __clone(){}
}
class GetViewer extends GetController{
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
		print <<<EOT
		<!DOCTYPE HTML>
		<html>
		<head>
			<meta http-equiv="content-type" content="text/html" charset="utf-8" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
			<meta name="HandheldFriendly" content="true" />

			<link href="style/global.css" rel="stylesheet" type="text/css" />
			<link href="style/board/global.css" rel="stylesheet" type="text/css" />

			<title>{$this->postInfo['title']} - {$this->config['sbbsName']}</title>
		</head>
		<body>
			<header>{$this->userInfo}</header>
			<div>
				<nav>{$this->nav()}</nav>
				<hr>
				<h3>{$this->postInfo['title']}</h3>
				<ul>{$this->fetchPost()}</ul>
				<form id="posteditor" method="post" >
					<div id="textinput">
						<textarea name="content" placeholder="你的发言" ></textarea>
						<article id="preview"></article>
					</div>
					<ul id="editor-menu">
						<li>
							<label>
								格式
								<select name="type">
									<option value="text">Text</option>
									<option value="markdown">Markdown</option>
									<option value="html">HTML</option>
									<option value="bbcode">BBCode</option>
								</select>
							</label>
						</li>
						<li><button id="eidtor-preview">预览</button></li>

						<li>
							<button name="repost" value="{$this->pid}" type="submit">发射</button>
						</li>
					</ul>
				</form>

			</div>
			<footer>Hey, sbbs</footer>
		</body>
		</html>
EOT;
	}

	function __destruct(){
		$this->userInfo = userInfo();
		$this->config = $GLOBALS['config'];
		$this->put();
	}
}

$post = new GetViewer;
?>
