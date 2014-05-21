<?php
include_once('mods/header.php');
?>
<link rel="stylesheet" href="<?=BASEURL?>view/assets/markdown.css">
<script type="text/javascript" src="<?=BASEURL?>view/assets/prettify.js"></script>
<script type="text/javascript" src="<?=BASEURL?>view/assets/zepto.min.js"></script>

<main class="main mardown markdown-edit" id="main">
    <div class="nav-bannre">
        <div class="markdown-left">
            <div class="title">
                <div class="input markdown-edit-title">
                    <label>
                        <span>编辑标题：</span>
                        <input type="text" name="title" class="txt" id="title" value="<?=$current_select->name?>">
                    </label>
                </div>
            </div>
        </div>
        <div class="markdown-right">
            <div class="title">
                <span class="title-url">URL：</span>
                <div class="input markdown-edit-title">
                    <label>
                        <span><?=BASEURL . '?f=' ?></span>
                        <input type="text" name="url" class="txt" id="url" value="<?=$current_select->url?>">
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div id="app_wrap" class="container-fluid">
        <?php
            $origin_path = explode(',', $path);
            foreach($origin_path as $p){
                if(file_exists(DIR_DOCS . DS . $p)){
                    $origin_text = file_get_contents(DIR_DOCS . DS . $p);
                    // echo '<textarea class="markdown-edit-textarea">' . $origin_text . '</textarea>';
                    echo '<div id="editor">' . $origin_text . '</div>';
                }else{
                    write_file(DIR_DOCS . DS . $p, '');
                    // echo '<textarea class="markdown-edit-textarea">## help</textarea>';
                    echo '<div id="editor"></div>';
                }
            }
        ?>
        <div id="spliter" class="spliter" title="隐藏预览"></div>
        <div id="spliter_edit" class="spliter" title="隐藏编辑区域"></div>
        <div class="preview-data">
        <?php
        include_once(BASEDIR . DS . 'lib' . DS . 'md' . DS . 'Michelf' . DS . 'MarkdownExtra.inc.php');

        // Install PSR-0-compatible class autoloader


        // Get Markdown class
        use \Michelf\MarkdownExtra;

        // Read file and pass content through the Markdown parser
        // $text = file_get_contents(BASEDIR . DS . 'help.md');
        $path = explode(',', $path);
        foreach($path as $p){
            if(file_exists(DIR_DOCS . DS . $p)){
                $text = file_get_contents(DIR_DOCS . DS . $p);
                $html = MarkdownExtra::defaultTransform($text);
                echo $html;
            }
        }

        ?>
        </div>
    </div>
<script src="<?=BASEURL?>view/assets/ace/src/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=BASEURL?>view/assets/ace/src/theme-twilight.js" type="text/javascript" charset="utf-8"></script>
 <script src="<?=BASEURL?>view/assets/ace/src/mode-markdown.js" type="text/javascript" charset="utf-8"></script>


<script>

</script>
<script>
;(function(){
    var markdown_right_area = $(".preview-data"),
        app_wrap = $("#app_wrap"),
        edit_handle = $("#edit");
    var title = $("#title");
    function pretty(){
        var pre = markdown_right_area.find('pre'), len = pre.length;
        for(var i=0; i < len; i++){
            pre[i].className = 'prettyprint';
        }
        prettyPrint();
    }
    pretty();
    var editor = ace.edit("editor");

    function setWrap(value, col) {
        var session = editor.session;
        var renderer = editor.renderer;
        switch (value) {
            case "off":
                session.setUseWrapMode(false);
                renderer.setPrintMarginColumn(80);
                break;
            case "free":
                session.setUseWrapMode(true);
                session.setWrapLimitRange(col, col);
                renderer.setPrintMarginColumn(null);
                break;
            default:
                session.setUseWrapMode(true);
                var col = parseInt(value, 10);
                session.setWrapLimitRange(col, col);
                renderer.setPrintMarginColumn(col);
        }
    }
    setWrap('free', null);
    editor.setTheme("ace/theme/twilight");
    var MarkdownMode = require("ace/mode/markdown").Mode;
    editor.getSession().setMode(new MarkdownMode());
    editor.on("change", function(e){
        editor.resize()
        var val = editor.getValue(),
            title_val = title.val();
        if(!title_val){
            alert('标题不能为空');
            return;
        }

        $.post('<?=BASEURL?>?a=preview', {
            content: val,
            item: '<?=json_encode($current_select)?>',
            path: "<?=$current_select->path?>",
            title: title.val()
        }, function(html){
            markdown_right_area.html(html);
            pretty();
        })
    });
    $("#spliter").on("click", function(){
        app_wrap.toggleClass('no-preview');
        if(app_wrap.hasClass('no-preview')){
            setWrap('free', 80);
            setWrap('free', null);
            console.log(1);
        }else{
            console.log(2);
            setWrap('free', 80);
            setWrap('free', null);
        }
    });
    $("#spliter_edit").on("click", function(){
        app_wrap.toggleClass('no-edit');
    })
})();
</script>
</main>

<?include_once('mods/footer.php')?>
