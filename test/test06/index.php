<html>
	<head>
		<?php
			//随机数命名，防止文件重名，获取cookie文件
			$rd=rand(0,5000);
			$cookie_file = __DIR__."\\"."$rd.cookie";
			while (file_exists($cookie_file)) {
				$rd=rand(0,5000);
				$cookie_file = __DIR__."\\"."$rd.cookie";
			}
			$login_url = "http://115.236.84.162/default2.aspx";
			$verify_code_url = "http://115.236.84.162/CheckCode.aspx";
			$curl = curl_init();
			$timeout = 5;
			$headers[] = 'Accept: application/xaml+xml, text/html, */*';
			$headers[] = 'Connection: Keep-Alive';
			$headers[] = 'Accept-Language: zh-CN';
			$headers[] = 'Host: localhost';
			$headers[] = 'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)';
			$headers[] = 'UA-CPU: AMD64';
			$headers[] = 'Accept-Encoding: gzip, deflate';
			//获取验证码，并保存
			curl_setopt($curl, CURLOPT_HTTPHEADER , $headers);
			curl_setopt($curl, CURLOPT_URL, $login_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); //获取COOKIE并存储
			curl_exec($curl);//执行curl
			curl_close($curl);
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $verify_code_url);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$img = curl_exec($curl);
			$picname = $rd.".jpg";
			$fp = fopen("$picname","w");
			fwrite($fp, $img);
			fclose($fp);
			curl_close($curl);
			$img = "<img src=$picname />";
		?>
	</head>
	<body>
		<form action="get.php" method="POST">
			学号：<input type="text" name="stunum"><br/>
			密码：<input type="password" name="stupwd"><br/>
			验证码：<input type="text" name="code"/>
			<input type="hidden" name="cookie_file" value="<?php echo "$cookie_file" ?>">
			<input type="hidden" name="picname" value="<?php echo "$picname" ?>">
			<?php 
				echo "$img";
			?>
			<br/>
			<input type="submit" value="确认"/>
		</form>
	</body>
	<br/>
</html>