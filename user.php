<?php

require('permission.php');

function checkUidAndPw($uid, $pw){
	return is_numeric($uid) && is_string($pw) && (strlen($pw) === 32);
}
function isLogin(){
	return isset($_SESSION['islogin']);
}
function needLogin($infoStr = ""){
	$infoStr = "需要登录后才能访问";
	require('login.php');
	die();
}
function setUserSession($uid, $pw){
	$_SESSION['uid'] = $uid;
	$_SESSION['pw'] = $pw;
	$_SESSION['islogin'] = true;
}
function clearUserSession(){
	$_SESSION['uid'] = '';
	$_SESSION['pw'] = '';
	$_SESSION['islogin'] = false;
}
function authenticate($username, $pw){
	$usermodel = new UserModel;
	if ( is_numeric($username) ){
		$result = $usermodel->getById($username);
	}else{
		$result = $usermodel->getByUserName( $username );
		$result || ($result = $usermodel->getByEmail( $pw ));
	}
	if ( $result && ($result['pw'] === $pw) ){
		$GLOBALS['UserInfo'] = array(
			'username' => $result['username'],
			'nickname' => $result['nickname']
		);
		return $result['id'];
	}
}

function userInfo(){
	$loginStateHTML = "";

	session_start();

	/* 检查cookie，确认登录 */
	if ( isset($_COOKIE['uid'], $_COOKIE['pw']) && checkUidAndPw($_COOKIE['uid'], $_COOKIE['pw']) && authenticate($_COOKIE['uid'], $_COOKIE['pw']) ){
		setUserSession($_COOKIE['uid'], $_COOKIE['pw']);
	}
	/* 检查Session */
	else if ( isset($_SESSION['islogin'], $_SESSION['uid'], $_SESSION['pw']) && checkUidAndPw($_SESSION['uid'], $_SESSION['pw']) ){
		authenticate($_SESSION['uid'], $_SESSION['pw']);
	}
	else{
		setcookie('uid', '', time()-1, $GLOBALS['config']['root']);
		setcookie('pw', '', time()-1, $GLOBALS['config']['root']);

		if ( !$GLOBALS['config']['accessGuest'] ){
			needLogin("COOKIE或许出了点问题，请重新登录");
		}

	}
	if ( isLogin() ){
		$loginStateHTML = "欢迎，<a href=\"user.php?uid={$_SESSION['uid']}\">{$GLOBALS['UserInfo']['username']}</a>";
		$htmlStr = "<div>{$loginStateHTML}</div><div><a href=\"login.php?unlogin=true\">注销</a></div>";
	}else{
		$loginStateHTML = "您还未<a href=\"login.php\">登录</a>";
	}


	return $htmlStr;
}

?>
