<?php
print <<<EOT
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />
	<link href="style/global.css" rel="stylesheet" />
	<title>{$this->config['sbbsName']}</title>
</head>
<body>
	<header>{$this->userInfo}</header>
	<div>
		<h1>{$this->config['sbbsTitle']}</h1>
		<hr>
		<nav id="board">
			{$this->putBoard()}
		</nav>
	</div>
	<footer>Hey, sbbs</footer>
</body>
</html>
EOT;
?>
