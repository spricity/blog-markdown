<?php
include_once(BASEDIR . DS . 'lib' . DS . 'md' . DS . 'Michelf' . DS . 'MarkdownExtra.inc.php');

// Install PSR-0-compatible class autoloader


// Get Markdown class
use \Michelf\MarkdownExtra;

// Read file and pass content through the Markdown parser
// $text = file_get_contents(BASEDIR . DS . 'help.md');
$html = MarkdownExtra::defaultTransform($content);
echo $html;
?>
