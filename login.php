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
?>