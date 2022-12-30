<?php

if (file_exists(ROOT . '.env')) {
    $envVars    = file_get_contents(ROOT . '.env');
    $array = explode(PHP_EOL, $envVars);
    foreach ($array as $key => $value) {
        $env = explode('=', $value);
        if (count($env) == 2) {
            $env[0] = trim($env[0]);
            $env[1] = trim($env[1]);
            if ($env[0] != '' && $env[1] != '') {
                $_ENV[$env[0]] = $env[1];
            }
        }
    }
    foreach ($array as $key => $value) {
        $value = trim($value);
        if (!empty($value)) {
            putenv($value);
        };
    }
}
function denv($key, $value = null)
{
    if (is_null($value)) {
        return isset($_ENV[$key]) ? $_ENV[$key] : null;
    }
    $_ENV[$key] = $value;
}
if(file_exists(ROOT . 'vendor/autoload.php')){
    require_once ROOT . 'vendor/autoload.php';
}
function Loader($path)
{
    if (is_array($path)) {

        foreach ($path as $value) {

            Checked($value);
        }
    } else {

        Checked($path);
    }
}

function Checked($path = "Module")
{

    $test = glob(APP . $path . "/*");

    foreach ($test as $key => $value) {
        if (is_file($value)) {
            require_once $value;
        } else {
            Checked(str_replace(APP, '', $value));
        }
    }
}
