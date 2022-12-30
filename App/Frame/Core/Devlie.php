<?php

use Corcel\Model\Menu;
use Corcel\Model\Post;
use Corcel\Model\Page;
// function recursiveMenuFill($returnValue, $items, $parentId) {
//     $array = [];
//     if(isset($items[$parentId])) {
//         foreach ($items[$parentId] as $item) {
//             [$meta] = array_merge([], array_filter($item['meta'],function($value){
//                 return $value['meta_key'] == '_menu_item_object_id';
//             }));
//             $realItem = Post::find($meta['meta_value']);
//             $array[] = [
//                 'id' => $realItem->ID,
//                 'title' => $realItem->post_title,
//                 'url' => $realItem->guid,
//                 'children' => recursiveMenuFill($returnValue, $items, $item['ID'])
//             ];
            

//         }
//     }
//     return $array;
// }


function Devlie()
{
    global $request, $route, $rended, $d;
    Loader(['Frame', 'Dep']);
    $request = Request();
    [$d, $regex, $genData] = [[], [
        "Prefix" => "@Prefix\((.*)\)",
        "GetUrl" => "@Route\((.*)\)",
    ], [], []];
    $initRoute = glob(APP . "Module" . DS . "*" . DS . "*");
    foreach ($initRoute as $model => $file) {
        foreach ($regex as $key => $value) {
            $method = GetMethod($value, $file);
            foreach ($method as $k => $v) {
                $v = explode(",", trim($v));
                unset($method[$k]);
                $key($v[0], trim($v[1]));
            }
        }
    }

    $Entities = glob(APP . "Entity" . DS . "*");
    foreach ($Entities as $key => $Entity) {
        $champsTable = strtolower(str_replace([APP . "Entity" . DS, ".php"], ["", ""], $Entity));
        $champsType = GetMethod('\* (.*)', $Entity);
        $champsName = GetMethod('\$(.*);', $Entity);
        foreach ($champsType as $k => $v) {
            $genData[$champsTable][strtolower(trim($champsName[$k]))] = strtolower(trim($v));
        }
    }
    genTable($genData);
    GetParser($request['url'], $request);
    loadController();
    if (isset($request['prefix'])) {
        $request['request']['action'] = $request['prefix'] . '_' . $request['request']['action'];
    }
    if (!in_array(trim($request['request']['action']), GetMethod("function ([\w]+)", controllerFile($request['request']['controller'])))) {
        echo "cette action n'exists pas";
        die();
        // Redirection();
    }
    $hook = APP . "Conf" . DS . "Hook.php";
    if (file_exists($hook)) {
        require_once $hook;
    }
    $d = call_user_func_array($request['request']['action'], $request['request']['params']);
    if (is_null($d)) {
        $d = [];
    }
    wordpresData($d);
    if ($d === 0) {
        $rended = true;
    }
    Render($request['request']['action']);
}
function controllerFile(string $text, $filename = "Controller")
{
    return APP . 'Module' . DS . ucfirst($text) . DS . ucfirst($text) . $filename . '.php';
}

function loadController()
{
    global $request, $route, $rended, $d;
    $file = controllerFile($request['request']['controller']);
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "ceux controlleur n'exists pas";
        die();
        // Redirection();
    }
}
