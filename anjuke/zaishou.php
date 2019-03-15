<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from anjuke_url where status=0 limit 100")){
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
			$content_fieldss = "update anjuke_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h3 class="long-title">([\s\S]*?)</h3>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('anjuke_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update anjuke_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim($out0[1]);
		//var_dump($title);exit();
		
		$sub = '';


		preg_match('#<span class="light info-tag">([\s\S]*?)</span>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$zongjia = 0;
		}else{
			$zongjia = trim(preg_replace('#<.*?>#', '', $out2[1]));
		}
		
		
		preg_match('#房屋单价：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = $out3[1];
		}
		
		//var_dump($danjia);exit();

		preg_match('#房屋户型：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#<.*?>#', '', $out4[1]));
		}
		//var_dump($huxing);exit();
		preg_match('#所在楼层：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = $out5[1];
		}

		preg_match('#房屋朝向：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = $out6[1];
		}

		preg_match('#装修程度：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#建筑面积：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}


		preg_match('#房屋类型：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#所属小区：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<.*?>#', '', $out10[1]));
		}

		preg_match('#所在位置：</div>[\s\S]*?<div class="houseInfo-content.*?">([\s\S]*?)</div>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}


		
		$kanfangshijian = '';
		

		preg_match('#<div class="brokercard-name">([\s\S]*?)<div class="broker-level clearfix">#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = trim(preg_replace('#<.*?>#', '', $out13[1]));
		}
		//var_dump($jingjiren);exit();
		//<div class="evaluate">评分:5.0/<a href="https://dianpu.anjuke.com/1000000020012870/?w=pingjia">52人评价</a>

		preg_match('#<div class="brokercard-scoredetail">([\s\S]*?)<div class="brokercard-sd-tip"#', $html_city, $out14);
		if(!isset($out14[1]) || empty($out14[1])){
			$jingjirenpingfen = '';
		}else{
			$jingjirenpingfen = trim(preg_replace('#<.*?>#', '', $out14[1]));
		}

		//
		preg_match('#<div class="broker-company">([\s\S]*?)<div.*?id="allScreen">#', $html_city, $out19);
		if(!isset($out19[1]) || empty($out19[1])){
			$jingjirensum = '';
		}else{
			$jingjirensum = trim(preg_replace('#<.*?>#', '', $out19[1]));
		}


		

		//<div class="phone" >4008807259<span>转</span>3922<div class="weapp-code"
		
		
		$jingjirendianhua = '';
		

		preg_match('#<div class="houseInfoBox">([\s\S]*?)<div class="houseInfo-desc ">#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#<div class="houseInfo-item-desc js-house-explain">([\s\S]*?)<!-- 二手房信息 -->#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#业主心态[\s\S]*?<div class="houseInfo-item-desc">([\s\S]*?)</div>#', $html_city, $out18);
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


		
		
		$content_field = "insert into anjuke_ershou_zaishou(title,sub,zongjia,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,kanfangshijian,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$zongjia}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$kanfangshijian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('anjuke_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update anjuke_url set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>