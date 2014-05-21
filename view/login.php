<?php
include_once('mods/header.php');
?>

<main class="wrapper pull-mt-40">
<form action="<?=BASEURL?>?a=login&c=check" method="post">
        <div class="title">
            登录
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
                <span>登录密码：</span><input type="password" name="password" class="txt" value="<?=_form_value('password')?>">
            </label>
            <?=_form_error('password');?>
        </div>

        <div class="block-wrap pull-mt-20">
            <input type="submit" class="button button-red" name="save" value="登录">
        </div>
    </form>
</main>

<?include_once('mods/footer.php')?>
