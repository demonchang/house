<?php 
require_once(dirname(__FILE__).'./../common.php');


$content_field = "select * from ganji_city";
$select_result = $sql_class->querys($content_field);



foreach ($select_result as $key => $value) {
	$city_url = $value['url'];
	$parent_id = $value['id'];
	preg_match('#\/\/(.*?)\.ganji\.com#', $city_url, $out);
	$city_name = $out[1];
	$content_field1 = "select * from ganji_area";
	$select_result1 = $sql_class->querys($content_field1);
	foreach ($select_result1 as $key1 => $value1) {
		$city_url1 = $value1['url'];
		$id = $value1['id'];
		preg_match('#\/\/(.*?)\.ganji\.com#', $city_url1, $out1);
		$city_name1 = $out1[1];

		if($city_name == $city_name1){
			$content_fieldss = "update ganji_area set parent_id={$parent_id} where id={$id}";
			//var_dump($content_fieldss);exit();
			$sql_class->update($content_fieldss);
		}
	}
}




 ?>