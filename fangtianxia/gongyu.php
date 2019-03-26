<?php 

require_once(dirname(__FILE__).'./../common.php');

while($select_result = $sql_class->querys("select * from fangtianxia_url_gongyu where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		$html_city = $curl_class->request($url,true);
		$html_city = mb_convert_encoding($html_city, "UTF-8", "GBK");
		//file_put_contents('detail1.html',$html_city);exit();
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update fangtianxia_url_gongyu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<h1.*?>([\s\S]*?)</h1>#', $html_city, $out0);
		
		if(empty($out0)){
			file_put_contents('fangtianxia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update fangtianxia_url_gongyu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim($out0[1]);
		if($title == '502 Bad Gateway'){
			continue;
		}
		//var_dump($title);exit();

		
		preg_match('#<div class="topheada">([\s\S]*?)<!-- 面包屑导航 start-->#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}else{
			$sub = trim(preg_replace('#<.*?>#', '', $out1[1]));
		}

		//var_dump($sub);exit();

		//<div class="bqian clearfix">
		preg_match('#<div class="tr05 mt10">([\s\S]*?)</div>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$biaoqian = '';
		}else{
			$biaoqian = trim(preg_replace('#<.*?>#', '', $out2[1]));
		}
		//var_dump($sub,$biaoqian);exit();
		preg_match('#<div class="tr01">(.*?)</div>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#<.*?>#', '', $out3[1]));
		}
		

		preg_match('#<div class="td">([\s\S]*?)<p>出租方式</p>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$fangshi = '';
		}else{
			$fangshi = trim(preg_replace('#<.*?>#', '', $out4[1]));
		}

		//var_dump($fangshi);exit();
		preg_match('#出租方式</p>([\s\S]*?)<p>户型#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#<.*?>#', '', $out4[1]));
		}


		//var_dump($danjia,$fangshi,$huxing);exit();

		preg_match('#朝向</p>([\s\S]*?)<p>楼层</p>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
				$louceng = '';
		}else{
			$louceng = trim(preg_replace('#<.*?>#', '', $out5[1]));
		}
		

		preg_match('#建筑面积</p>([\s\S]*?)<p>朝向</p>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
				$chaoxiang = '';
		}else{
			$chaoxiang = trim(preg_replace('#<.*?>#', '', $out6[1]));
		}

		preg_match('#楼层</p>([\s\S]*?)<p>装修</p>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
				$zhuangxiu = '';
		}else{
			$zhuangxiu = trim(preg_replace('#<.*?>#', '', $out7[1]));
		}



		preg_match('#户型</p>([\s\S]*?)<p>建筑面积</p>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = trim(preg_replace('#<.*?>#', '', $out8[1]));
		}
		//var_dump($chaoxiang,$zhuangxiu,$mianji,$louceng);exit();




		preg_match('#小<i></i>区:</span>([\s\S]*?)</div>#', $html_city, $out10);
		//var_dump($out10);exit();
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<.*?>#', '', $out10[1]));
		}

		preg_match('#地<i></i>址:</span>([\s\S]*?)</div>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}


		//var_dump($suozaiquyu,$xiaoqumingcheng,$mianji);exit();


		preg_match('#<span class="zf_jjname">[\s]*?<a[\s\S]*?href="([\s\S]*?)".*?>([\s\S]*?)</a></span>#', $html_city, $out13);
		//var_dump($out13);exit();
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = 'https:'.$out13[1];
			
		}
		//var_dump($jingjiren);exit();
		if($jingjiren){
			//var_dump($jingjiren);
			//https://sh.esf.fang.com/chushou

			$html_jingjiren= $curl_class->request($jingjiren,true);
			$html_jingjiren = mb_convert_encoding($html_jingjiren, "UTF-8", "GBK");
			//file_put_contents('jingjiren.html',$html_jingjiren);exit();
			//$html_jingjiren = file_get_contents('jingjiren.html');
			preg_match('#<b id="agentname">(.*?)</b>#', $html_jingjiren, $out21);
			if(!isset($out21[1]) || empty($out21[1])){
				$jingjirenmingcheng = '';
			}else{
				$jingjirenmingcheng = trim($out21[1]);
			}

			preg_match('#联系电话：<b class="redword">(.*?)</b>#', $html_jingjiren, $out22);
			if(!isset($out22[1]) || empty($out22[1])){
				$jingjirendianhua = '';
			}else{
				$jingjirendianhua = trim($out22[1]);
			}


			preg_match('#公司名称：<span>(.*?)</span>#', $html_jingjiren, $out23);
			if(!isset($out23[1]) || empty($out23[1])){
				$jingjirensuoshu = '';
			}else{
				$jingjirensuoshu = trim($out23[1]);
			}


			preg_match('#工作时间：</dt>[\s]*?<dd>(.*?)</dd>#', $html_jingjiren, $out24);
			if(!isset($out24[1]) || empty($out24[1])){
				$jingjirengongzuoshijian = '';
			}else{
				$jingjirengongzuoshijian = trim($out24[1]);
			}

			//<input type="hidden" id="importantesfprojname" value="<span style='padding-right:10px;'>安华西里(19)</span><span style='padding-right:10px;'>安华里(4)</span><span style='padding-right:10px;'>安贞西里(2)</span>" />


			preg_match('#经纪人信息 start-->([\s\S]*?)<!-- 经纪人信息 end-->#', $html_jingjiren, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jingjirensum = '';
			}else{
				preg_match('#<input type="hidden" id="importantesfprojname" value="(.*?)" />#', $html_jingjiren, $out226);
				if(!isset($out226[1]) || empty($out226[1])){
					$jingjirenpingfen_zhongdian = '';
				}else{
					$jingjirenpingfen_zhongdian =  $out226[1];
				}

				$out25[1] = preg_replace('#<span class="grayword" id="importantgrayword"></span>#', $jingjirenpingfen_zhongdian, $out25[1]);
				$jingjirensum = trim(preg_replace('#-->#', '', preg_replace('#<[^<].*?>#', '', $out25[1])));
			}

			//var_dump($jingjirensum);exit();
			preg_match('# <!-- 客户评价 start -->([\s\S]*?)<!-- 客户评价 end -->#', $html_jingjiren, $out26);
			if(!isset($out26[1]) || empty($out26[1])){
				$jingjirenpingfen = '';
			}else{
				$jingjirenpingfen = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[^<].*?>#', '', $out26[1])));
			}

			//var_dump($jingjirenpingfen,$jingjirensum,$jingjirensuoshu,$jingjirendianhua,$jingjirenmingcheng);exit();

		
		}else{
			$jingjirensum = '';
			$jingjirenmingcheng = '';
			$jingjirendianhua = '';
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';
		}

		if(!$jingjirenmingcheng){
			preg_match('#<div class="tr04 mt20">[\s]*?<span>(.*?)</span><em>.*?</em>#', $html_city, $out116);
			if(!isset($out116[1]) || empty($out116[1])){
				$jingjirenmingcheng = '';
			}else{
				$jingjirenmingcheng =  trim(preg_replace('#<.*?>#', '', $out116[1]));
			}

			preg_match('#<div class="tr04 mt20">[\s]*?<span>.*?</span><em>(.*?)</em>#', $html_city, $out117);
			if(!isset($out117[1]) || empty($out117[1])){
				$jingjirendianhua = '';
			}else{
				$jingjirendianhua =  trim(preg_replace('#<.*?>#', '', $out117[1]));
			}
		}
		
		

		//var_dump($jingjirendianhua,$jingjirenmingcheng);exit();

		preg_match('#<!--房源描述 begin-->([\s\S]*?)<!--房源end begin-->#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}



		preg_match('#房源配套([\s\S]*?)<!--房源描述 begin-->#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(str_replace(' ', '',preg_replace('#<.*?>#', '', $out18[1])));
		}



		
		
		$content_field = "insert into fangtianxia_ershou_gongyu(title,sub,biaoqian,danjia,fangshi,huxing,louceng,chaoxiang,zhuangxiu,mianji,xiaoqumingcheng,suozaiquyu,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$biaoqian}','{$danjia}','{$fangshi}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$xiaoqumingcheng}','{$suozaiquyu}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确

		}else{
			$status = 4; //插入失败
			file_put_contents('fangtianxia_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update fangtianxia_url_gongyu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>