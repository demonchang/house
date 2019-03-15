<?php 

require_once(dirname(__FILE__).'./../common.php');


while($select_result = $sql_class->querys("select * from wuba_url_xiaoqu where status=0 limit 100")){
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
			$content_fieldss = "update wuba_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}

		preg_match('#<span class="title">(.*?)</span>#', $html_city, $out0);
		if(empty($out0)){
			file_put_contents('wuba_detail.log',$url.PHP_EOL,FILE_APPEND);

			$status = 3; //解析标题失败
			$content_fieldss = "update wuba_url_xiaoqu set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//var_dump($out0);exit();
		$title = trim($out0[1]);
		preg_match('#<span class="addr">(.*?)</span>#', $html_city, $out1);
		if(!isset($out1[1]) || empty($out1[1])){
			$sub = '';
		}
		$sub = trim($out1[1]);
		//var_dump($sub);exit();

		preg_match('#<span class="price">(.*?)</span>#', $html_city, $out3);
		if(!isset($out3[1]) || empty($out3[1])){
			$danjia = '';
		}else{
			$danjia = trim($out3[1]);
		}
		//var_dump($danjia);exit();


		preg_match('#建筑年代</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out4);
		if(!isset($out4[1]) || empty($out4[1])){
			$jianzhuniandai = '';
		}else{
			$jianzhuniandai = trim($out4[1]);
		}
		//var_dump($jianzhuniandai);exit();

		preg_match('#建筑类别</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out5);
		if(!isset($out5[1]) || empty($out5[1])){
			$jianzhuleixing = '';
		}else{
			$jianzhuleixing = trim($out5[1]);
		}

		
		preg_match('#物业费用</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out6);
		if(!isset($out6[1]) || empty($out6[1])){
			$wuyefeiyong = '';
		}else{
			$wuyefeiyong = trim($out6[1]);
		}
		

		preg_match('#物业公司</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out7);
		if(!isset($out7[1]) || empty($out7[1])){
			$wuyegongsi = '';
		}else{
			$wuyegongsi = trim($out7[1]);
		}

		preg_match('#开发商</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out8);
		if(!isset($out8[1]) || empty($out8[1])){
			$kaifashang = '';
		}else{
			$kaifashang = trim($out8[1]);
		}
		//var_dump($kaifashang);exit;

		preg_match('#绿化率</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out16);
		if(!isset($out16[1]) || empty($out16[1])){
			$lvhualv = '';
		}else{
			$lvhualv = trim($out16[1]);
		}

		preg_match('#容积率</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out17);
		if(!isset($out17[1]) || empty($out17[1])){
			$rongjilv = '';
		}else{
			$rongjilv = trim($out17[1]);
		}

		preg_match('#停车位</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out18);
		if(!isset($out18[1]) || empty($out18[1])){
			$tingchewei = '';
		}else{
			$tingchewei = trim($out18[1]);
		}

		preg_match('#详细地址</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out19);
		if(!isset($out19[1]) || empty($out19[1])){
			$xiangxidizhi = '';
		}else{
			$xiangxidizhi = trim(preg_replace('#<[\s\S]*?>#', '', $out19[1]));
		}

		preg_match('#产权年限</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out20);
		if(!isset($out20[1]) || empty($out20[1])){
			$chanquannianxian = '';
		}else{
			$chanquannianxian = trim($out20[1]);
		}

		preg_match('#总住户数</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out9);
		if(!isset($out9[1]) || empty($out9[1])){
			$zongzhuhushu = '';
		}else{
			$zongzhuhushu = trim($out9[1]);
		}
		//var_dump($leixing);exit();


		preg_match('#占地面积</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out10);
		if(!isset($out10[1]) || empty($out10[1])){
			$zhandimianji = '';
		}else{
			$zhandimianji = trim($out10[1]);
		}

		preg_match('#建筑面积</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out21);
		if(!isset($out21[1]) || empty($out21[1])){
			$jianzhumianji = '';
		}else{
			$jianzhumianji = trim($out21[1]);
		}

		preg_match('#商圈区域</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out22);
		if(!isset($out22[1]) || empty($out22[1])){
			$shangquan = '';
		}else{
			$shangquan = trim(preg_replace('#<[\s\S]*?>#', '', $out22[1]));
		}


		preg_match('#产权类别</td>[\s]*?<td class="desc".*?>([\s\S]*?)</td>#', $html_city, $out23);
		if(!isset($out23[1]) || empty($out23[1])){
			$chanquanleibie = '';
		}else{
			$chanquanleibie = trim(preg_replace('#<[\s\S]*?>#', '', $out23[1]));
		}

	

	


		
		
		$content_field = "insert into wuba_ershou_xiaoqu(title,sub,danjia,shangquan,jianzhuniandai,jianzhuleixing,xiangxidizhi,wuyefeiyong,wuyegongsi,kaifashang,tingchewei,rongjilv,lvhualv,chanquanleibie,chanquannianxian,zongzhuhushu,jianzhumianji,zhandimianji,parent_id) values('{$title}','{$sub}','{$danjia}','{$shangquan}','{$jianzhuniandai}','{$jianzhuleixing}','{$xiangxidizhi}','{$wuyefeiyong}','{$wuyegongsi}','{$kaifashang}','{$tingchewei}','{$rongjilv}','{$lvhualv}','{$chanquanleibie}','{$chanquannianxian}','{$zongzhuhushu}','{$jianzhumianji}','{$zhandimianji}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确
			
		}else{
			$status = 4; //插入失败
			file_put_contents('wuba_xiaoqu_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update wuba_url_xiaoqu set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>