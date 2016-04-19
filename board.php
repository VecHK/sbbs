<?php

$GLOBALS['config'] = require("config.php");

require('user.php');

require("model/MySQLPDO.class.php");
require("model/Model.php");
require("model/BoardModel.class.php");
require("model/PostModel.class.php");
require("model/UserModel.class.php");
require_once("user.php");

if ( isset($_POST['newpost']) ){
	$newPost = require("newpost.php");
	$newPost();
	return 0;
}

/* 检查不到bid或者bid不是数字，跳转 index.php */
( isset($_GET['bid']) && is_numeric($_GET['bid']) ) || die( header("location: index.php") );

$bid = abs($_GET['bid']);

/* 检查不到page或者page不是数字，跳转 board.php?bid={$_GET['bid']}&page=1 */
( isset($_GET['page']) && is_numeric($_GET['page']) ) || die( header("location: board.php?bid={$_GET['bid']}&page=1") );

$board = new BoardModel;

$boardName = $board->getById($bid)['boardname'];

function outItemHtml($item){
	$html = "<li>
	<h3><a href=\"get.php?id={$item['id']}\">{$item['title']}</a></h3>
	</li>";
	return $html;
}

$postListHtml = 'putPostListHtml';
function putPostListHtml(){
	$page = $_GET['page'];//( isset($_GET['page']) && is_numeric($_GET['page']) ) ? abs($_GET['page']) : 1;

	$start = ($page -1) * $GLOBALS['config']['pageLimit'];
	$end = $start + $GLOBALS['config']['pageLimit'];

	$postArr = (new PostModel)->getPostsByBid($GLOBALS['bid'], $start, $end);

	$html = '';
	if ( $postArr ){
		foreach( $postArr as $item ){
			$html .= outItemHtml($item);
		}
	}else{
		$html = '暂无帖子';
	}
	return $html;
}

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

	<title>{$config['sbbsName']} - {$boardName}</title>
</head>
<body>
	<header>{$userInfo()}</header>
	<div>
		<a href="index.php">返回首页</a>
		<h1>{$boardName}</h1>
		<hr>
		<ul id="postlist">
			{$postListHtml()}
		</ul>
		<hr>
		<form id="posteditor" method="post" >
			<div id="textinput">
				<input name="title" type="text" placeholder="你的标题" />
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
					<button name="newpost" value="{$bid}" type="submit">发射</button>
				</li>
			</ul>
		</form>
	</div>

	<footer>Hey, sbbs</footer>

</body>
</html>
EOT;
?>
