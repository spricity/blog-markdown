<?php
include_once('mods/admin-header.php');
?>

<main class="wrapper pull-mt-40">
<form action="<?=BASEURL?>?a=add&c=save&category=<?=$category?>" method="post">
        <div class="title">
            <?=$title?>
        </div>
        <div class="input">
            <label>
                <span>中文标识：</span>
                <input type="text" name="name" class="txt" placeholder="随笔" autocomplete="off" value="<?=_form_value('name')?>" place="分类名">
            </label>
            <?=_form_error('name');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>英文标识：</span>
                <input type="text" name="category" class="txt" autocomplete="off" value="<?=_form_value('category')?>" placeholder="start">
            </label>
            <?=_form_error('category');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>排序数值：</span>
                <input type="text" name="sort" class="txt" autocomplete="off" value="<?=_form_value('sort')?>" placeholder="1">
            </label>
            <?=_form_error('sort');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>固定链接：</span>
                <input type="text" name="url" class="txt" autocomplete="off" value="<?=_form_value('url')?>" placeholder="<?=$category?>-xxx">
            </label>
            <?=_form_error('url');?>
        </div>

        <div class="block-wrap pull-mt-20">
            <input type="submit" class="button button-red" name="save" value="提交">
        </div>
    </form>
</main>

<?include_once('mods/footer.php')?>
