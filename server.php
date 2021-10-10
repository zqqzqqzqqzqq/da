<?php

//$corpid = $_REQUEST['corpid']; //企业id
//$corpsecret = $_REQUEST['corpsecret']; //应用secret
//$agentid = $_REQUEST['agentid']; //应用id

//如果就自己用，可以把参数写到这里。
$corpid = 'wwbff2ec62f3bc3052';
$corpsecret = 'pzoQ8WgAWHYOnuiXilnpc0cbyk7LSxtiEpL8vl7OIuo';
$agentid = '1000002';


$title = $_REQUEST['title']; //消息title
$description = $_REQUEST['description']; //消息内容
$description = str_replace(PHP_EOL, '<br>', $description);
$url = $_REQUEST['url']; //消息跳转url

if(!$corpid or !$corpsecret or !$agentid){
    exit("canshu buquan");
}


//获取access_token
$response = CurlGet("https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$corpid&corpsecret=$corpsecret","","");

$access_token = json_decode($response)->access_token;
if(!$access_token){
    exit("canshu cuowu");
}else{

    $json = '{"touser":"@all","msgtype":"textcard","agentid":"","textcard":{"title":"","description":"","url":"","btntxt":"更多"},"safe":1,"enable_id_trans":0,"enable_duplicate_check":0}';
    $json = json_decode($json);

    $json->agentid = $agentid;
    $json->textcard->title = $title ? $title : '无标题';
    $json->textcard->description = $description ? $description : '无内容';
    
    $json->textcard->url = $url ? $url : 'URL';

    echo CurlPost("https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=$access_token","", json_encode($json));
}





function CurlGet($url,$cookies = "",$UserAgent = "")
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
    curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_REFERER, '');
        curl_setopt($curl, CURLOPT_COOKIE, $cookies);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    if ($UserAgent != "") {
        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function CurlPost($url, $cookies="", $post_data="", $headers=array(), $refer="", $UserAgent = '')
{

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); 
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
    curl_setopt($curl, CURLOPT_URL, $url);     
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent); 
        curl_setopt($curl, CURLOPT_COOKIE, $cookies);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    if ($refer != '') {
        curl_setopt($curl, CURLOPT_REFERER, $refer);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}