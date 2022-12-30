<?php
// Debug($dataComponent);



$header = (isset($dataComponent['header'])) ? [[
    "tags" => "header",
    "attributes" => [
		"class" => "header",
		"id" => "header"
	],
    "condition" => Bool($header),
    "content" => [
        "getWidjet" => [
            $header,
            "sections"
        ]
    ]
]] : [];
$sections = (isset($dataComponent['sections'])) ? [[
    "tags" => "main",
    "condition" => Bool($sections),
    "content" => [
        "getWidjet" => [
            $sections,
            "sections"
        ]
    ]
]] : [];
$footer = (isset($dataComponent['footer'])) ? [[
    "tags" => "footer",
    "condition" => Bool($footer),
    "attributes" => [
        "class" => "page-footer",
        "role" => "complementary"
    ],
    "content" => [
        "getWidjet" => [
            $footer,
            "sections"
        ]
    ]
]] : [];

$pages = array_merge($header, $sections, $footer);

echo BuildHtml([
    ...$pages
]);
