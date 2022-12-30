<?php

function render($page)
{
    global $request, $route, $rended, $conf, $d;
    if ($rended) {
        return false;
    }
    extract($d);
    if (strpos($page, '/') === 0) {
        $views = APP . "Module" . $page . ".php";
    } else {
        $views = APP . "Module" . DS . ucfirst($request['request']['controller']) . DS . 'Views' . DS . $page . ".php";
    }
    $view = (file_exists($views)) ? require_once $views : [
        "sections" =>  [
            [
                "attributes" => ["class" => "container"],
                "item" => [
                    [
                        "type" => "build",
                        "build" => [
                            [
                                "tags" => "h1",
                                "content" => "default page content"
                            ],
                            [
                                "tags" => "p",
                                "content" => "missing file " . str_replace(APP, '', $views)
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $theme = APP . "Theme" . DS . ($conf['tmp'] ?? "default") . DS . "index.php";
    $function = APP . "Theme" . DS . ($conf['tmp'] ?? "default") . DS . "function.php";
    if(file_exists($function)) {
        require_once $function;
    }
    if (file_exists($theme)) {
        
        require_once $theme;
    } else {
        echo getWidjet("Moodboard", $view);
    }
    $rended = true;
}
