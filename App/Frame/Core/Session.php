<?php

function write($key, $value)
{
    $_SESSION[$key] = $value;
}

function read()
{
    $nbArg = func_num_args();
    $data = func_get_args();
    if ($nbArg > 0 and $nbArg < 2) {
        if (isset($_SESSION[$data[0]])) {
            return $_SESSION[$data[0]];
        } else {
            return false;
        }
    } elseif ($nbArg > 1) {
        if (isset($_SESSION[$data[0]][$data[1]])) {
            return $_SESSION[$data[0]][$data[1]];
        } else {
            return false;
        }
    } else {
        return $_SESSION;
    }
}

function setFlash($message, $type = "success", $array = true)
{
    if ($array == true) {
        $_SESSION['flash'][] = [
            "message" => $message,
            "type" => $type,
        ];
    } else {
        $_SESSION['flash'] = [
            "message" => $message,
            "type" => $type,
        ];
    }
}

function isLogged()
{
    $nbArg = func_num_args();
    $data = func_get_args();

    if ($nbArg > 1) {
        return isset($_SESSION[$data[0]][$data[1]]);
    } else {
        return isset($_SESSION[$data[0]]);
    }
}
