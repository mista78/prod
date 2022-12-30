<?php

function Request()
{

    $request = [];

    $request['url'] = isset($_SERVER['REQUEST_URI']) ? str_replace("/project", "", $_SERVER['REQUEST_URI']) : "/";

    return $request;
}
function lang($url, $table)
{
    $lang = null;
    if (isset($_SESSION['local']) && $_SESSION['local'] === $url[0]) {
        $lang = $_SESSION['local'];
    } else {
        $lang = (strlen($url[0]) <= 2 && in_array($url[0], $table)) ? $url[0] : "en";
        $remove = array_shift($url);
        $_SESSION['local'] = $lang;
        $url = "/" . $lang . "/" . implode("/", $url);
        header("Location:" . $url);
        die();
    }
    return $lang;
}
