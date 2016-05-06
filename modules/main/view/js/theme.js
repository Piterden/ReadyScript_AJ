CTheme = function()
{
    var _this = this;
    this.dialog = $('#themeDialog');    
    
    this.init = function()
    {
        _this.dialog.dialog({autoOpen: false, width: 700, height: 440, modal:true});
        $('.cancel', _this.dialog).click(_this.cancelPress);
        $('.install', _this.dialog).click(_this.installPress);
        $('#setterTheme').click(_this.showDialog);
    }
    
    this.showDialog = function()
    {
        _this.dialog.dialog('open');
    }
    
    this.cancelPress = function()
    {
        _this.dialog.dialog('close');
    }
    
    this.installPress = function()
    {
        
        
    }
    
    this.init();
}

$(function()
{
    var theme = new CTheme();
})