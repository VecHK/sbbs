<?php

$GLOBALS['config'] = require("config.php");

require("model/MySQLPDO.class.php");
require("model/Model.php");
require("model/BoardModel.class.php");
require("model/PostModel.class.php");

if ( isset($_POST['newpost']) ){
	$newPost = require("newpost.php");
	$newPost();
	return 0;
}

if( !isset($_GET['bid']) || !is_numeric($_GET['bid']) ){
	echo "bid似乎有点问题";
	require('index.php');
	return 0;
}else{
	$bid = abs($_GET['bid']);
}

if ( !isset($_GET['page']) || !is_numeric($_GET['page']) ){
	$page = 1;
}else{
	$page = abs($_GET['page']);
}

$board = new BoardModel;

$boardName = $board->getById($bid)['boardname'];


function test(){
	return 'aaa';
}

$start = ($page -1) * $config['pageLimit'];
$end = $start + $config['pageLimit'];
function putPostListHtml(){
	$post = new PostModel;
	$postArr = $post->getPostsByBid($GLOBALS['bid'], $GLOBALS['start'], $GLOBALS['end']);
	if ( $postArr ){

	}else{
		return '暂无帖子';
	}
}
$postListHtml = 'putPostListHtml';

print <<<EOT
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />

	<link href="style/board/global.css" rel="stylesheet" type="text/css" />

	<title>{$config['sbbsName']} - {$boardName}</title>
</head>
<body>
	<a href="index.php">返回首页</a>
	<h1>{$boardName}</h1>
	<hr>
	<ul id="postlist">
		{$postListHtml()}
	</ul>

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
				<button name="newpost" value="post" type="submit">发射</button>
			</li>
		</ul>
	</form>

</body>
</html>
EOT;
?>
