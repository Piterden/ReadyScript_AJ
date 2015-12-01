<img src="{$router->getUrl('kaptcha',['rand',$field->getRandom()])}" width="100" height="42"> 
<input type="text" name="{$field->getFormName()}" {$field->getAttr()}>