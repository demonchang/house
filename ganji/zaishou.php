<?php 

require_once(dirname(__FILE__).'./../common.php');

$path = './ganji/';
while($select_result = $sql_class->querys("select * from ganji_url where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		sleep(1);
		$html_city = $curl_class->request($url);
		//file_put_contents($path.$value['id'].'.html',$html_city);
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update ganji_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<!--房源标题start-->[\s\S]*?<i>([\s\S]*?)</i>[\s\S]*?<!--房源标题end-->#', $html_city, $out0);

		if(empty($out0)){
			file_put_contents('ganji_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update ganji_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<.*?>#', '', $out0[1]));
		
		$sub = '';
		

		preg_match('#<span class="price">(.*?)</span>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$zongjia = 0;
		}else{
			$zongjia = $out2[1];
		}
		

		preg_match('# <span class="price">[\s\S]*?class="unit">(.*?)元/m²</span>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim($out3[1]);
		}
		

		preg_match('#户<i class="space"></i>型：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = $out4[1];
		}

		preg_match('#楼<i class="space"></i>层：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = $out5[1];
		}

		preg_match('#朝<i class="space"></i>向：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = $out6[1];
		}

		preg_match('#装修情况：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#面<i class="space"></i>积：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}


		preg_match('#产<i class="space"></i>权：</span>[\s\S]*?class="content">(.*?)</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#小区名称：[\s\S]*?<span class="content">([\s\S]*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<.*?>#', '', $out10[1]));
		}

		preg_match('#所在地址：[\s\S]*?<span class="content">([\s\S]*?)</span>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}


	
		$kanfangshijian = '';
		

		preg_match('#<div class="name">([\s\S]*?)</div>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = trim(preg_replace('#<.*?>#', '', $out13[1]));
		}

		//<div class="evaluate">评分:5.0/<a href="https://dianpu.ganji.com/1000000020012870/?w=pingjia">52人评价</a>

	
		$jingjirenpingfen = '';
		

		//var_dump($jingjirenpingfen);exit();

		//<div class="phone" >4008807259<span>转</span>3922<div class="weapp-code"
		
		
		$jingjirendianhua = '';
		

		preg_match('#房屋描述</h3>([\s\S]*?)<h3 class="g-title">业主心态#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#服务介绍</h3>([\s\S]*?)<!--政府核验信息-->#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#业主心态</h3>([\s\S]*?)<h3 class="g-title">服务介绍#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<.*?>#', '', $out18[1]));
		}

		//30日带看<span>0</span>次
		//var_dump($fangyuantese);exit();
		
			$daikancishu7 = '';
		

		
			$daikancishu30 = '';
		
		
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 20; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();

		/*小区信息采集*/
		preg_match('#id="js-xiaoquinfo-mark">([\s\S]*?)<!--房贷计算器start-->#', $html_city, $out21);
		if(!isset($out21[1]) || empty($out21[1])){
			$xiaoqu = '';
		}else{
			$xiaoqu = $out21[1];

			preg_match('#href="(.*?)"#', $xiaoqu, $out22);
			//var_dump($out22);exit();
			if(!isset($out22[1]) || empty($out22[1])){
				$xiaoqu_url = '';
			}else{
				$xiaoqu_url = 'http:'.$out22[1];

				$content_url_field = "select * from ganji_url_xiaoqu where url='{$xiaoqu_url}'";
				$select_result = $sql_class->querys($content_url_field);
				//var_dump($select_result);exit();
				if(empty($select_result)){
					$content_field = "insert into ganji_url_xiaoqu(url,parent_id) values('{$xiaoqu_url}',{$value['parent_id']})";
					//var_dump($content_field);exit();
					$select_result = $sql_class->insert($content_field);
					$xiaoqu_parent_id = $select_result[0]['id'];

					// 插入连接直接保存小区信息
					preg_match('#<p class="title">[\s\S]*?<a href=".*?/xiaoqu/[\s\S]*?<span>([\s\S]*?)</span>#', $xiaoqu, $out23);
					if(!isset($out23[1]) || empty($out23[1])){
						$xiaoqu_title = '';
						continue;
					}else{
						$xiaoqu_title = trim($out23[1]);
					}

					//
					preg_match('#均价<i class="average-red">(.*?)</i>#', $xiaoqu, $out24);
					if(!isset($out24[1]) || empty($out24[1])){
						$xiaoqu_junjia = '';
					}else{
						$xiaoqu_junjia = trim($out24[1]);
					}

					preg_match('#容积率：([\s\S]*?)</li>#', $xiaoqu, $out25);
					if(!isset($out25[1]) || empty($out25[1])){
						$xiaoqu_rongjilv = '';
					}else{
						$xiaoqu_rongjilv = trim($out25[1]);
					}

					preg_match('#所在地址：([\s\S]*?)</li>#', $xiaoqu, $out26);
					if(!isset($out26[1]) || empty($out26[1])){
						$xiaoqu_suozaidizhi = '';
					}else{
						$xiaoqu_suozaidizhi = trim($out26[1]);
					}
					preg_match('#绿化率：([\s\S]*?)</li>#', $xiaoqu, $out27);
					if(!isset($out27[1]) || empty($out27[1])){
						$xiaoqu_lvhualv = '';
					}else{
						$xiaoqu_lvhualv = trim($out27[1]);
					}
					preg_match('#所在区域：([\s\S]*?)</li>#', $xiaoqu, $out28);
					if(!isset($out28[1]) || empty($out28[1])){
						$xiaoqu_suizaiquyu = '';
					}else{
						$xiaoqu_suizaiquyu = trim($out28[1]);
					}
					preg_match('#停车位：([\s\S]*?)</li>#', $xiaoqu, $out29);
					if(!isset($out29[1]) || empty($out29[1])){
						$xiaoqu_tingchewei = '';
					}else{
						$xiaoqu_tingchewei = trim($out29[1]);
					}
					preg_match('#竣工时间：([\s\S]*?)</li>#', $xiaoqu, $out30);
					if(!isset($out30[1]) || empty($out30[1])){
						$xiaoqu_jungongshijian = '';
					}else{
						$xiaoqu_jungongshijian = trim($out30[1]);
					}

					preg_match('#<div class="person">[\s\S]*?<a.*?href="(.*?)".*?>([\s\S]*?)</a>#', $html_city, $out31);
					if(!isset($out31[1]) || empty($out31[1])){
						$xiaoqu_jingjiren = '';
					}else{
						$xiaoqu_jingjiren = trim($out31[1].'#'.$out31[2]);
					}



					preg_match('#经纪公司：</span>[\s]*?<span class="text">(.*?)</span>#', $html_city, $out32);
					if(!isset($out32[1]) || empty($out32[1])){
						$xiaoqu_jingjigongsi = '';
					}else{
						$xiaoqu_jingjigongsi = trim($out32[1]);
					}
					preg_match('#服务区域：</span>[\s]*?<span class="text">(.*?)</span>#', $html_city, $out33);
					if(!isset($out33[1]) || empty($out33[1])){
						$xiaoqu_jingjirenfuwuquyu = '';
					}else{
						$xiaoqu_jingjirenfuwuquyu = trim($out33[1]);
					}

					$content_fieldsss = "insert into ganji_ershou_xiaoqu(title,danjia,rongjilv,suozaidizhi,lvhualv,suizaiquyu,tingchewei,jungongshijian,jingjiren,jingjigongsi,jingjirenfuwuquyu,parent_id) values('{$xiaoqu_title}','{$xiaoqu_junjia}','{$xiaoqu_rongjilv}','{$xiaoqu_suozaidizhi}','{$xiaoqu_lvhualv}','{$xiaoqu_suizaiquyu}','{$xiaoqu_tingchewei}','{$xiaoqu_jungongshijian}','{$xiaoqu_jingjiren}','{$xiaoqu_jingjigongsi}','{$xiaoqu_jingjirenfuwuquyu}',{$xiaoqu_parent_id})";
					//var_dump($content_fieldsss);exit();
					$select_result = $sql_class->insert($content_fieldsss);

					//var_dump($xiaoqu_junjia);exit();
					
				}
			}

		}

		//var_dump(111);exit();


		
		
		$content_field = "insert into ganji_ershou_zaishou(title,sub,zongjia,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,kanfangshijian,jingjiren,jingjirenpingfen,jingjirendianhua,jibenshuxing,jiaoyishuxing,fangyuantese,daikancishu7,daikancishu30,parent_id) values('{$title}','{$sub}','{$zongjia}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$kanfangshijian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}','{$daikancishu7}','{$daikancishu30}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('ganji_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update ganji_url set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>