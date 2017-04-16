<?php 
    header("Content-type: text/html; charset=gb2312");  //视学校而定

    session_start();
    //模拟登录
    function login_post($url, $cookie, $post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
        curl_setopt($ch, CURLOPT_REFERER, 'http://115.236.84.162/');  //重要，302跳转需要referer，可以在Request Headers找到 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  //post提交数据
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    $_SESSION['xh']=$_POST['xh'];//获取并存储学号
    $xh=$_POST['xh'];
    $pw=$_POST['pw'];
    $code= $_POST['code'];

    $cookie = dirname(__FILE__) . '/cookie/'.$_SESSION['id'].'.txt';//获取cookie
    $url="http://115.236.84.162/default2.aspx";  //教务处地址
    $con1=login_post($url, $cookie, '');
    preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
    $post=array(
        '__VIEWSTATE'=>$view[1][0],
        'txtUserName'=>$xh,
        'textBox1'=>'',
        'TextBox2'=>$pw,
        'txtSecretCode'=>$code,
        'RadioButtonList1'=>iconv('utf-8', 'gb2312', '学生'),
        'Button1'=>'',
        'lbLanguage'=>'',
        'hidPdrs'=>'',
        'hidsc'=>''
    );

    $con2 = login_post($url, $cookie, http_build_query($post)); //将数组连接成字符串
    // echo $con2;

    //抓取成绩
    //成绩查询URL：http://115.236.84.162/xscjcx.aspx?xh=学号&xm=姓名&gnmkdm=N121605
    preg_match_all('/<span id="xhxm">([^<>]+)/', $con2, $xm);//正则出的数据存到$xm数组中
    $xm[1][0] = substr($xm[1][0], 0, -4);//字符串截取，获得姓名

    $url2 = "http://115.236.84.162/xscjcx.aspx?xh=".$_SESSION['xh']."&xm=".$xm[1][0];
    $viewstate = login_post($url2, $cookie, '');
    preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $viewstate, $vs);
    $state=$vs[1][0];  //$state存放一会post的__VIEWSTATE

    //POST数据
    $post=array(
        '__EVENTTARGET'=>'',
        '__EVENTARGUMENT'=>'',
        '__VIEWSTATE'=>$state,
        'hidLanguage'=>'',
        'ddlXN'=>'2015-2016',  //当前学年
        'ddlXQ'=>'1',  //当前学期
        'ddl_kcxz'=>'',
        'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8'  //“学期成绩”的gb2312编码，视情况而定
     );

    $content=login_post($url2, $cookie, http_build_query($post));
    // echo $content;
    preg_match_all('/<td>([^<>]+)/', $content, $cj);
    for($n=0; $n<count($cj[1]); $n++){
        echo $cj[1][$n];
    }
    
?>
