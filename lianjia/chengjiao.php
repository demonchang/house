<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from lianjia_url_chengjiao where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url);
		// file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update lianjia_url_chengjiao set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1.*?>(.*?)</h1>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('lianjia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update lianjia_url_chengjiao set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//var_dump($out0);exit();
		$title = $out0[1];
		preg_match('#<span>(.*?) 成交</span><h1#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$chengjiaoriqi = '';
		}
		$chengjiaoriqi = $out1[1];
		//var_dump($chengjiaoriqi);exit();

		preg_match('#<span class="dealTotalPrice"><i>(.*?)</i>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$zongjia = 0;
		}else{
			$zongjia = $out2[1];
		}
		

		preg_match('#<b>(.*?)</b>元/平#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = $out3[1];
		}
		//var_dump($zongjia,$danjia);exit();


		preg_match('#<span><label>(.*?)</label>挂牌价格#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$guapaijia = '';
		}else{
			$guapaijia = $out4[1];
		}



		preg_match('#挂牌价格（万）</span><span><label>(.*?)</label>成交周期#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$chengjiaozhouqi = '';
		}else{
			$chengjiaozhouqi = $out5[1];
		}
		//var_dump($chengjiaozhouqi);exit();
		preg_match('#成交周期（天）</span><span><label>(.*?)</label>调价#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$tiaojiacishu = '';
		}else{
			$tiaojiacishu = $out6[1];
		}

		preg_match('#调价（次）</span><span><label>(.*?)</label>带看#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$daikancishu = '';
		}else{
			$daikancishu = $out7[1];
		}
		//var_dump($daikancishu);exit();

		preg_match('#带看（次）</span><span><label>(.*?)</label>关注#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$guanzhushu = '';
		}else{
			$guanzhushu = $out8[1];
		}


		preg_match('#关注（人）</span><span><label>(.*?)</label>浏览#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$liulanshu = '';
		}else{
			$liulanshu = $out9[1];
		}
		//var_dump($leixing);exit();

		preg_match("#houseCode:'(.*?)',#", $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren_lianjie = '';
		}else{
			$jingjiren_lianjie = 'https://hf.lianjia.com/chengjiao/display?hid='.$out13[1];
		}

		preg_match('#<div class="name">基本属性</div>([\s\S]*?)<div class="transaction">#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#<ul class="record_list">([\s\S]*?)</ul>#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyijilu = '';
		}else{
			$jiaoyijilu =  trim(preg_replace('#<.*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#<!-- 房源特色 -->([\s\S]*?)<!-- 房主自荐 -->#', $html_city, $out18);
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
		// 	if(!empty(${$wrap})){
		// 		var_dump(${$wrap}[1]);
		// 	}
			
		// }
		// exit();


		
		
		$content_field = "insert into lianjia_ershou_chengjiao(title,chengjiaoriqi,zongjia,danjia,guapaijia,chengjiaozhouqi,tiaojiacishu,daikancishu,guanzhushu,liulanshu,jingjiren_lianjie,jibenshuxing,jiaoyijilu,fangyuantese,parent_id) values('{$title}','{$chengjiaoriqi}','{$zongjia}','{$danjia}','{$guapaijia}','{$chengjiaozhouqi}','{$tiaojiacishu}','{$daikancishu}','{$guanzhushu}','{$liulanshu}','{$jingjiren_lianjie}','{$jibenshuxing}','{$jiaoyijilu}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('lianjia_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update lianjia_url_chengjiao set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>