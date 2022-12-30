<?php

echo BuildHtml([
    [
        // "tags" => (isset($tags)) ? $tags : 'section',
        "attributes" => $attributes ?? [],
        "condition" => Bool($item),
        "content" => [
            "Component" => [$item]
        ]
    ]
]);
