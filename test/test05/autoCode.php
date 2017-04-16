<?php  
/** php 发送流文件 
* @param  String  $url  接收的路径 
* @param  String  $file 要发送的文件 
* @return boolean 
*/  
function sendStreamFile($url, $file){  
    if(file_exists($file)){  
        $opts = array(  
            'http' => array(  
                'method' => 'POST',  
                'header' => 'content-type:application/x-www-form-urlencoded',  
                'content' => file_get_contents($file)  
            )  
        );  
        $context = stream_context_create($opts);  
        $response = file_get_contents($url, false, $context);  
        $ret = json_decode($response, true);  
        return $ret;  
    }else{  
        return false;  
    }  
}
$url = 'http://182.254.216.161/jwxt/api.php';
$file = './verifyCode.jpg';
$ret = sendStreamFile($url, $file);
echo json_encode($ret);  
?>