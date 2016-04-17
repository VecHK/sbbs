<?php
/*	sbbs v2
	又一个要坑的项目
*/

$GLOBALS['config'] = require("config.php");

require_once("model/MySQLPDO.class.php");
require_once("model/Model.php");
require_once("model/BoardModel.class.php");

require_once("model/UserModel.class.php");

require_once("user.php");

$board = new BoardModel;

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />
	<title><?php echo $config["sbbsName"]; ?></title>
</head>
<body>
	<header><?php echo userInfo(); ?></header>
	<h1><?php echo $config["sbbsTitle"]; ?></h1>
	<hr>

	<h1>板块</h1>
	<nav id="board">
		<?php
		$boardArr = $board->getAllBoard();
		foreach( $boardArr as $key => $board ){
			echo "<li><a href=\"board.php?bid={$board["id"]}\">{$board['boardname']}</a></li>";
		}
		?>
	</nav>
	<ul id="postlist">

	</ul>

	<footer>有的人装逼，他已经死了；有的人装逼，他还活着。</footer>
</body>
</html>
