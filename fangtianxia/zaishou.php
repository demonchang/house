<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from fangtianxia_url where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url,true);
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update fangtianxia_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1.*?>([\s\S]*?)</h1>#', $html_city, $out0);
		
		if(empty($out0)){
			file_put_contents('fangtianxia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update fangtianxia_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim($out0[1]);
		//var_dump($title);exit();

		$sub = '';
		preg_match('#<div class="sub".*?>(.*?)</div>#', $html_city, $out1);
		if(isset($out1[1]) || !empty($out1[1])){
			$sub = $out1[1];
		}
		

		preg_match('#<div class="trl-item price_esf  sty1"><i>(.*?)</i>万</div>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$zongjia = 0;
		}else{
			$zongjia = $out2[1];
		}
		

		preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">单价</div>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = $out3[1];
		}
		


		preg_match('#<div class="tt">([\s\S]*?)</div>[\s]*?<div class="font14">户型</div>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim($out4[1]);
		}


		//var_dump($zongjia,$danjia);exit();

		preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">楼层(.*?)</div>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">地上层数(.*?)</div>#', $html_city, $out35);
			if(!isset($out35[1]) || empty($out35[1])){
				$louceng = '';
			}else{
				$louceng = $out35[1].$out35[2];
			}
		}else{
			$louceng = $out5[1].$out5[2];
		}

		preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">朝向</div>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">进门朝向</div>#', $html_city, $out36);
			if(!isset($out36[1]) || empty($out36[1])){
				$chaoxiang = '';
			}else{
				$chaoxiang = $out36[1];
			}
		
		}else{
			$chaoxiang = $out6[1];
		}

		preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">装修</div>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">装修程度</div>#', $html_city, $out37);
			if(!isset($out37[1]) || empty($out37[1])){
				$zhuangxiu = '';
			}else{
				$zhuangxiu = $out37[1];
			}
			
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#<div class="tt">(.*?)</div>[\s]*?<div class="font14">建筑面积</div>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}


		preg_match('#<span class="lab">建筑类别</span>[\s]*?<span class="rcont">(.*?)</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#title="查看此楼盘的更多二手房房源" class="blue">(.*?)</a>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = $out10[1];
		}

		preg_match('#id="address">([\s\S]*?)</div>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}

		//var_dump($suozaiquyu);exit();
		
		$kanfangshijian = '';
		

		preg_match('#<span class="zf_jjname"><a[\s\S]*?href="([\s\S]*?)".*?>([\s\S]*?)</a></span>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = $out13[1].'#'.$out13[2];
		}
		//var_dump($jingjiren);exit();
		//<div class="evaluate">评分:5.0/<a href="https://dianpu.fangtianxia.com/1000000020012870/?w=pingjia">52人评价</a>

		// preg_match('#<div class="evaluate">评分:(.*?)</a>#', $html_city, $out14);
		// if(!isset($out14[1]) || empty($out14[1])){
			$jingjirenpingfen = '';
		// }else{
		// 	$jingjirenpingfen = $out14[1];
		// }

		//var_dump($jingjirenpingfen);exit();

		//<div class="phone" >4008807259<span>转</span>3922<div class="weapp-code"
		
		preg_match('#<span class="floatl mr10" id="mobilecode">(.*?)</span>#', $html_city, $out15);
		if(!isset($out15[1]) || empty($out15[1])){
			$jingjirendianhua = '';
		}else{
			$jingjirendianhua =  preg_replace('#<.*?>#', '', $out15[1]);
		}
		//var_dump($jingjirendianhua);exit();

		preg_match('#房源信息([\s\S]*?)<div class="mscont">#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		// preg_match('#<div class="name">交易属性</div>([\s\S]*?)<div class="disclaimer"#', $html_city, $out17);
		// if(!isset($out17[1]) || empty($out17[1])){
		 	$jiaoyishuxing = '';
		// }else{
		 	/*$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', $out17[1]));*/
		//}

		//<div class="mscont">
		//var_dump($jiaoyishuxing);exit();


		preg_match('#<div class="mscont">([\s\S]*?)房源图片#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<.*?>#', '', $out18[1]));
		}
		//var_dump($fangyuantese);exit();
		//30日带看<span>0</span>次
		//var_dump($fangyuantese);exit();
		// preg_match('#近7天带看次数</div>[\s\S]*?<div class="count">(.*?)</div>#', $html_city, $out19);
		// if(!isset($out19[1])){
		 	$daikancishu7 = '';
		// }else{
		// 	$daikancishu7 = $out19[1];
		// }

		// preg_match('#30日带看<span>(.*?)</span>次#', $html_city, $out20);
		// if(!isset($out20[1])){
		 	$daikancishu30 = '';
		// }else{
		// 	$daikancishu30 = $out20[1];
		// }
		
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 20; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();


		
		
		$content_field = "insert into fangtianxia_ershou_zaishou(title,sub,zongjia,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,kanfangshijian,jingjiren,jingjirenpingfen,jingjirendianhua,jibenshuxing,jiaoyishuxing,fangyuantese,daikancishu7,daikancishu30,parent_id) values('{$title}','{$sub}','{$zongjia}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$kanfangshijian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}','{$daikancishu7}','{$daikancishu30}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('fangtianxia_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update fangtianxia_url set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>