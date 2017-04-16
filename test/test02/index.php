<?php
	header('Content-Type:text/html;charset=gb2312');

	//含验证码识别
	$id='';
	$psw='';
	if($_POST["id"]){
		global $id;
		$id=$_POST["id"];
	}
	if($_POST["psw"]){
		global $psw; 
		$psw=$_POST["psw"];
	}
	$view2='';
	$urlcookie='';
	$name='';
	function curl_request($url,$post='',$cookie='', $returnCookie=0){
		global $id;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_REFERER, 'http://115.236.84.162/xs_main.aspx?xh='.$id);
		if($post) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if($cookie) {
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);
		if($returnCookie){
			list($header, $body) = explode("\r\n\r\n", $data, 2);
			preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
			$info['cookie']  = substr($matches[1][0], 1);
			global $urlcookie;
			$urlcookie=substr($matches[1][0], 1);
			$info['content'] = $body;
			return $info;
		}else{
			return $data;
		}
	}
	function getView(){
		$res;
		$url='http://115.236.84.162/default2.aspx';
		/*-----此处有改动------*/
		$result=curl_request($url,'','',1); //让服务器返回cookie信息   
		/*---------------------*/
		$pattern='/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is'; 
		foreach ($result as $r) {
			preg_match_all($pattern, $r, $matches);
		}
		$res=$matches[1][0];
		return $res;
	}
	/*-----验证码识别方法如下------*/
	function yzm($c){
		$url = "http://www.kejibu.org/cx/cts.php?c={$c}";  
		// $url = "http://www.unique-liu.com:8080/WhxyJw/yzm.jsp?c={$c}";  
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
/*-----------------------------*/
	function login(){
		global $id;
		global $psw;
		$url = "http://115.236.84.162/default2.aspx";
		$post['__VIEWSTATE'] = getView();
		$post['txtUserName'] = $id;
		$post['TextBox1'] ='';
		$post['TextBox2'] =$psw;
		/*-----验证码如下------*/
		global $urlcookie;
		$post['txtSecretCode'] =yzm($urlcookie); //带入cookie去执行yzm方法
		/*---------------------*/
		$post['RadioButtonList1'] = iconv('utf-8', 'gb2312', '学生');
		$post['Button1'] = '';
		$post['lbLanguage'] = '';
		$post['hidPdrs'] = '';
		$post['hidsc'] = '';
		/*-----此处有改动------*/
		$result = curl_request($url, $post, $urlcookie);
		/*---------------------*/
		print_r($result);
		return $result;
	}
	function main(){
		login();
		global $id;
		global $urlcookie;
		global $view2;
		$url="http://115.236.84.162/xscj_gc.aspx?xh={$id}&gnmkdm=N121605";
		 $result = curl_request($url, '', $urlcookie);
		$pattn='/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is'; 
		preg_match_all($pattn, $result, $ma);
		$view2=$ma[1][0];
		global $name; 
		$pa='#<span[^>]*?xhxm[^>]*?>([^<]*?)</span>#is';
		preg_match_all($pa, $result, $matche);
		$res=$matche[1][0];
		$name = substr ($res, 0,-4);
	}
	function get_td_array($table) {
		$table = preg_replace("'<table[^>]*?>'si","",$table);
		$table = preg_replace("'<tr[^>]*?>'si","",$table);
		$table = preg_replace("'<td[^>]*?>'si","",$table);
		$table = str_replace("</tr>","{tr}",$table);
		$table = str_replace("</td>","{td}",$table);
		//去掉 HTML 标记
		$table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
		//去掉空白字符
		$table = preg_replace("'([rn])[s]+'","",$table);
		$table = preg_replace('/&nbsp;/',"",$table);
		$table = str_replace(" ","",$table);
		$table = str_replace(" ","",$table);
		$table = explode('{tr}', $table);
		array_pop($table);
		foreach ($table as $key=>$tr) {
		$td = explode('{td}', $tr);
		array_pop($td);
		$td_array[] = $td;
	}
		return $td_array;
	}
	function cj(){
		main();
		global $id;
		global $urlcookie;
		global $name;
		 global $view2;
		$url="http://115.236.84.162/xscj_gc.aspx?xh={$id}&gnmkdm=N121605";
		$post['ddlXN'] = '2014-2015';
		$post['ddlXQ'] = '2';
		$post['__VIEWSTATE'] = $view2;
		$post['Button1'] ='按学期查询';
		$result =curl_request($url,$post,$urlcookie);
		$result=get_td_array($result);
		foreach($result as $v){
			if($v[8]){
				$grade .="<tr><td width=\"200px\" align=\"center\" >{$v[3]}</td><td align=\"center\" >{$v[8]}</td></tr>";
			}
		}
			print_r("<table>{$grade}</table>");
		}
	// echo cj();
	echo login();
?>