<div class="sidebar-bg"></div>
<div class="sidebar">
    <ul>
        <?foreach($ci->menu as $m):?>
            <?if($m['child']):?>
                <li class="tree-head">
                    <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$m['url']?>" <?if(isset($m['active'])):?>class="active"<?endif?>><?=$m['name']?></a>
                    <ol>
                        <?foreach($m['child'] as $child):?>
                            <li>
                                <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$child['url']?>" <?if(isset($child['active'])):?>class="active"<?endif?>><?=$child['name']?></a>
                                <?if($session->get('islogin')):?>
                                <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$child['url']?>&a=edit#main" class="edit">编辑</a>
                                <?endif?>
                            </li>
                        <?endforeach?>
<!--                         <li><a href="<?=BASEURL?>?a=add&act=t2&t=<?=$category?>&t1=<?=$m['url']?>">+ 添加二级目录</a></li> -->
                    </ol>
                    <?if($session->get('islogin')):?>
                        <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$m['url']?>&a=edit#main" class="edit">编辑</a>
                    <?endif?>
                </li>
            <?else:?>
                <li class="tree-head">
                    <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$m['url']?>" <?if(isset($m['active'])):?>class="active"<?endif?>><?=$m['name']?></a>
                    <?if($session->get('islogin')):?>
                        <a href="<?=BASEURL?>?c=<?=$act?>&f=<?=$m['url']?>&a=edit#main" class="edit">编辑</a>
                    <?endif?>
                    <ol>
<!--                         <li><a href="<?=BASEURL?>?a=add&act=t2&t=<?=$category?>&t1=<?=$m['url']?>">+ 添加二级目录</a></li> -->
                    </ol>
                </li>
            <?endif?>
        <?endforeach?>
<!--         <li><a href="<?=BASEURL?>?t=<?=$category?>&act=t1&a=add">+ 添加一级目录</a></li> -->
    </ul>
</div>
