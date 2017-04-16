<?php 
    header("Content-type: text/html; charset=gb2312");
    session_start();
    $id=session_id();
    $_SESSION['id']=$id;
    // echo $_SESSION['id'];
?>
<?php 
    $cookie = dirname(__FILE__) . '/cookie/'.$_SESSION['id'].'.txt';   
    $verify_code_url = "http://115.236.84.162/CheckCode.aspx";//教务处验证码地址
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $verify_code_url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);//保存cookie
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $img = curl_exec($curl);//执行curl
    curl_close($curl);
    $fp = fopen("/verifyCode/verifyCode.jpg","w");//网站位置，视情况而定
    fwrite($fp,$img);
    fclose($fp);
    $shibie_code_url='http://www.kejibu.org:8080/WhxyJw/yzm.jsp?c=&url=http://115.159.53.241/mail/verifyCode.jpg';//验证码在线识别，感谢老司机。详情请戳http://www.unique-liu.com/211.html
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $shibie_code_url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $re = curl_exec($curl);
    curl_close($curl);
    
    $re=trim($re);
    // echo $re;
    function login_post($url,$cookie,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
        curl_setopt($ch, CURLOPT_REFERER, 'http://210.44.176.46/'); //REFERER改成你们学校教务系统
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    $url="http://210.44.176.46/default2.aspx";//你的学校教务系统地址
    $con1=login_post($url,$cookie,'');
    preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
    $post=array(
        '__VIEWSTATE'=>$view[1][0],
        'txtUserName'=>'13XXXXX',//学号
        'TextBox2'=>'XXXXX',//密码
        'txtSecretCode'=>$re,//验证码
        'RadioButtonList1'=>'%D1%A7%C9%FA',
        'Button1'=>'',
        'lbLanguage'=>'',
        'hidPdrs'=>'',
        'hidsc'=>''
    );
    // var_dump($post);
    $con2=login_post($url,$cookie,http_build_query($post));
    // echo $con2;
    preg_match_all('/<span id="xhxm">([^<>]+)/', $con2, $xm);
    $xm[1][0]=substr($xm[1][0],0,-4);
    // echo $xm[1][0];
    $url2="http://210.44.176.46/xscjcx.aspx?xh="."13XXXXX"."&xm=".$xm[1][0]; //成绩请求URL
    $viewstate=login_post($url2,$cookie,'');
    preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $viewstate, $vs);
    // var_dump($vs);
    $state=$vs[1][0];
    $post=array(
        '__EVENTTARGET'=>'',
        '__EVENTARGUMENT'=>'',
        '__VIEWSTATE'=>$state,
        'hidLanguage'=>'',
        'ddlXN'=>'2015-2016',
        'ddlXQ'=>'2',
        'ddl_kcxz'=>'',
        'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8'
    );
    // var_dump($post);
    $content=login_post($url2,$cookie,http_build_query($post));
    preg_match_all('/<td>([^<>]+)/', $content, $cj);
    $num=count($cj[1]);//91
    echo $num;//所有<td>的数量
    if($num>143){
        //发邮件
        require 'PHPMailerAutoload.php';                 //PHPMAIL部分
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();   
        $mail->CharSet = "UTF-8";  //字符集
        $mail->setLanguage('zh_cn','/language');
        // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers //视情况而定
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'admin@admin.com';                 // SMTP username 
        $mail->Password = 'password';                           // SMTP password //发件箱密码
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->setFrom('admin@admin.com', 'GJJ');        //发件人邮箱和昵称
        // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress('1392552862@qq.com');               // Name is optional //收件人邮箱
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = '高嘉君同学';  //主题
        $mail->Body = '出成绩了';  //内容
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if(!$mail->send()) {
            echo '邮件未发送';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            echo '<br>';
        } else {
            echo '邮件已发送'.'<br>';
        }
    }
    else{
        echo "未抓到成绩"."<br>";
    }
    $file="/home/wwwroot/default/mail/verifyCode.jpg";
    unlink($file);//删除验证码
?>