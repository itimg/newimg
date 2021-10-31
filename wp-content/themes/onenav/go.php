<?php 
if(strlen($_SERVER['REQUEST_URI']) > 384 || strpos($_SERVER['REQUEST_URI'], "eval(") || strpos($_SERVER['REQUEST_URI'], "base64")) {
	@header("HTTP/1.1 414 Request-URI Too Long");
	@header("Status: 414 Request-URI Too Long");
	@header("Connection: Close");
	@exit;
}
$url = $_GET['url'];
if( !empty($url) ) {
    $title = __('加载中','i_theme');
    if ($url == base64_encode(base64_decode($url))) 
        $b =  base64_decode($url); 
    else
	    $b = $url;
} else {
    $title = __('参数缺失，正在返回首页...','i_theme');
    $b = '//'.$_SERVER['HTTP_HOST'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes"> 
<meta name="robots" content="noindex,follow">
<title><?php echo $title ?></title>
<meta http-equiv="refresh" content="1;url=<?php echo $b; ?>">
<style>
body{margin:0}body{height:100%}#loading{-webkit-box-pack:center;justify-content:center;-webkit-box-align:center;align-items:center;display:-webkit-box;display:flex;position:fixed;top:0;left:0;width:100%;height:100%;background:#fff}.io-black-mode #loading{background:#1b1d1f}
</style>
</head>
<body class="<?php echo theme_mode() ?>">
<div id="loading"><?=loading_type()?></div>
</body>
</html>