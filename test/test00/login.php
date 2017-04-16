<?php	
	header('Content-Type:text/html;charset=gb2312');

	//引入curl函数
	include "curl.php";

	//获取提交的数据
	if ($_POST['submit']) {
		$userName = $_POST['username'];
		$passWord = $_POST['password'];
		$verifyCode = $_POST['verifycodetext'];	
	}

	//获取__VIEWSTATE
	$url = 'http://115.236.84.162/default2.aspx';
	$view_result = curl_request($url, '', '', 0);
	$pattern = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
	preg_match_all($pattern, $view_result, $matches);
	$view = $matches[1][0];

	//POST提交	
	function login($view, $userName, $passWord, $secretCode){
	     $url = 'http://115.236.84.162/default2.aspx';
	     $post['__VIEWSTATE'] = $view;
	     // $post['__VIEWSTATEGENERATOR'] = 上面获取结果;
	     $post['txtUserName'] = $userName;
	     $post['TextBox1'] = '';
	     $post['TextBox2'] = $passWord;
	     $post['txtSecretCode'] = $secretCode;
	     $post['RadioButtonList1'] = iconv('utf-8', 'gb2312', '学生');
	     $post['Button1'] = '';
	     $post['lbLanguage'] = '';
	     $post['hidPdrs'] = '';
	     $post['hidsc'] = '';
	     // $result = curl_request($url, $post, '', 1);
	     $result = curl_request($url, $post, '', 1);	     
	     print_r($result);
	     return $result;
	}

	login($view, $userName, $passWord, $verifyCode);

?>