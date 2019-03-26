<?php 

require_once(dirname(__FILE__).'./../common.php');
require_once(dirname(__FILE__).'./../baidu_img.php');

function getImgnum($img_url,$price_position,$count=3){
	$res = '';
	if($count < 0) return $res;
	$baidu_str = getNum($img_url);
	if(!empty($baidu_str)){
		$price_num = str_split($baidu_str);
		
		foreach ($price_position as $keys => $values) {
			if(isset($price_num[$values])){
				$res .= $price_num[$values];
			}else{
				$new_count = $count-1;
				getImgnum($img_url,$price_position,$new_count);
			}
		}
	}
	return $res;
}


while($select_result = $sql_class->querys("select * from ziroom_url where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update ziroom_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h2>([\s\S]*?)</h2>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('ziroom_detail.log',$url.PHP_EOL,FILE_APPEND);
			$status = 3; //解析标题失败
			$content_fieldss = "update ziroom_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//var_dump($out);exit();
		$title = trim(preg_replace('#<.*?>#', '', $out0[1]));
		
		preg_match('# <span class="ellipsis">([\s\S]*?)</span>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}
		$sub = trim(preg_replace('#<.*?>#', '', $out1[1]));
		//var_dump($title,$sub);exit();
		preg_match('#<p class="room_tags clearfix">([\s\S]*?)</p>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$biaoqian = '';
		}else{
			$biaoqian = trim(preg_replace('#[\s]*?#', '', preg_replace('#<.*?>#', '', $out2[1])));

		}
		//var_dump($title,$biaoqian);exit();




		preg_match('#<input type="hidden" value="(\d*?)" id="room_id" />#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$room_id = '';
		}else{
			$room_id = $out3[1];
		}

		preg_match('#<input type="hidden" value="(\d*?)" id="house_id" />#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$house_id = '';
		}else{
			$house_id = $out10[1];
		}
		$danjia = '';
		$api_url = "http://www.ziroom.com/detail/info?id={$room_id}&house_id={$house_id}";
		$api_json = $curl_class->request($api_url);
		$api_arr = json_decode($api_json,true);
		if(isset($api_arr['data']['air_part']['air_quality']['show_info']['air_test_time'])){
			$air_test_time = $api_arr['data']['air_part']['air_quality']['show_info']['air_test_time'];
			$is_new_des = $api_arr['data']['air_part']['air_quality']['show_info']['is_new_des'];
		}else{
			$air_test_time = '';
			$is_new_des = '';
		}
		
		if($is_new_des == '首次出租'){
			$promise = $api_arr['data']['air_part']['vanancy']['promise'];
			$vanancy_day = $api_arr['data']['air_part']['vanancy']['vanancy_day'];
		}else{
			$promise = '';
			$vanancy_day = '';
		}
		
		$biaoqian .= ' '.$air_test_time.' '.$is_new_des.' '.$promise.' '.$vanancy_day;
		//var_dump($air_test_time,$promise,$vanancy_day);exit();
		$img_url = 'https:'.$api_arr['data']['price'][1];
		$price_position = $api_arr['data']['price'][2];

		$danjia = getImgnum($img_url,$price_position);
		

		

		
		//var_dump($danjia);exit();
		

		preg_match('#<span class="room_price" id="room_price"></span><span class="gray-6">(.*?)</span>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$danjia = '';
		}else{
			$danjia = $danjia.$out11[1];
		}

		//var_dump($danjia);exit();
		preg_match('#</b>户型：([\s\S]*?)</li>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#<.*?>#', '', $out4[1]));
		}

		preg_match('#</b>楼层：([\s\S]*?)</li>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = trim(preg_replace('#<.*?>#', '', $out5[1]));
		}

		preg_match('#</b>朝向：([\s\S]*?)</li>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = trim(preg_replace('#<.*?>#', '', $out6[1]));
		}

		preg_match('#<span class="style">([\s\S]*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#</b>面积：([\s\S]*?)</li>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = trim(preg_replace('#<.*?>#', '', $out8[1]));
		}


		preg_match('#</b>交通：([\s\S]*?)</li>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out9[1]));
		}
		//var_dump($chaoxiang,$leixing);exit();

		//http://www.ziroom.com/detail/steward?resblock_id=1111027374745&room_id=61338790&house_id=60214926&ly_name=&ly_phone=
		preg_match('#<input type="hidden" id="resblock_id" value="(.*?)"/>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
			
		}else{
			$resblock_id = $out13[1];
			$jingjiren = "http://www.ziroom.com/detail/steward?resblock_id={$resblock_id}&room_id={$room_id}&house_id={$house_id}&ly_name=&ly_phone=";
		}

		if($jingjiren){
			$jingjiren_json = $curl_class->request($jingjiren);
			$jingjiren_arr = json_decode($jingjiren_json,true);
			
			$jingjirenmingcheng = $jingjiren_arr['data']['keeperName'];
			$jingjirendianhua = $jingjiren_arr['data']['keeperPhone'];
			$jingjirensum = $jingjiren_arr['data']['keeperPresent'];
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';

		}else{
			$jingjirensum = '';
			$jingjirenmingcheng = '';
			$jingjirendianhua = '';
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';
		}

		
		

		preg_match('#<h3 class="fb">([\s\S]*?)房屋配置#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#<ul class="configuration clearfix">([\s\S]*?)</ul>#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#<div class="greatRoommate.*?>([\s\S]*?)<!--/greatRoommate-->#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#[\s]*?#', '', preg_replace('#<.*?>#', '', $out18[1])));
		}

		
		$date = date('Y-m-d');
		
		
		$content_field = "insert into ziroom_gongyu(title,sub,biaoqian,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,suozaiquyu,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id,date) values('{$title}','{$sub}','{$biaoqian}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$suozaiquyu}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']},'{$date}')";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('ziroom_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update ziroom_url set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>