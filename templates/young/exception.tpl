<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Ой, ошибочка {$error.code}</title>
<!--[if lt IE 9]>
<script src="{$THEME_JS}/html5shiv.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="{$THEME_CSS}/960gs/reset.css">
<link rel="stylesheet" type="text/css" href="{$THEME_CSS}/960gs/960.css">
<link rel="stylesheet" type="text/css" href="{$THEME_CSS}/style.css">
<link rel="stylesheet" type="text/css" href="{$THEME_CSS}/720.css">
<link rel="stylesheet" type="text/css" href="{$THEME_CSS}/mobile.css">

</head>
<body class="body404">
<div class="container_12">
    <div class="grid_12 exceptionBlock">
        <a href="{$site->getRootUrl()}" class="logo"><img src="{$site_config.__logo->getUrl(206, 46)}"></a>
        <div class="exceptionInfo">
            <p class="message">{$error.comment}</p>        
            <div class="mobileWrapper">
                <p class="code">{$error.code}</p>
                <img src="{$THEME_IMG}/e404.jpg" class="big">
                <p class="links">
                    <a href="{$site->getRootUrl()}" class="blueButton">На главную</a>
                </p>
            </div>
        </div>
    </div>
</div>
</html>