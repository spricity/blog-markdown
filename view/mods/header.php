<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?if(isset($title))echo $title;else echo '终结者';?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=BASEURL?>view/assets/blog.css">
        <script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://g.tbcdn.cn/??kissy/k/1.3.2/kissy-min.js"></script>
        <script src="http://g.assets.daily.taobao.net/ju/zcom/zcom.js"></script>
    </head>
    <body>
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
