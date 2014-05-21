<?php
include_once('mods/header.php');
?>

<main class="wrapper pull-mt-40">
<form action="/button/create" method="post">
        <div class="title">
            创建应用
        </div>
        <div class="input">
            <label>
                <span>应用名称:</span>
                <input type="text" name="app" class="txt" placeholder="juadmin" value="">
            </label>
                    </div>
        <br>
        <div class="input">
            <label>
                <span>tag地址：</span><input type="text" name="svn" class="txt" value="" placeholder="http://svn.app.taobao.net/repos/juadmin/tags/">
            </label>
                    </div>

        <div class="block-wrap pull-mt-20">
            <input type="submit" class="button button-red" name="save" value="提交">
        </div>
    </form>
</main>

<?include_once('mods/footer.php')?>
