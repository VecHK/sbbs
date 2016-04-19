<?php

require_once("model/UserModel.class.php");

function checkBid($bid){
	$bid = (int)$bid;

	if ( !$bid ){
		return false;
	}
	return (new BoardModel)->getById($bid);
}
function checkContentType($type){
	return count( array_keys($GLOBALS['config']['allowType'], $type, true) );
}

return function (){
	header("Content-Type: text/html; charset=utf-8");

	$postInfo = array();

	$boardInfo = checkBid($_POST['newpost']);
	$boardInfo || die("bid错误");

	checkContentType($_POST['type']) || die('type错误');

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

?>
