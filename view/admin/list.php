<?php
include_once(BASEDIR . '/view/mods/admin-header.php');
?>

<main class="wrapper pull-mt-40">
<div class="title">目录列表</div>
<table class="table table-bordered table-hover">
        <tbody>
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>标识符</th>
            <th>排序</th>
            <!-- <th>固定URL</th> -->
            <th>操作</th>
        </tr>
        <?foreach($ci->navs as $index=>$nav):?>
        <tr>
            <td><?=$index + 1?></td>
            <td><?=$nav['name']?></td>
            <td><?=$nav['category']?></td>
            <td><?=$nav['sort']?></td>
            <!-- <td><?=$nav['url']?></td> -->
            <td>
                <a href="<?=BASEURL?>?a=admin&c=modify&category=<?=$nav['category']?>">编辑</a>
                <a href="<?=BASEURL?>?a=tree&category=<?=$nav['category']?>">目录结构</a>
            </td>
        </tr>
        <?endforeach?>
        </tbody>
    </table>
    <a href="<?=BASEURL?>?a=admin&c=add" class="button button-red">+ 添加</a>
</main>
<?include_once(BASEDIR . '/view/mods/footer.php')?>
