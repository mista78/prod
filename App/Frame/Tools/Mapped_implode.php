<?php


/**
 * @ref https://www.php.net/manual/fr/function.implode.php#124942
 */
function mapped_implode($glue, $array, $symbol = '=')
{
    if (!is_array($array)) return '';
    return implode(
        $glue,
        array_map(
            function ($k, $v) use ($symbol) {
                return  ' ' . $k . $symbol . "'$v'";
            },
            array_keys($array),
            array_values($array)
        )
    );
}


function wp_meta($item, $key = "") {
    $prefix = denv("PREFIX") ?? "";
    $item['meta'] = Find([
        'table' => "{$prefix}postmeta",
        "where" => "{$prefix}postmeta.post_id='{$item['ID']}'",
        "database" => "wordpress",
    ]);
    $meta = [];
    foreach ($item['meta'] as $value) {
        $meta[$value['meta_key']] = $value['meta_value'];
    }
    if ($key) {
        return $meta[$key];
    }
    return $meta;
}