<?php

if (CacheRead('wp_content_dates' . $wp_item['post_name']) != $wp_item['post_modified']) {
    $middleware = APP . 'Frame' . DS . 'Middleware';
    $content = reuseableBlock($wp_item['post_content']);
    $pattern = '/<!--.*-->/i';
    $content = preg_replace($pattern, '', $content);
    if (is_dir($middleware)) {
        $middleware = glob($middleware . DS . '*.php');
        foreach ($middleware as $key => $value) {
            $value = pathinfo($value, PATHINFO_FILENAME);
            if (function_exists($value)) {
                $content = $value($content, $wp_item);
            }
        }
    }
    $content =  BuildHtml([
        [
            "attributes" => ["class" => "Content"],
            "content" => $content
        ]
    ]);
    $content = CacheWrite('wp_contents' . $wp_item['post_name'], $content);
    CacheWrite('wp_content_dates' . $wp_item['post_name'], $wp_item['post_modified']);
    echo $content;
} else {
    echo CacheRead('wp_contents' . $wp_item['post_name'], [], "1");
}
