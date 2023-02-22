<?php

use ScssPhp\ScssPhp\Compiler;

/**
 * compressed
 * expanded
 */
function CompileCss($array = [], $formated = "compressed")
{
    $path = [];
    $init = ROOT . 'Public/assets/base/*.scss';
    foreach (array_merge(glob($init), [
        APP . 'Component/Moodboard/index.scss',
        APP . 'Component/Sections/index.scss',
    ]) as $file) {
        if (file_exists($file)) {
            $path[] = $file;
        }
    }
    foreach ($array as $sec) {
        foreach ($sec as  $v) {
            if (isset($v['item'])) {
                foreach ($v['item'] as  $vi) {
                    $pathname = APP . 'Component' . DS . ucfirst($vi['component']) . DS . '*.{scss,css}';
                    foreach (glob($pathname, GLOB_BRACE) as $file) {
                        if (file_exists($file) && !in_array($file, $path)) {
                            $path[] = $file;
                        }
                    }
                }
            }
        }
    }
    return runstyle($path, $formated);
}


function CompileJs($array = [])
{
    $path = [];
    $init = ROOT . 'Public' . DS . 'assets' . DS . 'js' . DS . '*.js';
    foreach (array_merge(glob($init), [
        APP . 'Component/Moodboard/index.js',
        APP . 'Component/Sections/index.js',
    ]) as $file) {
        if (file_exists($file)) {
            $path[] = $file;
        }
    }
    foreach ($array as $sec) {
        foreach ($sec as  $v) {
            if (isset($v['item'])) {
                foreach ($v['item'] as  $vi) {
                    $pathname = APP . 'Component' . DS . ucfirst($vi['component']) . DS . '*.js';
                    foreach (glob($pathname) as $file) {
                        if (file_exists($file) && !in_array($file, $path)) {
                            $path[] = $file;
                        }
                    }
                }
            }
        }
    }

    return HtmlScript($path);
}

function HtmlScript($array = [])
{
    $html = '';
    $html .= PHP_EOL;
    foreach ($array as $value) {
        $data = file_get_contents($value);
        $elem = '';
        if (empty($data)) continue;
        if (strpos($value, "assets") === false) {
            $elem .= "window.addEventListener('DOMContentLoaded', (event) => {(function(wd,e) { \n";
        }
        $elem .= $data;
        if (strpos($value, "assets") === false) {
            $elem .= "})(window.wd,event);}); \n";
        }
        if (!isset($_GET['debug'])) {

            $data = new JavaScriptPacker($elem);
            $replace = str_replace([
                APP . 'Component' . DS,
                ROOT . 'Public' . DS . 'assets' . DS . 'js' . DS,
                '/index.js',
            ], '', $value);
            $html .= BuildHtml([
                [
                    "tags" => "script",
                    "attributes" => [
                        "type" => "module",
                        "data-component" => "$replace",
                    ],
                    "content" => $data->pack(),
                ]
            ]);
        } else {
            $replace = str_replace([
                APP . 'Component' . DS,
                ROOT . 'Public' . DS . 'assets' . DS . 'js' . DS,
                '/index.js',
            ], '', $value);
            $html .= BuildHtml([
                [
                    "tags" => "script",
                    "attributes" => [
                        "type" => "module",
                        "data-component" => "$replace",
                    ],
                    "content" => $elem,
                ]
            ]);
        }
    }
    return $html;
}


function runstyle($path, $format_style = "expanded")
{
    $scss_compiler = new Compiler();
    $html = '';
    $scss_compiler->setOutputStyle($format_style);
    foreach ($path as $file_path) {
        $string_sass = "";
        $init = glob(ROOT . 'Public/assets/css/*.scss');
        foreach ($init as $file) {
            if (file_exists($file)) {
                $string_sass .= file_get_contents($file);
            }
        }
        $file_path_elements = pathinfo($file_path);
        $file_dir = $file_path_elements["dirname"];
        $file_name = $file_path_elements['filename'];
        [$file] = glob($file_dir . DS . $file_name . ".{scss,css}", GLOB_BRACE);
        $string_sass .= file_get_contents($file);
        $file_dir = str_replace([
            ROOT . 'App' . DS . 'Component' . DS,
            ROOT . "Public" . DS . 'assets' . DS
        ], '', $file_dir);
        $component = str_replace("/index", '', $file_dir . DS . $file_name);
        $css = $scss_compiler->compileString($string_sass)->getCss();
        if ($css == '') continue;
        $html .= BuildHtml([
            [
                "tags" => "style",
                "attributes" => [
                    "type" => "text/css",
                    "data-component" => $component,
                ],
                "content" => $css,
            ]
        ]);
    }
    return $html;
}
