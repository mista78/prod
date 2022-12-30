<?php

function dd(...$var)
{
    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    foreach ($var as $v) {
        // file color atom
        $default = 'background-color: #1e1e1e; color: #d4d4d4; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px; line-height: 1.5; font-family: Consolas, Menlo, Monaco, &quot;Courier New&quot;, monospace; white-space: pre-wrap; word-break: break-word; word-wrap: break-word; tab-size: 4; hyphens: none;';
        echo "<details style='{$default}'>";
        echo '<summary style="color: #d4d4d4; cursor: pointer; font-weight: bold;">';
        echo 'Debug in ' . $file . ' on line ' . $line;
        echo '</summary>';
        echo '<pre style="margin: 0;">';
        print_r($v);
        echo '</pre>';
        echo '</details>';
    }
    die();
}

function Debug(...$var)
{
    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    foreach ($var as $v) {
        $default = 'background-color: #1e1e1e; color: #d4d4d4; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px; line-height: 1.5; font-family: Consolas, Menlo, Monaco, &quot;Courier New&quot;, monospace; white-space: pre-wrap; word-break: break-word; word-wrap: break-word; tab-size: 4; hyphens: none;';
        echo "<details style='{$default}'>";
        echo '<summary style="color: #d4d4d4; cursor: pointer; font-weight: bold;">';
        echo 'Debug in ' . $file . ' on line ' . $line;
        echo '</summary>';
        echo '<pre style="margin: 0;">';
        print_r($v);
        echo '</pre>';
        echo '</details>';
    }
}


function renderDebug($value)
{
    ob_start();
    print_r($value);
    return ob_get_clean();
}

function str_slice() {
    $args = func_get_args();
    switch (count($args)) {
        case 1:
            return $args[0];
        case 2:
            $str        = $args[0];
            $str_length = strlen($str);
            $start      = $args[1];
            if ($start < 0) {
                if ($start >= - $str_length) {
                    $start = $str_length - abs($start);
                } else {
                    $start = 0;
                }
            }
            else if ($start >= $str_length) {
                $start = $str_length;
            }
            $length = $str_length - $start;
            return substr($str, $start, $length);
        case 3:
            $str        = $args[0];
            $str_length = strlen($str);
            $start      = $args[1];
            $end        = $args[2];
            if ($start >= $str_length) {
                return "";
            }
            if ($start < 0) {
                if ($start < - $str_length) {
                    $start = 0;
                } else {
                    $start = $str_length - abs($start);
                }
            }
            if ($end <= $start) {
                return "";
            }
            if ($end > $str_length) {
                $end = $str_length;
            }
            $length = $end - $start;
            return substr($str, $start, $length);
    }
    return null;
}
