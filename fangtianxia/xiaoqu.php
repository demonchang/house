<?php 

require_once(dirname(__FILE__).'./../common.php');




//select a.*,b.url from fangtianxia_ershou_xiaoqu as a left join fangtianxia_url_xiaoqu as b on a.parent_id = b.id limit 300
while($select_result = $sql_class->querys("select * from fangtianxia_url_xiaoqu where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);exit();
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url,true);
		$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update fangtianxia_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1.*?>([\s\S]*?)</h1>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('fangtianxia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update fangtianxia_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<.*?>#', '', $out0[1]));
		//var_dump($title);exit();
		
		// if(!isset($out1[1]) || empty($out1[1])){
		 	$sub = '';
		// }
		// $sub = $out1[1];
		

		preg_match('#<span class="prib">(.*?)</span>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = $out3[1];
		}


		


		preg_match('#<li><b>建筑年代</b>(.*?)</li>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			preg_match('#<dd>竣工时间：(.*?)</dd>#', $html_city, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jianzhuniandai = '';
			}else{
				$jianzhuniandai = $out25[1]; 
			}
			
		}else{
			$jianzhuniandai = $out4[1];
		}
		//var_dump($jianzhuniandai);exit();

		preg_match('#<li><b>建筑类型</b>(.*?)</li>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$jianzhuleixing = '';
		}else{
			$jianzhuleixing = $out5[1];
		}

		
		$wuyefeiyong = '';
		

		preg_match('#<li .*?><b>物业公司</b>(.*?)</li>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			preg_match('#<dd>物业公司：(.*?)</dd>#', $html_city, $out26);
			if(!isset($out26[1]) || empty($out26[1])){
				$wuyegongsi = '';
			}else{
				$wuyegongsi = $out26[1]; 
			}
		}else{
			$wuyegongsi = $out7[1];
		}

		preg_match('#<li .*?><b>开发商</b>(.*?)</li>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			preg_match('#<dd>开 发 商：(.*?)</dd>#', $html_city, $out27);
			if(!isset($out27[1]) || empty($out27[1])){
				$kaifashang = '';
			}else{
				$kaifashang = trim(preg_replace('#<.*?>#', '', $out27[1])); 
			}
		}else{
			$kaifashang = $out8[1];
		}


		preg_match('#<li .*?><b>楼栋总数</b>(.*?)</li>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$loudongzongshu = '';
		}else{
			$loudongzongshu = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#<li .*?><b>房屋总数</b>(.*?)</li>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			preg_match('#<dd>总 层 数：(.*?)</dd>#', $html_city, $out28);
			if(!isset($out28[1]) || empty($out28[1])){
				$fangwuzongshu = '';
			}else{
				$fangwuzongshu = $out28[1]; 
			}
		}else{
			$fangwuzongshu = $out10[1];
		}

		preg_match('#<b>小区位置</b>(.*?)</li>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$fujinmeidian = '';
		}else{
			$fujinmeidian = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}

		preg_match('#<div class="jiage1">([\s\S]*?)基本信息</dt>#', $html_city, $out30);
		if(!isset($out30[1]) || empty($out30[1])){
			$xiaoquxinxi = '';
		}else{
			$xiaoquxinxi = trim(preg_replace('#<.*?>#', '', $out30[1]));
		}




		//var_dump($fujinmeidian);exit();
		
		$html_citys = $curl_class->request($url.'xiangqing/',true);
		$html_citys = mb_convert_encoding($html_citys, "UTF-8", "GBK");
		//file_put_contents('detail1.html',$html_citys);exit();
		//$html_citys = file_get_contents('detail1.html');
		preg_match('#<h3>基本信息</h3>[\s\S]*?<dl .*?>([\s\S]*?)</dl>#', $html_citys, $out11);
		if(!isset($out11[1]) || empty($out11[1])){

			preg_match('#class="name">基本信息</dt>([\s\S]*?)搜房问答</dt>#', $html_city, $out29);
			if(!isset($out29[1]) || empty($out29[1])){
				
				$jibenxinxi = '';
			}else{
				$jibenxinxi = trim(preg_replace('#<.*?>#', '', $out29[1]));
			}
		}else{
			$jibenxinxi = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}
		//var_dump($jibennxinxi);exit();

		preg_match('#<h3>配套设施</h3>[\s\S]*?<dl .*?>([\s\S]*?)</dl>#', $html_citys, $out12);
		if(!isset($out12[1]) || empty($out12[1])){
			$peitaoshebei = '';
		}else{
			$peitaoshebei = trim(preg_replace('#<.*?>#', '', $out12[1]));
		}
		preg_match('#<h3>交通状况</h3>[\s\S]*?<dl .*?>([\s\S]*?)</dl>#', $html_citys, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jiaotongzhuangkuang = '';
		}else{
			$jiaotongzhuangkuang = trim(preg_replace('#<.*?>#', '', $out13[1]));
		}
		preg_match('#<h3>周边信息</h3>[\s\S]*?<dl .*?>([\s\S]*?)</dl>#', $html_citys, $out14);
		if(!isset($out14[1]) || empty($out14[1])){
			$zhoubianxinxi = '';
		}else{
			$zhoubianxinxi = trim(preg_replace('#<.*?>#', '', $out14[1]));
		}
		//var_dump($jibennxinxi,$peitaoshebei,$jiaotongzhuangkuang,$zhoubianxinxi);exit();
		//var_dump($daikancishu7,$daikancishu30);exit();
		// for ($i=0; $i < 15; $i++) { 
		// 	$wrap = 'out'.$i;
		// 	//var_dump($wrap);exit();
		// 	var_dump(${$wrap}[1]);
		// }
		// exit();

		$jingjiren = '';
		$jingjirenpingfen = '';
		$jingjirendianhua = '';
		
		$content_field = "insert into fangtianxia_ershou_xiaoqu(title,sub,danjia,jianzhuniandai,jianzhuleixing,wuyefeiyong,wuyegongsi,kaifashang,loudongzongshu,fangwuzongshu,fujinmeidian,jingjiren,jingjirenpingfen,jingjirendianhua,parent_id,jibenxinxi,xiaoquxinxi,peitaoshebei,jiaotongzhuangkuang,zhoubianxinxi) values('{$title}','{$sub}','{$danjia}','{$jianzhuniandai}','{$jianzhuleixing}','{$wuyefeiyong}','{$wuyegongsi}','{$kaifashang}','{$loudongzongshu}','{$fangwuzongshu}','{$fujinmeidian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}',{$value['id']},'{$jibenxinxi}','{$xiaoquxinxi}','{$peitaoshebei}','{$jiaotongzhuangkuang}','{$zhoubianxinxi}')";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			$jingjiren_base = $url;
			//https://guomeidiyicheng.fang.com/house/ajaxrequest/getZhoubiangwData.php?newcode=1010035895&version=new&city=%E5%8C%97%E4%BA%AC&cityin=north&citysuo=bj
			/*经纪人采集部分*/
			preg_match('#newcode = "(.*?)"#', $html_city, $out110);
			if(!isset($out110[1]) || empty($out110[1])){
				$newcode = '';
			}else{
				$newcode =  trim(preg_replace('#<.*?>#', '', $out110[1]));
			}

			preg_match('#city = "(.*?)"#', $html_city, $out111);
			if(!isset($out111[1]) || empty($out111[1])){
				$city = '';
			}else{
				$city =  urlencode(trim(preg_replace('#<.*?>#', '', $out111[1])));
			}

			preg_match('#cityin = "(.*?)"#', $html_city, $out112);
			if(!isset($out112[1]) || empty($out112[1])){
				$cityin = '';
			}else{
				$cityin =  trim(preg_replace('#<.*?>#', '', $out112[1]));
			}

			preg_match('#citysuo = "(.*?)"#', $html_city, $out113);
			if(!isset($out113[1]) || empty($out113[1])){
				$citysuo = '';
			}else{
				$citysuo =  trim(preg_replace('#<.*?>#', '', $out113[1]));
			}

			$jingjiren_url = $jingjiren_base."house/ajaxrequest/getZhoubiangwData.php?newcode={$newcode}&version=new&city={$city}&cityin={$cityin}&citysuo={$citysuo}";
			$jingjiren_citys = $curl_class->request($jingjiren_url,true);
			//var_dump($jingjiren_citys);
			//$jingjiren = file_get_contents('jingjiren.html');
			$jingjiren_arr = json_decode($jingjiren_citys,true);
			if(!empty($jingjiren_arr['data'])) {
				preg_match_all('#href="(.*?)"#', $jingjiren_arr['data'], $jingjiren_href);
				if(!empty($jingjiren_href)){
					foreach ($jingjiren_href[1] as $kk=> $vv) {
						$content_field = "insert into fangtianxia_url_jingjiren (url,parent_id) value('{$vv}',{$value['id']})";
						//var_dump($content_field);exit();
						$insert_field = $sql_class->insertContent($vv,'fangtianxia_url_jingjiren',$content_field);

						
					}
				}
			}

			//var_dump($jingjiren_arr);exit();
			
			
		}else{
			$status = 4; //插入失败
			file_put_contents('fangtianxia_xiaoqu_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update fangtianxia_url_xiaoqu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>