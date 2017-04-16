<?php 
    session_start();
    $id=session_id();//获取当前会话ID
    $_SESSION['id']=$id;
?>
<?php 
    $cookie = dirname(__FILE__) . '/cookie/'.$_SESSION['id'].'.txt'; //cookie路径  
    $verify_code_url = "http://115.236.84.162/CheckCode.aspx"; //验证码地址
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $verify_code_url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $img = curl_exec($curl);  //执行curl
    curl_close($curl);
    $fp = fopen("verifyCode.jpg","w");  //文件名
    fwrite($fp,$img);  //写入文件
    fclose($fp);

    $url = "./verifyCode.jpg";

    $img = file_get_contents($url, true);
    //使用图片头输出浏览器
    header("Content-Type: image/jpeg; text/html; charset=utf-8");
    echo $img;

?>