<?php 

require_once(dirname(__FILE__).'./../common.php');
require_once(dirname(__FILE__).'./../font.php');


$path = './wuba/zufang/';
while($select_result = $sql_class->querys("select * from wuba_url_zufang where status=0 limit 100")){
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
		//$html_city = file_get_contents('detail.html');
		
		if(empty($html_city)){
			$status = 2; //内容抓取为空
			$content_fieldss = "update wuba_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		
		

		preg_match('# <h1.*?>(.*?)</h1>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('wuba_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update wuba_url_zufang set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<[\s\S]*?>#', '', $out0[1]));
		

		if(preg_match('#<span class="ax-tip">安选</span>#', $html_city)){
			$title = $title.' '.'安选';
		}
		


		preg_match('#<p class="house-update-info.*?">([\s\S]*?)</p>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}else{
			$sub = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out1[1])));
		}
		//var_dump($sub);exit();

		preg_match('#租赁方式：</span><span>(.*?)</span>#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$fangshi = 0;
		}else{
			$fangshi = $out2[1];
		}
		
		//var_dump($fangshi);exit();
		preg_match('#<div class="house-pay-way f16">([\s\S]*?)</div>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out3[1])));
		}
		//var_dump($danjia);exit();


		preg_match('#房屋类型：</span><span.*?>([\s\S]*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out4[1])));
		}
		//var_dump($huxing);exit();
		preg_match('#朝向楼层：</span><span.*?>([\s\S]*?)</span>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = trim($out5[1]);
		}

	

		preg_match('#所在小区：</span><span.*?>([\s\S]*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$xiaoqumingcheng = '';
		}else{
			$xiaoqumingcheng = trim(preg_replace('#<[\s\S]*?>#', '', $out10[1]));
		}
		//var_dump($xiaoqumingcheng);exit();

		preg_match('#所属区域：</span><span.*?>([\s\S]*?)详细地址：#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out11[1])));
		}

		preg_match('#详细地址：</span><span.*?>([\s\S]*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$xiangxidizhi = '';
		}else{
			$xiangxidizhi = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out6[1])));
		}

		//var_dump($suozaiquyu);exit();
	
		
		$jingjiren_geren= ''; //个人
		preg_match('#<p class="agent-name.*?"><a[\s\S]*?href=".*?brokerId=(\d*?)&city[\s\S]*?>#', $html_city, $out13);
		if(!isset($out13[1]) || empty($out13[1])){
			//https://houserent.58.com/landlord/center?infoId=37388424197030&city=hf
			$jingjiren = '';
			preg_match('#<p class="agent-name.*?"><a[\s\S]*?href=".*?infoId=(\d*?)&city[\s\S]*?"[\s\S]*?>#', $html_city, $out113);
			//var_dump($out113);exit();
			if(isset($out113[1]) && !empty($out113[1])){
				//https://houserent.58.com/landlord/api_landlord_detail?infoId=37388424197030
				$jingjiren_geren = 'https://houserent.58.com/landlord/api_landlord_detail?infoId='.$out113[1];
			}
			
		}else{
			$jingjiren = 'https://broker.58.com/sh/detail/'.trim($out13[1]).'.shtml';
		}

		preg_match("#<p class='phone-num'>(.*?)</p>#", $html_city, $out26);
		if(!isset($out26[1]) || empty($out26[1])){

			$jingjirendianhua = '';
		}else{
			$jingjirendianhua = trim(preg_replace('#<[\s\S]*?>#', '', $out26[1]));
		}

		//var_dump($jingjiren_geren);exit();
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


			preg_match('#<ul class="agent-biz-info-list">([\s\S]*?)<div class="agent-list" id="gent-list">#', $html_jingjiren, $out25);
			if(!isset($out25[1]) || empty($out25[1])){
				$jingjirensum = '';
			}else{
				$jingjirensum = trim(preg_replace('#<[\s\S]*?>#', '', $out25[1]));
			}
		}else{
			$jingjirenmingcheng = '';
			$jingjirensuoshu = '';
			$jingjirensum = '';
			$jingjirengongzuoshijian = '';
		}

		if($jingjiren_geren){
			sleep(1);
			$html_jingjiren = file_get_contents($jingjiren_geren);
			//var_dump($html_jingjiren);exit();
			$html_jingjiren = json_decode($html_jingjiren,true);
			if(!$html_jingjiren['code']){
				$jingjiren = $jingjiren_geren;
				$jingjirenmingcheng = $html_jingjiren['data']['user']['nickName'];
				$jingjirengongzuoshijian = $html_jingjiren['data']['user']['registrationDays'];
			}
		}
		
		//var_dump($jingjirensum);exit();
		preg_match('#<!--房屋配置-->([\s\S]*?)<div class="house-word-introduce f16 c_555">#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match("#<ul class='introduce-item'>([\s\S]*?)房源描述#", $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match("#<span class='a1'>房源描述([\s\S]*?)<!-- 增加 end -->#", $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<[\s\S]*?>#', '', $out18[1]));
		}

		$date = date('Y-m-d');
		
		$content_field = "insert into wuba_ershou_zufang(title,sub,fangshi,danjia,huxing,louceng,xiaoqumingcheng,suozaiquyu,xiangxidizhi,jingjiren,jingjirenmingcheng,jingjirendianhua,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id,date) values('{$title}','{$sub}','{$fangshi}','{$danjia}','{$huxing}','{$louceng}','{$xiaoqumingcheng}','{$suozaiquyu}','{$xiangxidizhi}','{$jingjiren}','{$jingjirenmingcheng}','{$jingjirendianhua}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']},'{$date}')";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('wuba_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update wuba_url_zufang set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>