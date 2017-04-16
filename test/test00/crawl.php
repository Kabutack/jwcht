<?php
     //引入curl函数
     include 'curl.php';

     //获取cookie
     // function getCookie(){
     //         $res;
     //         $url = 'http://115.236.84.162/default2.aspx';
     //         $result = curl_request($url, '', '', 1);
     //         $res = $result['cookie'];
     //         return $res;
     //    }
     //    $cookie = getCookie();
     
     $cookie_file = dirname(__FILE__).'/cookie.txt'; 
     //$cookie_file = tempnam("tmp","cookie");
     //先获取cookies并保存
     $url = "http://115.236.84.162/default2.aspx";
     $ch = curl_init($url); //初始化
     curl_setopt($ch, CURLOPT_HEADER, 0); //不返回header部分
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
     curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
     curl_exec($ch);
     curl_close($ch);

        //获取验证码
        $code_url = 'http://115.236.84.162/CheckCode.aspx';
        $verify_code = curl_request($code_url, '', '$cookie', 0);
        // echo $verify_code;
        $fp = fopen("./verify/verifyCode.png",'w');   //把抓取到的图片文件写入本地图片文件保存
        fwrite($fp, $verify_code);
        fclose($fp);

        $url = "./verify/verifyCode.png";

        $img = file_get_contents($url, true);
        //使用图片头输出浏览器
        header("Content-Type: image/jpeg; text/html; charset=utf-8");
        echo $img;
?>