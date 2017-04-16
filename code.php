<?php 
	require_once dirname(__FILE__).'/vendor/autoload.php';
	use ZCrawler\Foundation\ZCrawler;
	// include '/vendor/yuan1994/z-crawler/src/Foundation/ZCrawler.php';
	// use ZCrawler\Foundation;
	
	$config = include '/vendor/yuan1994/z-crawler/config.php';

	// $username = $_POST["data.username"];
	// $password = $_POST["data.password"];
	$username = $_POST['username'];
	$password = $_POST['password'];

	// 实例化
	$zCrawler = new ZCrawler($username, $password, $config);
	// $zCrawler = new Foundation\ZCrawler($username, $password, $config);

	//获取实例
	$login = $zCrawler->login;

	//登录
	$path = 'code/';
	$codeUrl = $login->getCaptcha($path);//返回验证码图片保存路径
	// echo $codeUrl;

	// $img = file_get_contents($codeUrl, true);
	// header("Content-Type: image/jpeg; text/html; charset=utf-8");
	// echo $img;
	$fullcodeUrl = 'http://localhost/zafujwc/'.$codeUrl;
	echo $fullcodeUrl;
	echo $username;
	echo $password;
 ?>