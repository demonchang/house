<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from anjuke_url_zufang where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		//$url = 'https://as.zu.anjuke.com/fangyuan/1267450306';
		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update anjuke_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h3 class="house-title">(.*?)</h3>#', $html_city, $out0);
		//var_dump($out0);exit();
		if(empty($out0)){
			file_put_contents('anjuke_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update anjuke_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim($out0[1]);
		//var_dump($title);exit();
		
		preg_match('#<div class="right-info">(.*?)</div>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$sub = '';
		}else{
			$sub = trim(preg_replace('#<.*?>#', '', $out2[1]));
		}


		//<li class="title-label-item rent">整租</li>


		preg_match('#<li class="title-label-item rent">(.*?)</li>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$fangshi = 0;
		}else{
			$fangshi = trim(preg_replace('#<.*?>#', '', $out2[1]));
		}
		//var_dump($title,$sub,$fangshi);exit();
		
		preg_match('#<span class="light info-tag">(.*?)</span>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#<.*?>#', '', $out3[1]));
		}
		
		//var_dump($danjia);exit();

		preg_match('#户型：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#<.*?>#', '', $out4[1]));
		}
		//var_dump($huxing);exit();
		preg_match('#楼层：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = $out5[1];
		}

		preg_match('#朝向：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = $out6[1];
		}

		preg_match('#装修：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#面积：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}


		preg_match('#类型：</span>[\s]*?<span class="info">(.*?)</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#小区：</span>[\s\S]*?</li>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<.*?>#', '', $out10[1]));
		}

		

		preg_match('#<h2 class="broker-name" title=".*?">(.*?)</h2>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = trim(preg_replace('#<.*?>#', '', $out13[1]));
		}
		//var_dump($jingjiren);exit();

       

		preg_match('#<div class="brokercard-scoredetail">([\s\S]*?)<div class="brokercard-sd-tip"#', $html_city, $out14);
		if(!isset($out14[1]) || empty($out14[1])){
			$jingjirenpingfen = '';
		}else{
			$jingjirenpingfen = trim(preg_replace('#<.*?>#', '', $out14[1]));
		}

		//
		preg_match('#<div class="broker-card">([\s\S]*?) <!-- 如果是隐私通话的城市 -->#', $html_city, $out19);
		if(!isset($out19[1]) || empty($out19[1])){
			$jingjirensum = '';
		}else{
			$jingjirensum = trim(preg_replace('#<.*?>#', '', $out19[1]));
		}		

		preg_match('#房屋信息</h3>([\s\S]*?)</ul>#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#房屋配套</h3>([\s\S]*?)房源概况</h3>#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#房源概况</h3>([\s\S]*?)<!--传给前端的数据begin-->#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<.*?>#', '', $out18[1]));
		}

		//var_dump($fangyuantese);exit();

		
		
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 20; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();


		
		
		$content_field = "insert into anjuke_ershou_zufang(title,sub,fangshi,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,jingjiren,jingjirenpingfen,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$fangshi}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('anjuke_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update anjuke_url_zufang set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>