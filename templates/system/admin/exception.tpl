<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="Content-type" content="text/html; Charset=utf-8">
{addcss file="admin/exception.css" basepath="common"}
<link rel="stylesheet" type="text/css" href="{$Setup.CSS_PATH}/admin/exception.css" media="all" rel="stylesheet">
<title>Ой, ошибочка {$error.code}</title>
</head>
<body>
    <div class="exception">
        <div class="centered">
            {if !empty($CONFIG.logo)}
            <img src="{$CONFIG.__logo->getUrl(275,80)}">
            {/if}
            <div class="code">{$error.code}</div>
                <div class="message">{$error.comment}</div>
            <a href="/{$Setup.ADMIN_SECTION}/">Вернуться на главную</a>
        </div>
    </div>
</body>
</html>