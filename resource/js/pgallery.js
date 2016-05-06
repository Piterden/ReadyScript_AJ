$(window).load(function () {
    $('#photolist').css({overflow: 'hidden'});
    picmove();
    
    $('#photolist a[photo]').click(function() {

        loadImg( $(this).attr('photo'), true );
        return false;
    })

    $('#prev, #next').click(function()
    {
        loadImg( $(this).attr('photo') );
        return false;
    });
    
});

var maxOffset;
var loadingTimer;

function picmove() {
  var div = $('#photolist'),
               ul = $('#photolist ul'),
               ulPadding = 5;
  var divWidth = div.width();
  var lastLi = ul.find('li:last-child');
  var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
  div.mousemove(function(e)
  {
    var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
    div.scrollLeft(left);
  });
  
  maxOffset = ulWidth - divWidth;
  setCenter();

}


function setCenter()
{
    var div = $('#photolist');
    var act = $('.act', div);
    if (act.length>0)
    {
      offsetAct = act.get(0).offsetLeft-288;
      if (offsetAct >0) {
        if (offsetAct > maxOffset) offsetAct = maxOffset;
        div.scrollLeft(offsetAct);
      }
    }    
}


function loadImg(photo_src, notSetCenter)
{
    if (photo_src == '') return false;
    $('#photolist li').removeClass('act');
    var a = $("#photolist a[photo='"+photo_src+"']");
    var li = a.parent();
    
    li.addClass('act');
    if (!notSetCenter) setCenter();
    
    loadingTimer = setTimeout('startLoading()', 200);
    
    $('#bigview').attr('src',photo_src);
    $('#bigview').load(function()
    {
            $('#phototext').html(a.attr('desc'));
            
            var prev_a = $('#prev');
            if (li.prev().length>0)
            {
                var prev_src = $('a', li.prev()).attr('photo');
                prev_a.attr('photo', prev_src);
                prev_a.addClass('prev_act');
            } else {
                prev_a.attr('photo', '');
                prev_a.addClass('prev');
                prev_a.removeClass('prev_act');
            }
            
            var next_a = $('#next');
            
            if (li.next().length>0)
            {
                var next_src = $('a', li.next()).attr('photo');
                next_a.attr('photo', next_src);
                next_a.addClass('next_act');
            } else {
                next_a.attr('photo', '');
                next_a.addClass('next');
                next_a.removeClass('next_act');
            }
            clearTimeout(loadingTimer);
            endLoading();
    });
    
}

function startLoading()
{
    $('#bigview').addClass('loading');
    $('#loading').show();
}

function endLoading()
{
    $('#bigview').removeClass('loading');
    $('#loading').hide();
}