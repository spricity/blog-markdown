<?php
include_once(BASEDIR . '/view/mods/admin-header.php');
?>

<main class="wrapper pull-mt-40">
<form action="<?=BASEURL?>?a=admin&c=repwd" method="post">
        <div class="title">
            修改密码
        </div>
        <div class="input">
            <label>
                <span>用户邮箱:</span>
                <input type="text" name="email" class="txt" value="<?=_form_value('email')?>">
            </label>
            <?=_form_error('email');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>当前密码：</span><input type="password" name="oldpwd" class="txt" value="<?=_form_value('oldpwd')?>">
            </label>
            <?=_form_error('oldpwd');?>
        </div>
        <br>
        <div class="input">
            <label>
                <span>新的密码：</span><input type="password" name="password" class="txt" value="<?=_form_value('password')?>">
            </label>
            <?=_form_error('password');?>
        </div>

        <div class="block-wrap pull-mt-20">
            <input type="submit" class="button button-red" name="save" value="修改">
        </div>
    </form>
</main>

<?include_once(BASEDIR . '/view/mods/footer.php');?>
