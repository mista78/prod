<?php

function Bool($arg)
{
    return isset($arg) ? true : false;
}

function BuildHtml(array $data): string
{
    $html = '';
    foreach ($data as $value) {
        $content = $attributes = $tags = $children = null;
        extract($value);
        if (isset($condition) && !$condition) continue;
        $attributes = isset($attributes) ? $attributes : [];
        $attr = mapped_implode(' ', $attributes, "=");
        if ($tags !== null) {
            $html .= "<{$tags}{$attr}>";
        }
        if (isset($content)) {
            if (is_array($content)) {
                extract($content);
                [$funcName] = array_keys($content);
                [$arrays, $nameComponent] = array_merge($content[$funcName], [null]);
                foreach ($arrays as $item) {
                    $html .= isset($nameComponent) ? $funcName($nameComponent, $item)  : $funcName($item);
                }
            } else {
                $html .= $content;
            }
        }
        if (isset($children)) {
            $html .= BuildHtml($children);
        }
        if ($tags !== null) {
            $html .= "</$tags>";
        }
    }
    return $html;
}
