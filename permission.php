<?php
/*
---- ---0	PERMISSION_BLOCK

---- ---1	PERMISSION_GUEST

---- --1-	PERMISSION_USER

---- -1--	PERMISSION_ADMIN

---- 1---	PERMISSION_OWNER
*/

define('PERMISSION_BLOCK',	0);
define('PERMISSION_GUEST',	1);
define('PERMISSION_USER',	2);
define('PERMISSION_ADMIN',	4);
define('PERMISSION_OWNER',	8);

function checkPer($per){
	$tmp = $per >> 4;
	$tmp = $tmp << 4;
	$per = $per - $tmp;
	switch( $per ){
		case PERMISSION_OWNER:
			$flagStr = 'OWNER';
			break;
		case PERMISSION_ADMIN:
			$flagStr = 'ADMIN';
			break;
		case PERMISSION_USER:
			$flagStr = 'USER';
			break;
		case PERMISSION_GUEST:
			$flagStr = 'GUEST';
			break;
		case PERMISSION_BLOCK:
			$flagStr = 'BLOCK';
			break;
		default:
			$flagStr = NULL;
	}
	return $flagStr;
}

?>
