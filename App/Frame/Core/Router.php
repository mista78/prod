<?php
function Prefix($url, $prefix)
{
    global $route;
    $route['prefixes'][$url] = $prefix;
}


function Config($url, $value)
{
    global $conf;
    $conf[$url] = $value;
}


function GetParser($url, $request)
{
    global $route, $request;
    $url = trim($url, '/');

    if (empty($url)) {
        $default = null;
        foreach ($route["request"] as $key => $value) {
            if ($value["redir"] === "''") {
                $default = $value["url"];
            }
        }
        $url = $default;
    } else {
        $match = false;
        foreach ($route['request'] as $v) {
            if (!$match && preg_match($v['redirreg'], $url, $matches)) {
                $url = $v['origin'];
                foreach ($matches as $k => $v) {
                    $url = str_replace(':' . $k . ':', $v, $url);
                }
                $match = true;
            }
        }
    }
    $params = explode('/', $url);
    if (isset($route['prefixes']) && in_array($params[0], array_keys($route['prefixes']))) {
        $request['prefix'] = trim($route['prefixes'][$params[0]]);
        array_shift($params);
    }
    $request['request']['controller'] = ucfirst($params[0]);
    $request['request']['action'] = isset($params[1]) ? $params[1] : 'index';
    foreach (isset($route['prefixes']) ? $route['prefixes'] : [] as $k => $v) {
        if (strpos($request['request']['action'], $v . '_') === 0) {
            $request['prefix'] = $v;
            $request['request']['action'] = str_replace($v . '_', '', $request['request']['action']);
        }
    }
    $request['request']['params'] = array_slice($params, 2);
    return $request;
}

function GetUrl($redir, $url)
{
    global $route;
    $rt = [];
    $r = array();
    $r['params'] = array();
    $r['url'] = $url;

    $r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/', '${1}:(?P<${1}>${2})', $url);
    $r['originreg'] = str_replace('/*', '(?P<args>/?.*)', $r['originreg']);
    $r['originreg'] = '/^' . str_replace('/', '\/', $r['originreg']) . '$/';
    // MODIF
    $r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/', ':${1}:', $url);
    $r['origin'] = str_replace('/*', ':args:', $r['origin']);

    $params = explode('/', $url);
    foreach ($params as $k => $v) {
        if (strpos($v, ':')) {
            $p = explode(':', $v);
            $r['params'][$p[0]] = $p[1];
        }
    }

    $r['redirreg'] = $redir;
    $r['redirreg'] = str_replace('/*', '(?P<args>/?.*)', $r['redirreg']);
    foreach ($r['params'] as $k => $v) {
        $r['redirreg'] = str_replace(":$k", "(?P<$k>$v)", $r['redirreg']);
    }
    $r['redirreg'] = '/^' . str_replace('/', '\/', $r['redirreg']) . '$/';

    $r['redir'] = preg_replace('/:([a-z0-9]+)/', ':${1}:', $redir);
    $r['redir'] = str_replace('/*', ':args:', $r['redir']);
    $route['request'][] = $r;
}

function Url($url = null)
{
    global $route;
    trim($url, '/');
    foreach ($route['request'] as $v) {
        if (preg_match($v['originreg'], $url, $match)) {
            $url = $v['redir'];
            foreach ($match as $k => $w) {
                $url = str_replace(":$k:", $w, $url);
            }
        }
    }
    foreach (isset($route['prefixes']) ? $route['prefixes'] : [] as $k => $v) {
        if (strpos($url, $v) === 0) {
            $url = str_replace($v, $k, $url);
        }
    }
    if (isset($route['prefixes'])) {
        foreach ($route['request'] as $v) {
            if (preg_match($v['originreg'], $url, $match)) {
                $url = $v['redir'];
                foreach ($match as $k => $w) {
                    $url = str_replace(":$k:", $w, $url);
                }
            }
        }
    }

    return WEBROOT . $url;
}

function Webroot($url)
{
    trim('/' . $url);

    return WEBROOT . $url;
}



function Redirection($path = '', $code = null)
{
    if ($code == 301) {
        header("HTTP/1.1 301 Moved Permanently");
    } elseif ($code == 404) {
        header("HTTP/1.0 404 Not Found");
    }
    header("Location:" . url($path));
    die();
}
