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

		
		preg_match('#<div class="sub".*?>(.*?)</div>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}else{
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
			$jingjiren = $out13[1];
			if(!empty($jingjiren)){
				preg_match('#(.*?)\/chushou.*?#',$url,$outjingjiren);

				$jingjiren = $outjingjiren[1].$jingjiren;
			}
		}

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



		preg_match('#<div class="mscont">([\s\S]*?)房源图片#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(str_replace(' ', '',preg_replace('#<.*?>#', '', $out18[1])));
		}



		
		
		$content_field = "insert into fangtianxia_ershou_zaishou(title,sub,zongjia,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,kanfangshijian,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$zongjia}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$kanfangshijian}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$fangyuantese}',{$value['id']})";
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