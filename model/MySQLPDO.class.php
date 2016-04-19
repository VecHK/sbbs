<?php

class mysqlPDO{
	/* 数据库信息 */
	private $dbConfig = array(
		'db' => 'mysql',
		'host' => 'localhost',
		'port' => '3306',
		'user' => 'root',
		'pass' => 'root',
		'charset' => 'utf8',
		'dbname' => 'sbbs'
	);

	/* 单例模式 本类对象引用 */
	private static $instance;

	/* PDO实例 */
	private $db;

	/**
	* 私有构造方法
	* @param @params array 数据库连接信息
	*/
	private function __construct($params){
		$this->dbConfig = array_merge($this->dbConfig, $params);

		$this->connect();
	}

	/**
	* 获得单例对象
	* @param @params array 数据库连接信息
	* @return object 单例的对象
	*/
	public static function getInstance($params = array()){
		if ( !self::$instance instanceof self ){
			self::$instance = new self($params);
		}
		return self::$instance;
	}

	/**
	 * 私有克隆
	 */
	private function __clone(){}

	/**
	* 连接目标服务器
	*/
	private function connect(){
		try{
			$dsn = "{$this->dbConfig['db']}:
			host={$this->dbConfig['host']};
			port={$this->dbConfig['host']};
			dbname={$this->dbConfig['dbname']};
			charset={$this->dbConfig['charset']};
			";

			$this->db = new PDO($dsn, $this->dbConfig['user'], $this->dbConfig['pass']);
			$this->db->query("set names {$this->dbConfig['charset']}");

		}catch( PDOException $e ){
			die("mysqlPDO connect Error: {$e->getMessage()}");
		}
	}

	/**
	* @param $sql string 执行的SQL语句
	* @return object PDOStatement
	*/
	public function query($sql){
		$rst = $this->db->query($sql);
		if ( $rst === false ){
			$error = $this->db->errorInfo();
			die("mysqlPDO query Error: ERROR {$error[1]}({$error[0]}): {$error[2]}");
		}
		return $rst;
	}

	/**
	* @param $sql string 执行的SQL语句
	* @return number lastInsertId
	*/
	public function insert($sql){
		$result = $this->query($sql);
		if ( $result ){
			return $this->db->lastInsertId();
		}else{
			return false;
		}
	}

	/**
	* 取得一行结果
	* @param $sql string 执行的SQL语句
	* @return array 关联数组结果
	*/
	public function fetchRow($sql){
		return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
	}

	/**
	* 取得所有结果
	* @param $sql string 执行的SQL语句
	* @return array 关联数组结果
	*/
	public function fetchAll($sql){
		return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	* 执行sql语句
	* @param $sql string 执行的SQL语句
	* @return 受到影响的行
	*/
	public function exec($sql){
		return $this->db->exec($sql);
	}
}
?>
