<?php 

require_once(dirname(__FILE__).'./../common.php');
require_once(dirname(__FILE__).'./../font.php');
$path = './ganji/';
while($select_result = $sql_class->querys("select * from ganji_url_zufang where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];
		//var_dump($url);
		// if($key == 10){
		// 	exit();
		// }
		sleep(1);
		$html_city = $curl_class->request($url);
		$html_city = fontDecode($html_city);
		//file_put_contents('detail.html',$html_city);exit();
		//file_put_contents($path.$value['id'].'.html',$html_city);
		//$html_city = file_get_contents('detail1.html');

		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update ganji_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<!--房源标题start-->[\s\S]*?<i>([\s\S]*?)</i>[\s\S]*?<!--房源标题end-->#', $html_city, $out0);

		if(empty($out0)){
			file_put_contents('ganji_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update ganji_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<.*?>#', '', $out0[1]));
		

		preg_match('#<li class="date">(.*?)</li>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = 0;
		}else{
			$sub = $out1[1];
		}
		

		preg_match('#<div class="er-card-pay">([\s\S]*?)<ul class="er-list f-clear">#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#&nbsp;#', '', preg_replace('#<.*?>#', '', $out3[1])));
		}
		

		preg_match('#户<i class="space"></i>型：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = $out4[1];
		}

		preg_match('#楼<i class="space"></i>层：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = $out5[1];
		}

		preg_match('#朝<i class="space"></i>向：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = $out6[1];
		}

		preg_match('#装修情况：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$zhuangxiu = '';
		}else{
			$zhuangxiu = $out7[1];
		}

		preg_match('#面<i class="space"></i>积：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}


		preg_match('#产<i class="space"></i>权：</span>[\s\S]*?class="content.*?">(.*?)</span>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$leixing = '';
		}else{
			$leixing = $out9[1];
		}
		//var_dump($leixing);exit();


		preg_match('#小区名称：[\s\S]*?<span class="content.*?">([\s\S]*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<.*?>#', '', $out10[1]));
		}

		preg_match('#所在地址：[\s\S]*?<span class="content.*?">([\s\S]*?)</span>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#<.*?>#', '', $out11[1]));
		}


		

		preg_match('#<div class="name">[\s\S]*?href="(.*?)"[\s\S]*?</a>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			$jingjiren = '';
		}else{
			$jingjiren = 'http:'.trim($out13[1]);
		}



		if($jingjiren){
			//var_dump($jingjiren);
			//https://sh.esf.fang.com/chushou
			$html_jingjiren = '';
			$jingjirenmingcheng = '';
			$count_jingjiren = 5;
			sleep(1);
			while ($count_jingjiren) {
				$html_jingjiren_res= $curl_class->request($jingjiren);
				
				if($html_jingjiren_res){
 					$html_jingjiren = $html_jingjiren_res;

 					preg_match('#<span class="name-text">(.*?)</span>#', $html_jingjiren, $out121);
					if(!isset($out121[1]) || empty($out121[1])){
						$jingjirenmingcheng = '';
					}else{
						$jingjirenmingcheng = trim($out121[1]);
						break;
					}
					
				}else{
					sleep($count_jingjiren);
				}
				$count_jingjiren--;
			}


			
			//file_put_contents('jingjiren.html',$html_jingjiren);exit();
			//$html_jingjiren = file_get_contents('jingjiren.html');
			

			preg_match('#<a class="phone_num js_person_phone".*?>(.*?)</a>#', $html_city, $out122);
			if(!isset($out122[1]) || empty($out122[1])){
				$jingjirendianhua = '';
			}else{
				$jingjirendianhua = trim($out122[1]);
			}


			preg_match('#经纪公司：</span>(.*?)</p>#', $html_jingjiren, $out123);
			if(!isset($out123[1]) || empty($out123[1])){
				$jingjirensuoshu = '';
			}else{
				$jingjirensuoshu = trim($out123[1]);
			}


			
			$jingjirengongzuoshijian = '';
			


			preg_match('#<div class="agent-info">([\s\S]*?)<div class="agent-business">#', $html_jingjiren, $out125);
			if(!isset($out125[1]) || empty($out125[1])){
				$jingjirensum = '';
			}else{
				$jingjirensum = trim(preg_replace('#-->#', '', preg_replace('#<[^<].*?>#', '', $out125[1])));
			}


			
			$jingjirenpingfen = '';
			

			//var_dump($jingjirenpingfen,$jingjirensum,$jingjirensuoshu,$jingjirendianhua,$jingjirenmingcheng);exit();

		
		}else{
			$jingjirensum = '';
			$jingjirenmingcheng = '';
			$jingjirendianhua = '';
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';
		}

		//var_dump($jingjiren);exit();

		

		preg_match('#房屋描述</h3>([\s\S]*?)<!--<div class="describe fang-source-num">#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<.*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#<div class="f-group" id="js-house-peizhi">([\s\S]*?)<div class="f-group" id="js-house-describe">#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<li class="item dele">.*?</li>#', '', preg_replace('#<.*?>#', '', $out17[1])));
		}
		//var_dump($jiaoyishuxing);exit();







		
		
		$content_field = "insert into ganji_ershou_zufang(title,sub,danjia,huxing,louceng,chaoxiang,zhuangxiu,mianji,leixing,xiaoqumingcheng,suozaiquyu,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,jiaoyishuxing,parent_id) values('{$title}','{$sub}','{$danjia}','{$huxing}','{$louceng}','{$chaoxiang}','{$zhuangxiu}','{$mianji}','{$leixing}','{$xiaoqumingcheng}','{$suozaiquyu}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('ganji_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update ganji_url_zufang set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>