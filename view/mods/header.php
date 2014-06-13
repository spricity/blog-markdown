<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?if(isset($title))echo $title;else echo '终结者';?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=BASEURL?>view/assets/blog.css">
        <? if(SITE === 'zseller'):?>
            <link rel="stylesheet" href="http://g.assets.daily.taobao.net/ju/zseller/1.0.0/zseller.css" />
            <script src="http://g.assets.daily.taobao.net//??kissy/k/1.3.2/kissy-min.js,ju/zcom/1.0.0/zcom.js,ju/zseller/1.0.0/zseller.js"></script>
        <?elseif(SITE === 'zadmin'):?>
            <link rel="stylesheet" href="http://g.assets.daily.taobao.net/ju/zadmin/1.0.0/zadmin.css" />
            <script src="http://g.assets.daily.taobao.net/??kissy/k/1.3.2/kissy-min.js,fi/bui/adapter-min.js,ju/zadmin/1.0.0/js/dpl/ko.js,ju/zadmin/1.0.0/js/dpl/jquery-1.8.1.min.js,ju/zadmin/1.0.0/js/dpl/leftmenu2-min.js,ju/zadmin/1.0.0/js/dpl/topSlider-min.js,ju/zcom/1.0.0/zcom-min.js,ju/zadmin/1.0.0/zadmin-min.js"></script>
        <?endif?>
    </head>
    <body class="ju">
        <header>
            <nav class="gnav">
                <div class="">
                    <a href="/" class="nav-item">首页</a>
                    <?foreach($ci->navs as $nav):?>
                        <a href="<?=BASEURL?>?c=<?=$nav['url']?>" class="nav-item <?=$nav_active === $nav['category'] ? 'active' : ''?>"><?=$nav['name']?></a>
                    <?endforeach?>
                    <!-- <a href="<?=BASEURL?>?a=admin" class="nav-item pull-right <?=$nav_active === 'admin' ? 'active' : ''?>"> &gt; 管理</a> -->
                </div>
            </nav>

        </header>
