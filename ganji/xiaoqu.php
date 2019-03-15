<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from lianjia_url_xiaoqu where status=0 limit 100")){
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
			$content_fieldss = "update lianjia_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1.*?>(.*?)</h1>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('lianjia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update lianjia_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//var_dump($out0);exit();
		$title = $out0[1];
		preg_match('#<div class="detailDesc".*?>(.*?)</div>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}
		$sub = $out1[1];
		

		preg_match('#<span class="xiaoquUnitPrice">(.*?)</span>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = $out3[1];
		}
		


		preg_match('#建筑年代</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$jianzhuniandai = '';
		}else{
			$jianzhuniandai = $out4[1];
		}
		//var_dump($jianzhuniandai);exit();

		preg_match('#建筑类型</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$jianzhuleixing = '';
		}else{
			$jianzhuleixing = $out5[1];
		}

		preg_match('#物业费用</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$wuyefeiyong = '';
		}else{
			$wuyefeiyong = $out6[1];
		}

		preg_match('#物业公司</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$wuyegongsi = '';
		}else{
			$wuyegongsi = $out7[1];
		}

		preg_match('#开发商</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$kaifashang = '';
		}else{
			$kaifashang = $out8[1];
		}


		preg_match('#楼栋总数</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$loudongzongshu = '';
		}else{
			$loudongzongshu = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#房屋总数</span><span class="xiaoquInfoContent">(.*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$fangwuzongshu = '';
		}else{
			$fangwuzongshu = $out10[1];
		}

		preg_match('#附近门店</span>(.*?)</div>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$fujinmeidian = '';
		}else{
			$fujinmeidian = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}



		preg_match('#<div class="fl">[\s\S]*?<a.*?class="agentName LOGCLICK LOGCLICKDATA".*?>(.*?)</a>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = $out13[1];
		}

		//var_dump($jingjiren);exit();
		//<div class="evaluate">评分:5.0/<a href="https://dianpu.lianjia.com/1000000020012870/?w=pingjia">52人评价</a>

		preg_match('#<div class="agentScore fl">评分:(.*?)</a>#', $html_city, $out14);
		if(!isset($out14[1]) || empty($out14[1])){
			$jingjirenpingfen = '';
		}else{
			$jingjirenpingfen = $out14[1];
		}

		//var_dump($jingjirenpingfen);exit();

		//<div class="phone" >4008807259<span>转</span>3922<div class="weapp-code"
		
		preg_match('#<div class="phone" >(.*?)</div>#', $html_city, $out15);
		if(!isset($out15[1]) || empty($out15[1])){
			$jingjirendianhua = '';
		}else{
			$jingjirendianhua =  trim(preg_replace('#<.*?>#', '', $out15[1]));
		}
		
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 15; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();


		
		
		$content_field = "insert into lianjia_ershou_xiaoqu(title,sub,danjia,jianzhuniandai,jianzhuleixing,wuyefeiyong,wuyegongsi,kaifashang,loudongzongshu,fangwuzongshu,fujinmeidian,jingjiren,jingjirenpingfen,jingjirendianhua,parent_id) values('{$title}','{$sub}','{$danjia}','{$jianzhuniandai}','{$jianzhuleixing}','{$wuyefeiyong}','{$wuyegongsi}','{$kaifashang}','{$loudongzongshu}','{$fangwuzongshu}','{$fujinmeidian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('lianjia_xiaoqu_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update lianjia_url_xiaoqu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>