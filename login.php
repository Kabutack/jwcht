<?php
	require_once dirname(__FILE__).'/vendor/autoload.php';
	use ZCrawler\Foundation\ZCrawler;
	// include '/vendor/yuan1994/z-crawler/src/Foundation/ZCrawler.php';
	// use ZCrawler\Foundation;
	
	$config = include '/vendor/yuan1994/z-crawler/config.php';

	//获取学生学号&密码&验证码
	$username = $_GET['username'];
	$password = $_GET['password'];
	$codenum = $_GET['codenum'];

	// 实例化
	$zCrawler = new ZCrawler($username, $password, $config);

	//获取实例
	$login = $zCrawler->login;

	//获取cookie
	$login->getCookie($forceRefresh = false, $method = 'withCode');

	//使用验证码登录
	$cookie = $login->withCode($codenum);//返回GuzzleHttp\Cookie\CookieJar Cookie 对象

	echo $cookie;

?>