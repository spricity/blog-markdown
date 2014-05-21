<?php
include_once('mods/header.php');
?>
<link rel="stylesheet" href="<?=BASEURL?>view/assets/markdown.css">
<script type="text/javascript" src="<?=BASEURL?>view/assets/prettify.js"></script>

<main class="main mardown">
<?php include_once('mods/menu.php'); ?>
<div class="clearfix"></div>
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
<script>
    var pre = document.getElementsByTagName('pre'), len = pre.length;
    for(var i=0; i < len; i++){
        pre[i].className = 'prettyprint';
    }
    prettyPrint();
</script>
</main>

<?include_once('mods/footer.php')?>
