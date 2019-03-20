<?php 

require_once(dirname(__FILE__).'./../common.php');



$path = './wuba/';
while($select_result = $sql_class->querys("select * from wuba_url where status=0 limit 100")){
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
		//$html_city = file_get_contents('detail.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update wuba_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		
		preg_match('#<a class="logo fl" href="(.*?)/house\.shtml"#',$html_city,$out31);
		if(isset($out1[1]) && !empty($out1[1])){
			$base_url = $out31[1];
		}else{
			$base_url ='';
		}
		

		preg_match('# <h1.*?>(.*?)</h1>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('wuba_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update wuba_url set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<[\s\S]*?>#', '', $out0[1]));
		

		preg_match('#<p class="house-update-info">([\s\S]*?)</p>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = 0;
		}else{
			$sub = trim(preg_replace('#<[\s\S]*?>#', '', $out1[1]));
		}
		//var_dump($sub);exit();

		preg_match('#<meta name="description" content=".*?售价：(.*?)万元.*?"#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$zongjia = 0;
		}else{
			$zongjia = $out2[1];
		}
		
		//var_dump($out2);exit();
		preg_match('#<meta name="description" content=".*?售价：.*?万元（(.*?)）；.*?"#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim($out3[1]);
		}
		//var_dump($danjia);exit();


		preg_match('#<p class="room">[\s\S]*?<span class="main">([\s\S]*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim($out4[1]);
		}
		//var_dump($huxing);exit();
		preg_match('#<p class="room">[\s\S]*?<span class="sub">([\s\S]*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = trim($out5[1]);
		}

		preg_match('#<p class="toward">[\s\S]*?<span class="main">([\s\S]*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = trim($out6[1]);
		}

		preg_match('#<p class="area">[\s\S]*?<span class="sub">([\s\S]*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = trim($out7[1]);
		}

		preg_match('#<p class="area">[\s\S]*?<span class="main">([\s\S]*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = trim($out8[1]);
		}


		preg_match('#产权年限</span>[\s\S]*?<span.*?>(.*?)年</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = trim($out9[1]);
		}
		//var_dump($leixing);exit();


		preg_match('#小区：</span>[\s\S]*?<span.*?>([\s\S]*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<[\s\S]*?>#', '', $out10[1]));
		}
		//var_dump($xiaoqumingcheng);exit();

		preg_match('#位置：</span>[\s\S]*?<span.*?>([\s\S]*?)</span>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<[\s\S]*?>#', '', $out11[1]));
		}

		//var_dump($suozaiquyu);exit();
	
		$kanfangshijian = '';
		

		preg_match('#电话归属地 [\s\S]*?<a href="(.*?)"[\s\S]*?>[\s]*?查看TA的店铺#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = trim(preg_replace('#<[\s\S]*?>#', '', $out13[1]));
		}


		preg_match("#<p class='phone-num'>(.*?)</p>#", $html_city, $out26);
		if(!isset($out26[1]) || empty($out26[1])){
			$jingjirendianhua = '';
		}else{
			$jingjirendianhua = trim(preg_replace('#<[\s\S]*?>#', '', $out26[1]));
		}


		if($jingjiren){

			sleep(1);
			$html_jingjiren = $curl_class->request($jingjiren);
			//file_put_contents('jingjiren.html',$html_jingjiren);exit();
			preg_match('#<div class="user-name">([\s\S]*?)<span#', $html_jingjiren, $out23);
			if(!isset($out23[1]) || empty($out23[1])){
				$jingjirenmingcheng = '';
			}else{
				$jingjirenmingcheng = trim(preg_replace('#<[\s\S]*?>#', '', $out23[1]));
			}

			preg_match('#所属公司<span class="u-msg">(.*?)</span>#', $html_jingjiren, $out24);
			if(!isset($out24[1]) || empty($out24[1])){
				$jingjirensuoshu = '';
			}else{
				$jingjirensuoshu = trim(preg_replace('#<[\s\S]*?>#', '', $out24[1]));
			}

			preg_match('#已在58服务[\s]*?<span class="c_red_1 fb">(.*?)</span>#', $html_jingjiren, $out27);
			if(!isset($out27[1]) || empty($out27[1])){
				$jingjirengongzuoshijian = '';
			}else{
				$jingjirengongzuoshijian = trim(preg_replace('#<[\s\S]*?>#', '', $out27[1]));
			}


			preg_match('# <ul class="agent-biz-info-list">([\s\S]*?)<div class="agent-list" id="gent-list">#', $html_jingjiren, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jingjirensum = '';
			}else{
				$jingjirensum = trim(preg_replace('#<[\s\S]*?>#', '', $out25[1]));
			}
		}else{
			$jingjirenmingcheng = '';
			$jingjirensuoshu = '';
			$jingjirensum = '';
		}
		

		preg_match('#<!-- 概况 start -->([\s\S]*?)<!-- 概况 end -->#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#描述</h3>([\s\S]*?)<!-- 描述 end -->#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('#费用</h3>([\s\S]*?)<!-- 费用 end -->#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<[\s\S]*?>#', '', $out18[1]));
		}



		/*小区信息采集*/
	
		preg_match('#<h3 class="xiaoqu-name">[\s\S]*?href="([\s\S]*?)"[\s\S]*?</h3>#', $html_city, $out22);
		//$xiaoqu_url = $base_url.$out22[1];
		//var_dump($xiaoqu_url);exit();
		if(!isset($out22[1]) || empty($out22[1])){
			$xiaoqu_url = '';
		}else{
			$xiaoqu_url = $base_url.$out22[1];

			$content_url_field = "select * from wuba_url_xiaoqu where url='{$xiaoqu_url}'";
			$select_result = $sql_class->querys($content_url_field);
			//var_dump($select_result);exit();
			if(empty($select_result)){
				$content_field = "insert into wuba_url_xiaoqu(url,parent_id) values('{$xiaoqu_url}',{$value['parent_id']})";
				//var_dump($content_field);exit();
				$select_result = $sql_class->insert($content_field);
				
			}
		}

		

		//var_dump(111);exit();


		
		
		$content_field = "insert into wuba_ershou_zaishou(title,sub,zongjia,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,kanfangshijian,jingjiren,jingjirenmingcheng,jingjirendianhua,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$zongjia}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$kanfangshijian}','{$jingjiren}','{$jingjirenmingcheng}','{$jingjirendianhua}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('wuba_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update wuba_url set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>