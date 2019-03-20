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
		preg_match('#(.*?)chengjiao\/.*?#',$url,$out01);
		if(!empty($out01)){
			$base_url = $out01[1];
		}else{
			$base_url = '';
		}


		$html_city = $curl_class->request($url);
		//file_put_contents('detail1.html',$html_city);exit();
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
			$jingjiren_lianjie = '';
			if($base_url){
				$api = $base_url.'chengjiao/display?hid='.$out13[1];
				//var_dump($api);
				$html_json= $curl_class->request($api);
				$arr = json_decode($html_json,true);
				//var_dump($arr);
				$jingjiren_agent = $arr['data']['agentUcid'];
				if($jingjiren_agent){
					$jingjiren_lianjie = 'https://dianpu.lianjia.com/'.$jingjiren_agent;
				}
				
			}
			
		}
		//var_dump($jingjiren_lianjie);exit();
		if($jingjiren_lianjie){
			//var_dump($url);
			//var_dump($jingjiren_lianjie);

			$html_jingjiren= $curl_class->request($jingjiren_lianjie);
			//file_put_contents('jingjiren.html',$html_jingjiren);exit();
			preg_match('#<div class="agent-name clear-fl".*?><a.*?>(.*?)</a>(.*?)</span>#', $html_jingjiren, $out21);
			if(!isset($out21[1]) || empty($out21[1])){
				$jingjirenmingcheng = 0;
			}else{
				$jingjirenmingcheng = $out21[1];
			}

			preg_match('#联系电话:&nbsp;&nbsp;(.*?)</span>#', $html_jingjiren, $out22);
			if(!isset($out22[1]) || empty($out22[1])){
				$jingjirendianhua = 0;
			}else{
				$jingjirendianhua = $out22[1];
			}


			preg_match('#所属门店:&nbsp;<i id="icon_pin"></i></span><a data-coord="" id="mapShow"><span>(.*?)</span>#', $html_jingjiren, $out23);
			if(!isset($out23[1]) || empty($out23[1])){
				$jingjirensuoshu = 0;
			}else{
				$jingjirensuoshu = $out23[1];
			}


			preg_match('#入职年限:&nbsp;&nbsp;</span>(.*?)&nbsp;#', $html_jingjiren, $out24);
			if(!isset($out24[1]) || empty($out24[1])){
				$jingjirengongzuoshijian = 0;
			}else{
				$jingjirengongzuoshijian = $out24[1];
			}


			preg_match('#<div class="info_bottom">([\s\S]*?)<div class="comment_tab con-box">#', $html_jingjiren, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jingjirensum = 0;
			}else{
				$jingjirensum = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[^<].*?>#', '', $out25[1])));
			}

			preg_match('#<div class="num">综合评分：(.*?)</div>#', $html_jingjiren, $out26);
			if(!isset($out26[1]) || empty($out26[1])){
				$jingjirenpingfen = 0;
			}else{
				$jingjirenpingfen = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[^<].*?>#', '', $out26[1])));
			}
			

		
		}else{
			$jingjirensum = '';
			$jingjirenmingcheng = '';
			$jingjirendianhua = '';
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';
		}

		preg_match('#<div class="name">基本属性</div>([\s\S]*?)<div class="disclaimer">#', $html_city, $out16);
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


		
		
		$content_field = "insert into lianjia_ershou_chengjiao(title,chengjiaoriqi,zongjia,danjia,guapaijia,chengjiaozhouqi,tiaojiacishu,daikancishu,guanzhushu,liulanshu,jingjiren_lianjie,jingjirenmingcheng,jingjirendianhua,jingjirenpingfen,jingjirensum,jingjirengongzuoshijian,jibenshuxing,jiaoyijilu,fangyuantese,parent_id) values('{$title}','{$chengjiaoriqi}','{$zongjia}','{$danjia}','{$guapaijia}','{$chengjiaozhouqi}','{$tiaojiacishu}','{$daikancishu}','{$guanzhushu}','{$liulanshu}','{$jingjiren_lianjie}','{$jingjirenmingcheng}','{$jingjirendianhua}','{$jingjirenpingfen}','{$jingjirensum}','{$jingjirengongzuoshijian}','{$jibenshuxing}','{$jiaoyijilu}','{$fangyuantese}',{$value['id']})";
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