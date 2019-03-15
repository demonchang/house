<?php
class Mysqlis{
	private static $conn;
	//配置文件路径
	private static $config_file_path = 'config.php';

	public function __construct(){
		$config = require self::$config_file_path;
		$host = $config['mysql']['host'];
		$username = $config['mysql']['username'];
		$password = $config['mysql']['password'];
		$dbname = $config['mysql']['dbname'];
		$connection = new mysqli($host, $username, $password, $dbname);
		if(mysqli_connect_errno()){
		 	return mysqli_connect_error();
		}else{
			$connection->set_charset('utf8');
			self::$conn = $connection;
		}
	}


	public function insert($sql){		
			$res = self::$conn->query($sql);
			if($res){
				return $this->querys('SELECT LAST_INSERT_ID() AS id');
			}else{
				return false;
			}		
	}

	public function querys($sql){
		$res = [];
		$results = self::$conn->query($sql);
		if(empty($results)) return false;
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){

		    $res[] = $row;
		}
		return $res;
	}

	public function update($sql){
		return  self::$conn->query($sql);
		
		 
	}

	public function insertContent($md5url, $tablename, $sql){
		$query = "select * from ".$tablename." where url='".$md5url."'";
		$query_res = $this->querys($query);
		if (!$query_res) {
			
			return self::$conn->query($sql);
			
		}else{
			return false;
		}
	}



	
}



 ?>