<?php

class PostModel extends model{
	public function getPostsByBid($start, $end){
		$start = (int) $start;
		$end = (int) $end;
		$data = $this->db->fetchAll("SELECT * FROM `sbbs_postlist` ORDER BY id LIMIT {$start}, {$end}");
	}

	public function getPostById($pid){

	}
	/**
	* @param $post array 帖子相关数据
	* @return number lastInsertId
	*/
	public function newPost($post){
		$result = $this->db->insert("INSERT INTO `sbbs_postlist` values(
			{$post['bid']},
			{$post['uid']},
			{$post['title']},
			1,
			now(),
			now(),
			{$post['uid']},
			'',
			now()
		)
		");
		return $result;
	}

}

class postModelAdmin extends postModel{
	public function removePosts($start, $end=1){

	}
}

?>
