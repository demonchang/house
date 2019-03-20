<?php 

require_once(dirname(__FILE__).'./../common.php');

while($select_result = $sql_class->querys("select * from lianjia_url_zufang where status=0 limit 100")){
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
			$content_fieldss = "update lianjia_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<p class="content__title">(.*?)</p>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('lianjia_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update lianjia_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//var_dump($out);exit();
		$title = trim($out0[1]);
		preg_match('#<div class="content__subtitle">([\s\S]*?)</div>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}else{
			$sub = trim(preg_replace('#<.*?>#', '', $out1[1]));
		}
		

	
		preg_match('#<p class="content__aside--title">([\s\S]*?)</p>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#<.*?>#', '', $out3[1]));
		}
		


		preg_match('#<span><i class="typ"></i>(.*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim($out4[1]);
		}

		preg_match('#<span><i class="house"></i>(.*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$fangshi = '';
		}else{
			$fangshi = trim($out5[1]);
		}

		preg_match('#<span><i class="orient"></i>(.*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$chaoxiang = '';
		}else{
			$chaoxiang = trim($out6[1]);
		}

		preg_match('#<p class="content__aside--tags">([\s\S]*?)</p>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$biaoqian = '';
		}else{
			$biaoqian = trim(preg_replace('#<.*?>#', '', $out7[1]));
		}

		preg_match('#<span><i class="area"></i>(.*?)</span>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$mianji = '';
		}else{
			$mianji = $out8[1];
		}



		preg_match('# <span class="contact__im" data-el="callIM" data-im_id="(.*?)"#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
				$jingjiren = '';	
		}else{
			$jingjiren = 'https://dianpu.lianjia.com/'.$out13[1];
		}

		if($jingjiren){
			//var_dump($jingjiren);

			$html_jingjiren= $curl_class->request($jingjiren);
			preg_match('#<div class="agent-name clear-fl".*?><a.*?>(.*?)</a>(.*?)</span>#', $html_jingjiren, $out21);
			if(!isset($out21[1]) || empty($out21[1])){
				$jingjirenmingcheng ='';
			}else{
				$jingjirenmingcheng = $out21[1];
			}

			preg_match('#联系电话:&nbsp;&nbsp;(.*?)</span>#', $html_jingjiren, $out22);
			if(!isset($out22[1]) || empty($out22[1])){
				$jingjirendianhua = '';
			}else{
				$jingjirendianhua = $out22[1];
			}


			preg_match('#所属门店:&nbsp;<i id="icon_pin"></i></span><a data-coord="" id="mapShow"><span>(.*?)</span>#', $html_jingjiren, $out23);
			if(!isset($out23[1]) || empty($out23[1])){
				$jingjirensuoshu = '';
			}else{
				$jingjirensuoshu = $out23[1];
			}


			preg_match('#入职年限:&nbsp;&nbsp;</span>(.*?)&nbsp;#', $html_jingjiren, $out24);
			if(!isset($out24[1]) || empty($out24[1])){
				$jingjirengongzuoshijian = '';
			}else{
				$jingjirengongzuoshijian = $out24[1];
			}


			preg_match('#<div class="info_bottom">([\s\S]*?)<div class="comment_tab con-box">#', $html_jingjiren, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jingjirensum = '';
			}else{
				$jingjirensum = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[^<].*?>#', '', $out25[1])));
			}

			$jingjirenpingfen = '';
		}else{
			$jingjirensum = '';
			$jingjirenmingcheng = '';
			$jingjirendianhua = '';
			$jingjirenpingfen = '';
			$jingjirensuoshu = '';
			$jingjirengongzuoshijian = '';
		}




		preg_match('#<h3 id="info">房屋信息</h3>([\s\S]*?)<!-- 配套设施列表 -->#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  preg_replace('#&nbsp;#', '', trim(preg_replace('#<.*?>#', '', $out16[1])));
		}
		//var_dump($jibenshuxing);exit();

		preg_match('#<!-- 配套设施列表 -->([\s\S]*?)<!-- 房源描述数据 -->#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			//<li class="fl oneline television_no ">没有的样式

			$jiaoyishuxing =  trim(preg_replace('#<.*?>#', '', preg_replace('#<li class="fl oneline .*?_no ">.*?</li>#', '', $out17[1])));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match('# <h3>房源描述</h3>([\s\S]*?)<!-- 右侧黄金展位 -->#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim( str_replace(' ', '', preg_replace('#-->#', '', preg_replace('#<.*?>#', '', $out18[1]))));
		}

	


		
		
		$content_field = "insert into lianjia_ershou_zufang(title,sub,danjia,huxing,fangshi,chaoxiang,biaoqian,mianji,jingjiren,jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id) values('{$title}','{$sub}','{$danjia}','{$huxing}','{$fangshi}','{$chaoxiang}','{$biaoqian}','{$mianji}','{$jingjiren}','{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('lianjia_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update lianjia_url_zufang set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>