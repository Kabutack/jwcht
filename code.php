<?php 
	require_once dirname(__FILE__).'/vendor/autoload.php';
	use ZCrawler\Foundation\ZCrawler;
	// include '/vendor/yuan1994/z-crawler/src/Foundation/ZCrawler.php';
	// use ZCrawler\Foundation;
	
	$config = include '/vendor/yuan1994/z-crawler/config.php';

	//获取学生学号&密码
	$username = $_GET['username'];
	$password = $_GET['password'];

	// 实例化
	$zCrawler = new ZCrawler($username, $password, $config);

	//获取实例
	$login = $zCrawler->login;

	//登录
	$path = 'code/';
	$codeUrl = $login->getCaptcha($path);//返回验证码图片保存路径
	// echo $codeUrl;

	$prefix = 'http://localhost/zafujwc/';//验证码图片路径前缀地址
	$fullcodeUrl = $prefix.$codeUrl;
	echo $fullcodeUrl;//返回验证码图片路径
 ?>