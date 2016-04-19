<?php

class PostModel extends model{
	public function getPostsIndexByBid($bid, $start, $end){
		$bid = (int) $bid;
		$start = (int) $start;
		$end = (int) $end;
		$sql = "SELECT * FROM `sbbs_postlist` WHERE `bid` = '{$bid}' ORDER BY `mod` LIMIT {$start}, {$end} ;";
		$data = $this->db->fetchAll($sql);
		return $data;
	}

	public function getPostIndexById($pid){
		$pid = (int) $pid;

		$sql = "SELECT * FROM `sbbs_postlist` WHERE `id` = '{$pid}'";
		return $this->db->fetchRow($sql);
	}

	/**
	* 获取帖子
	* @param $pid Number pid
	* @return $posts Array
	*/
	public function getPostById($pid, $start, $end){
		$pid = (int) $pid;
		$start = (int) $start;
		$end = (int) $end;

		$sql = "SELECT * FROM `sbbs_pool` WHERE `pid` = '{$pid}' ORDER BY `poolid` LIMIT {$start}, {$end}";
		return $this->db->fetchAll($sql);
	}

	/**
	* 创建帖子
	* @param $post array 帖子相关数据
	* @return number pid 帖子的pid
	*/
	public function createPost($post){
		$sql = "INSERT INTO `sbbs_postlist` (`bid`, `uid`, `title`, `floor`, `time`, `mod`, `creator`, `lastuid`, `lastuname`, `lasttime`)
		VALUES (
			'{$post["bid"]}',
			'{$post["uid"]}',
			'{$post["title"]}',
			'1',
			now(),
			now(),
			'{$post["uid"]}',
			'{$post["uid"]}',
			'',
			now());";
		$insertId = $this->db->insert($sql);

		$insertId || die("在postList创建的index似乎失败了");

		$postListItem = $this->db->fetchRow("SELECT * FROM `sbbs_postlist` WHERE id = {$insertId}");

		$postListItem || die("在postList创建的index似乎不存在");

		$sql = "INSERT INTO `sbbs_pool` (`pid`, `uid`, `floor`, `uname`, `content`, `type`, `format`, `time`, `mod`)
		VALUES (
			'{$postListItem['id']}',
			'{$post['uid']}',
			1,
			'',
			'{$post['content']}',
			'text',
			'{$post['format']}',
			now(),
			now());";
		$poolResult = $this->db->insert($sql);
		$poolResult || die('pool的创建似乎失败了');

		return array(
			'poolId' => $poolResult,
			'postItem' => $postListItem
		);
	}

	/**
	* 获得池中的一条数据
	* @param $poolId pool的id
	* @return Resource poolRow
	*/
	function getPoolById($poolId){
		$poolId = (int) $poolId;
		return $this->db->fetchRow("SELECT * FROM `sbbs_pool` WHERE poolid = '{$poolId}'");
	}

}

class postModelAdmin extends postModel{
	public function removePosts($start, $end=1){

	}
}

?>
