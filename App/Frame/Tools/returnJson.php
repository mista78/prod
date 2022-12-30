<?php

function returnJson($data, $name = 'test')
{
    header('Content-type: application/json');
    echo json_encode($data);
    die();
}

function getMeta(string $mixed = "", array $needle = []): array
{

    $data = [];
    foreach ($needle as $value) {
        extract($value);
        if ($meta_key === $mixed) {
            $data = $value;
            break;
        }
    }
    return $data;
}

if (!function_exists('str_starts_with')) {
    function str_starts_with($str, $start)
    {
        return (@substr_compare($str, $start, 0, strlen($start)) == 0);
    }
}
