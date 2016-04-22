<?php
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
?>
