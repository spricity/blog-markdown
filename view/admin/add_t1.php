<?php
include_once(BASEDIR . '/view/mods/header.php');
?>

<main class="wrapper pull-mt-40">
<form action="<?=BASEURL?>?a=add&t=<?=$t?>&act=t1&do=save" method="post">
        <div class="title">
            <?=$title?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>目录标题:</span>
                <input type="text" name="name" class="txt" autocomplete="off" value="<?=_form_value('name')?>" placeholder="目录名">
            </label>
            <?=_form_error('name');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>数字排序：</span>
                <input type="text" name="sort" class="txt" autocomplete="off" value="<?=_form_value('sort')?>" placeholder="1">
            </label>
            <?=_form_error('sort');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>固定URL：</span>
                <input type="text" name="url" class="txt" autocomplete="off" value="<?=_form_value('url')?>" placeholder="">
            </label>
            <?=_form_error('url');?>
        </div>

        <div class="block-wrap pull-mt-20">
            <input type="submit" class="button button-red" name="save" value="提交">
        </div>
    </form>
</main>

<?include_once(BASEDIR . '/view/mods/footer.php');?>
