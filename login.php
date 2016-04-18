<?php
if ( !isset($GLOBALS['config']) ){
	$GLOBALS['config'] = require('config.php');
}
require_once("model/MySQLPDO.class.php");
require_once("model/Model.php");
require_once("model/UserModel.class.php");
require_once("user.php");

function randomChar($isUpper=false){
	return chr( rand(0,25) + (rand(0, (int)$isUpper) ? 65 : 97) );
}

function randomString($length, $isUpper=false){
	return randomChar($isUpper) . ($length ? randomString(--$length, $isUpper) : '');
}

if ( !isset($_GET['info']) ){
	$infoStr = "帐号登录";
}else{
	$infoStr = $_GET['info'];
}

function checkUsernameAndPw($username, $pw){
	return (strlen($pw) === 32);
}
session_start();
if ( isset($_POST['login']) ){
	if ( isset($_POST['username'], $_POST['pw']) && checkUsernameAndPw($_POST['username'], $_POST['pw']) ){
		if ( $uid=authenticate($_POST['username'], $_POST['pw'], $_POST['login']) ){
			header("Content-Type: text/html; charset=utf-8");
			//header("refresh: 3; url=index.php");
			//header("location:index.php");

			if ( isset($_POST['keepweek']) && ($_POST['keepweek'] == 'true') ){
				setcookie('uid', $result['id'], time()+7*24*60*60);
				setcookie('pw', $_POST['pw'], time()+7*24*60*60);
			}else{
				setUserSession($uid, $_POST['pw']);
			}

			die('<p>登录成功，正在跳转……</p><p>或者<a href="index.php">点我</a></p>');
		}
	}
	needLogin("用户/密码 错误");
}else if ( !isLogin() ){
	$_SESSION['auth'] = randomString(16, true);
}

if ( isset($_GET['unlogin']) ){
	clearUserSession();
	setcookie('uid', '', time()-1);
	setcookie('pw', '', time()-1);
	session_destroy();
	header("location:login.php");
	die("");
}

$backHash = 'backHash';
function backHash(){
	return (new UserModel)->getById();
	return hashStr(randomString(8, true));
}


print <<<EOT
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />
	<title>{$GLOBALS['config']['sbbsName']} - 登录</title>
</head>
<body>
<div><i><b>{$infoStr}</b></i></div>
<form method="post">
<fieldset>
	<label>用户名:
		<input placeholder="E-Mail / 用户名" name="username" />
	</label>
	<br />
	<br />
	<label>密码:
		<input type="password" name="pw" />
	</label>
	<br />
	<br />
	<label>
		<input type="checkbox" name="keepweek" value="true" />
		七天内自动登录
	</label>
	<br />
	<br />
	<label>
		<button type="submit" name="login" value="{$_SESSION['auth']}">登录</button>
	</label>
</fieldset>
</form>
<script type="text/javascript" src="extends/md5/md5.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</body>
</html>
EOT;

?>
