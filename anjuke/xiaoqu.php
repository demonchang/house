<?php 

require_once(dirname(__FILE__).'./../common.php');

$sql = "select * from anjuke_url_xiaoqu where status=0 limit 100";
while($select_result = $sql_class->querys($sql)){
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
			$content_fieldss = "update anjuke_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1>([\s\S]*?)<span#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('anjuke_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update anjuke_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<.*?>#', '', $out0[1]));
		
		preg_match('#<span class="sub-hd">([\s\S]*?)</span>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}
		$sub = $out1[1];
		
		//var_dump($sub);exit();
		//<span class="average">4621<em class="unit">元/m²</em></span>
		preg_match('#"comm_midprice":"(.*?)",#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia =  trim(preg_replace('#<.*?>#', '', $out3[1]));
		}
		
		//var_dump($danjia);exit();

		preg_match('#建造年代：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$jianzhuniandai = '';
		}else{
			$jianzhuniandai = $out4[1];
		}
		//var_dump($jianzhuniandai);exit();

		preg_match('#物业类型：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$jianzhuleixing = '';
		}else{
			$jianzhuleixing = $out5[1];
		}

		preg_match('#物业费：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out75);
		if(!isset($out75[1]) || empty($out75[1])){
			$wuyefeiyong = '';
		}else{
			$wuyefeiyong = $out75[1];
		}


		preg_match('#绿化率：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out60);
		if(!isset($out60[1]) || empty($out60[1])){
			$lvhualv = '';
		}else{
			$lvhualv = $out60[1];
		}

		preg_match('#停车位：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out61);
		if(!isset($out61[1]) || empty($out61[1])){
			$tingchewei = '';
		}else{
			$tingchewei = $out61[1];
		}

		preg_match('#总户数：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out62);
		if(!isset($out62[1]) || empty($out62[1])){
			$zonghushu = '';
		}else{
			$zonghushu = $out62[1];
		}

		preg_match('#容&nbsp;&nbsp;积&nbsp;&nbsp;率：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out63);
		if(!isset($out63[1]) || empty($out63[1])){
			$rongjilv = '';
		}else{
			$rongjilv = $out63[1];
		}


		preg_match('#物业公司：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$wuyegongsi = '';
		}else{
			$wuyegongsi = $out7[1];
		}

		preg_match('#开&nbsp;&nbsp;发&nbsp;&nbsp;商：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$kaifashang = '';
		}else{
			$kaifashang = $out8[1];
		}


		
			$loudongzongshu = '';
		
		//var_dump($leixing);exit();


		preg_match('#总户数：</dt><dd.*?>([\s\S]*?)</dd>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$fangwuzongshu = '';
		}else{
			$fangwuzongshu = $out10[1];
		}

	
		$fujinmeidian = '';
		



	
			$jingjiren = '';
		

		//var_dump($jingjiren);exit();
		//<div class="evaluate">评分:5.0/<a href="https://dianpu.anjuke.com/1000000020012870/?w=pingjia">52人评价</a>

		
			$jingjirenpingfen = '';
	

		//var_dump($jingjirenpingfen);exit();

		//<div class="phone" >4008807259<span>转</span>3922<div class="weapp-code"
		
		
			$jingjirendianhua = '';
			
		
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 15; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();


		
		
		$content_field = "insert into anjuke_ershou_xiaoqu(title,sub,danjia,jianzhuniandai,jianzhuleixing,wuyefeiyong,wuyegongsi,kaifashang,lvhualv,tingchewei,zonghushu,rongjilv,loudongzongshu,fangwuzongshu,fujinmeidian,jingjiren,jingjirenpingfen,jingjirendianhua,parent_id) values('{$title}','{$sub}','{$danjia}','{$jianzhuniandai}','{$jianzhuleixing}','{$wuyefeiyong}','{$wuyegongsi}','{$kaifashang}','{$lvhualv}','{$tingchewei}','{$zonghushu}','{$rongjilv}','{$loudongzongshu}','{$fangwuzongshu}','{$fujinmeidian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('anjuke_xiaoqu_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update anjuke_url_xiaoqu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>