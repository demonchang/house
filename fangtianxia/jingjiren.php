<?php 

require_once(dirname(__FILE__).'./../common.php');

while($select_result = $sql_class->querys("select * from fangtianxia_url_jingjiren where status=0 limit 100")){
	//var_dump($select_result);exit();
	foreach ($select_result as $key => $value) {
		$url = $value['url'];


		$html_jingjiren= $curl_class->request($url,true);
		$html_jingjiren = mb_convert_encoding($html_jingjiren, "UTF-8", "GBK");
		//file_put_contents('jingjiren.html',$html_jingjiren);exit();
		//$html_jingjiren = file_get_contents('jingjiren.html');
		preg_match('#<b id="agentname">(.*?)</b>#', $html_jingjiren, $out21);
		if(!isset($out21[1]) || empty($out21[1])){
			$status = 2; //内容抓取为空
			$content_fieldss = "update fangtianxia_url_jingjiren set status={$status} where id={$value['id']}";
			$sql_class->update($content_fieldss);
			continue;

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


		preg_match('#经纪人信息 start-->([\s\S]*?)<!-- 经纪人信息 end-->#', $html_jingjiren, $out25);
		if(!isset($out25[1]) || empty($out25[1])){
			$jingjirensum = '';
		}else{
			$jingjirensum = trim(preg_replace('#-->#', '', preg_replace('#<[^<].*?>#', '', $out25[1])));
		}


		preg_match('# <!-- 客户评价 start -->([\s\S]*?)<!-- 客户评价 end -->#', $html_jingjiren, $out26);
		if(!isset($out26[1]) || empty($out26[1])){
			$jingjirenpingfen = '';
		}else{
			$jingjirenpingfen = trim(preg_replace('#&nbsp;#', '', preg_replace('#<[^<].*?>#', '', $out26[1])));
		}

		//var_dump($jingjirenpingfen,$jingjirensum,$jingjirensuoshu,$jingjirendianhua,$jingjirenmingcheng);exit();


		
		


		
		
		$content_field = "insert into fangtianxia_xiaoqu_jingjiren(jingjirenpingfen,jingjirendianhua,jingjirenmingcheng,jingjirensuoshu,jingjirengongzuoshijian,jingjirensum,parent_id) values('{$jingjirenpingfen}','{$jingjirendianhua}','{$jingjirenmingcheng}','{$jingjirensuoshu}','{$jingjirengongzuoshijian}','{$jingjirensum}',{$value['id']})";
		//var_dump($content_field);exit();
		$select_result = $sql_class->insert($content_field);
		if($select_result){
			$status = 1; //正确

		}else{
			$status = 4; //插入失败
			file_put_contents('fangtianxia_sql.log',$content_field.PHP_EOL,FILE_APPEND);

		}
		
		$content_fieldss = "update fangtianxia_url_jingjiren set status={$status} where id={$value['id']}";
		$sql_class->update($content_fieldss);

	}
}


 ?>