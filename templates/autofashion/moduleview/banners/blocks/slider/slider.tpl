{addjs file="main_slider.js"}
{$banners=$zone->getBanners()}
<div class="row sliders-wrap">
    <div class="col-sm-24 sliders-wrap-inner">
        <div id="carouselMainPage" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                {foreach $banners as $banner key=key}
                    <li data-target="#carouselMainPage" data-slide-to="{$key}" {if $banner@first} class="active"{/if}></li>
                {/foreach}
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                {foreach $banners as $banner}
                    <div class="item{if $banner@first} active{/if}">
                        {if $banner.link}<a href="{$banner.link}" {if $banner.targetblank}target="_blank"{/if}>{/if}
                            <img src="{$banner->getBannerUrl()}" alt="{$banner.title}">
                            {if $banner.link}</a>{/if}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>