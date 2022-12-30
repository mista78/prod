<?php

function CacheWrite($cachename, $content)
{
    global $conf;
    $file = $conf['dirname'] . DS . $cachename;
    file_put_contents($file, json_encode($content));
    return json_decode(file_get_contents($file), true);
}

function CacheRead($cachename, $options = [])
{
    global $conf;

    $default= ["duration" => 60 * 2, "dirname" => ROOT . 'Public' . DS . 'tmp'];
    $conf = array_merge($default,$conf, $options);
    extract($conf);
    if(!file_exists($conf['dirname'])){
        mkdir( $conf['dirname'], 0777, true );
    }

    $file = $conf['dirname'] . DS . $cachename;
    if (!file_exists($file)) {
        return false;
    }

    $times = (time() - filemtime($file)) / 60;
    if ($times > $conf['duration']) {
        return false;
    }

    return json_decode(file_get_contents($file), true);
}

function CacheStart($cachename,$callback, $options = [])
{
    global $conf;
    $default= ["duration" => 60 * 2, "dirname" => ROOT . 'Public' . DS . 'tmp'];
    $conf = array_merge( $default, $conf, $options);
    extract($conf);
    if(CacheRead($cachename)){
        return CacheRead($cachename);
    }
    $content = $callback();
    return CacheWrite($cachename, $content);
}
