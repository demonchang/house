<?php 

require_once(dirname(__FILE__).'./../common.php');
require_once(dirname(__FILE__).'./../font.php');


$path = './wuba/gongyu/';
while($select_result = $sql_class->querys("select * from wuba_url_gongyu where status=0 limit 100")){
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
			$content_fieldss = "update wuba_url_gongyu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		
		

		preg_match('# <h2.*?>(.*?)</h2>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('wuba_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update wuba_url_gongyu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		
		$title = trim(preg_replace('#<[\s\S]*?>#', '', $out0[1]));
		

		if(preg_match('#class="anxuan-pic"#', $html_city)){
			$title = $title.' '.'安选';
		}
		


		preg_match('#<div class="tags">([\s\S]*?)<div class="detail_headercon">#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}else{
			$sub = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out1[1])));
		}
		//var_dump($sub);exit();

		preg_match('#<h2 class="strongbox">【(.*?)】#', $html_city, $out2);
		if(!isset($out2[1]) || empty($out2[1])){
			$fangshi = '';
		}else{
			$fangshi = $out2[1];
		}
		
		//var_dump($fangshi);exit();
		preg_match('#<span class="price strongbox">([\s\S]*?)</div>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim(preg_replace('#<[\s\S]*?>#', '', $out3[1]));
		}
		//var_dump($danjia);exit();


		preg_match('#厅室</i><span.*?>([\s\S]*?)</span>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$huxing = '';
		}else{
			$huxing = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out4[1])));
		}
		//var_dump($danjia);exit();
		preg_match('#楼层</i><span.*?>([\s\S]*?)地址</i>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$louceng = '';
		}else{
			$louceng = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out5[1])));
		}


		preg_match('#面积</i><span.*?>([\s\S]*?)</span>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$mianji = '';
		}else{
			$mianji = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out7[1])));
		}

	

		preg_match('#<span class="name">(.*?)</span>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$gongyumingcheng = '';
		}else{
			$gongyumingcheng = trim(preg_replace('#<[\s\S]*?>#', '', $out10[1]));
		}
		//var_dump($gongyumingcheng);exit();

		preg_match('#地址</i><span.*?>([\s\S]*?)</span>#', $html_city, $out11);
		if(!isset($out11[1]) || empty($out11[1])){
			$suozaiquyu = '';
		}else{
			$suozaiquyu = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out11[1])));
		}

		preg_match('#交通</i><span.*?>([\s\S]*?)</span>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$xiangxidizhi = '';
		}else{
			$xiangxidizhi = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[\s\S]*?>#', '', $out6[1])));
		}

		
		//var_dump($suozaiquyu);exit();
	
		
		
		//var_dump($jingjirensum);exit();
		preg_match('#房屋配置</h4>([\s\S]*?)<div>房屋图片#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$jibenshuxing = '';
		}else{
			$jibenshuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out16[1]));
		}
		//var_dump($jibenshuxing);exit();

		preg_match("#配套服务</div>([\s\S]*?)地图</span>#", $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$jiaoyishuxing = '';
		}else{
			$jiaoyishuxing =  trim(preg_replace('#<[\s\S]*?>#', '', $out17[1]));
		}
		//var_dump($jiaoyishuxing);exit();


		preg_match("#房源描述</h4>([\s\S]*?)房屋资料</h4>#", $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$fangyuantese = '';
		}else{
			$fangyuantese =  trim(preg_replace('#<[\s\S]*?>#', '', $out18[1]));
		}

		$date = date('Y-m-d');
		
		$content_field = "insert into wuba_ershou_gongyu(title,sub,fangshi,danjia,huxing,louceng,mianji,gongyumingcheng,suozaiquyu,xiangxidizhi,jibenshuxing,jiaoyishuxing,fangyuantese,parent_id,date) values('{$title}','{$sub}','{$fangshi}','{$danjia}','{$huxing}','{$louceng}','{$mianji}','{$gongyumingcheng}','{$suozaiquyu}','{$xiangxidizhi}','{$jibenshuxing}','{$jiaoyishuxing}','{$fangyuantese}',{$value['id']},'{$date}')";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('wuba_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update wuba_url_gongyu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>