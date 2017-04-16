<?php
	date_default_timezone_set("Asia/Shanghai");
	$code = $_REQUEST['code'];
	$stunum = $_REQUEST['stunum'];
	$stupwd = $_REQUEST['stupwd'];
	$cookie_file = $_REQUEST['cookie_file'];
	$picname = $_REQUEST['picname'];
	unlink($picname);
	$fp = fopen("record.txt", "a");
	$record_content = "$stunum"."----".date('Y-m-d  h:i:sa')."\r\n";
	fwrite($fp, $record_content);
	fclose($fp);
	if(isset($code))
	{
	//模拟登录
	$post = "USERNAME=$stunum&PASSWORD=$stupwd&useDogCode=&useDogCode=&RANDOMCODE=$code";
	$curl = curl_init();
	$url = "http://localhost/Logon.do?method=logon";
	$headers;
	$headers[] = 'Accept: application/xaml+xml, text/html, */*';
	$headers[] = 'Connection: Keep-Alive';
	$headers[] = 'Accept-Language: zh-CN';
	$headers[] = 'Cache-Control: no-cache';
	$headers[] = 'Host: localhost';
	$headers[] = 'Referer: http://localhost/';
	$headers[] = 'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)';
	$headers[] = 'Accept-Encoding: gzip, deflate';
	curl_setopt($curl, CURLOPT_HTTPHEADER , $headers);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_exec($curl);
	curl_close($curl);
	echo "<br/>";
?>