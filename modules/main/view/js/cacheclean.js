CCacheClean = function()
{
    var _this = this;
    
    this.onClick = function()
    {
        if (confirm('Подтверждаете?')) 
        {
            var type = $(this).data('ctype');
            var li = $(this).parent();
            $('.success', li).hide();
            $.ajaxQuery({
                url: global.cleanCacheUrl,
                data: {
                    type: type
                },
                success: function() {
                    $('.success', li).show();                    
                }
            });
        }
    }
    
    $('.cache_links a').click(_this.onClick);
}

$(function() {
    new CCacheClean();
})