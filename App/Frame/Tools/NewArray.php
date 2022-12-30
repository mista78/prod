<?php

use ScssPhp\ScssPhp\Compiler;

function NewArray($num)
{
    global $NewArray;
    for ($i = 0; $i < $num; $i++) {
        $NewArray[] = null;
    }
    return $NewArray;
}
function reuseableBlock($content)
{
    if (preg_match_all('/<!-- wp:block {"ref":\d+} \/-->/', $content, $matches)) {
        //must loop through all matches
        foreach ($matches[0] as $match) {
            preg_match_all('!\d+!', $match, $matchesNumbers);
            $blockId = $matchesNumbers[0][0];
            $block   = FindFirst([
                'table' => 'wp_posts',
                "where" => "ID = {$blockId} AND post_type = 'wp_block'",
                "database" => "wordpress",
            ]);
            $content = str_replace($match, $block['post_content'], $content);
        }
    }

    return $content;
}


function human_filesize($bytes, $decimals = 2)
{
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

function wordpresData($data)
{
    global $conf;
    $content = $data['post_content'];
    $content = reuseableBlock($content);
    $re = '/<@-- ([a-z]+)-([a-zA-Z\-]+) --@>/m';
    $re2 = '/data-([a-z\-]+)="([a-zA-Z\-]+)"/m';
    preg_match_all($re2, html_entity_decode($content), $matches2, PREG_SET_ORDER, 0);
    preg_match_all($re, html_entity_decode($content), $matches, PREG_SET_ORDER, 0);
    static $tmpcontent = null;
    $matchFil = [
        
    ];
    $html = "";
    foreach ($matches2 as $value) {
        [$connector, $type, $component] = $value;
        if (!isset($matchFil[$type . $component]) && in_array($type, ['css', 'script'])) {
            $matchFil[$type . $component] = $value;
        }
    }
    foreach ($matches as $value) {
        [$connector, $type, $component] = $value;
        if (!isset($matchFil[$type . $component]) && in_array($type, ['css', 'script'])) {
            $matchFil[$type . $component] = $value;
        }
    }
    $dirname = "intersport";
    $scss_compiler = new Compiler();
    $scss_compiler->setOutputStyle("compressed");
    foreach ($matchFil as $mt) {
        [$connector, $type, $component] = $mt;
        if ($tmpcontent === null) {
            $tmpcontent = $content;
        }
        $tmpcontent = str_replace($connector, "", $tmpcontent);
        switch ($type) {
            case 'css':
                $foldercss = ROOT . 'Public/plugins/'.$dirname.'/src/' . $component . '/builds/';
                if (file_exists($foldercss . 'styles.scss')) {
                    [$css] = glob($foldercss . 'dist/css/*.css');
                    $size = filesize($css);
                    $css = @file_get_contents($css);
                    [$tmpcontent, $tags] = replaceAssets($css, "", $tmpcontent, $type, $component, human_filesize($size));
                    $html .= $tags . "\n";
                };
                break;
            case 'script':
                $folderjs = ROOT . 'Public/plugins/'.$dirname.'/src/' . $component . '/builds/';
                if (file_exists($folderjs . 'scripts.js')) {
                    [$folderjs] = glob($folderjs . 'dist/script/*.js');
                    $js = @file_get_contents($folderjs);
                    $size = ($js != null) ? filesize($folderjs) : 0;
                    [$tmpcontent, $tags] = replaceAssets($js, "", $tmpcontent, $type, $component, human_filesize($size));
                    $html .= $tags . "\n";
                };
                break;

            default:
                break;
        }
    }
    Config("block.content", $html);
    Config("block.title", $data['post_title']);
}

function replaceAssets($vars, $flag, $content, $type = "style", $component = null, $size)
{
    $tags = "";
    $typetmp = ($type === 'css' || $type === "style") ? 'style' : 'script';
    $type = $type === "style" ? "css" : $type;
    // $component = ($component != null) ? $component : $flag;
    $flag = "<@-- $type-$component --@>";
    $pos = strpos($content, "-undefined");
    if ($pos !== false) {
        $content = str_replace("<@-- $type-undefined --@>", "", html_entity_decode($content));
    }
    $defer = ($type === "script") ? "type='module'" : "";
    if ($vars != null) {
        $tags = "<$typetmp $defer data-size='$size'  data-component='$component'>$vars</$typetmp>";
        $content = str_replace($flag, "", html_entity_decode($content));
    } else {
        $content = str_replace($flag, "", html_entity_decode($content));
    }
    return [$content, $tags];
}
