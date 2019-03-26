<?php 
//select a.*,b.url from ganji_ershou_zaishou  as a left join ganji_url as b on a.parent_id = b.id limit 300
return array(
	'mysql' => array(
		'host'=> '127.0.0.1',
		'port' => 3306,
		'username' => 'root',
		'password' => '123456',
		'charset' => 'utf8',
		'dbname' => 'ziroom'
		)
	);
?>