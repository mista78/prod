<?php

function tags($html, $options = [])
{
    $attr = attribute($options, ['tags', 'start', 'end']);
    $args = isset($options['tags']) ? $options['tags'] : 'div';
    $views = "";
    if(isset($options['start'])) {
        $views = '<'.$args.' '. $attr .'>'.$html;
    } elseif (isset($options['end'])) {
        $views = '</'.$args.'>';
    } else {
        $views = '<'.$args.' '. $attr .'>'.$html.'</'.$args.'>';
    }
    return $views;
}
function tag($html, $tags = "div", $class = null) {
    return "<$tags class='$class'>$html</$tags>";
}

function surround($html, $options = [])
{
    $views = $html;
    $argAttr = attribute($options, ['type']);
    $type = isset($options['type']) ? $options['type'] : 'p';
    if ($options != []) {
        $views = '<'.$type.' '. $argAttr .'>'.$html.'</'.$type.'>';
    }
    return $views;
}

function getValue($name)
{
    return isset($_POST[$name]) ? $_POST[$name] : null;
}

function label($name, $label)
{
    if ($label != null) {
        if (!is_array($label)) {
            $label = "<label id='input$name' > $label </label>";
        } else {
            if ($label['name'] == 'img') {
                $labelAttr = attribute($label, ['name']);
                $label['name'] = isset($label['name']) ? $label['name'] : $name;
                $label = "<label $labelAttr> <img src ='".WEBROOT."captch.php'> </label>";
            } else {
                $labelAttr = attribute($label, ['name']);
                $label['name'] = isset($label['name']) ? $label['name'] : $name;
                $label = "<label $labelAttr> {$label['name']} </label>";
            }
        }
    }
    return $label;
}

function attribute($options, $exception = [])
{
    $i = 0;
    $labelAttr = "";
    foreach ($options as $k => $v) {
        if (!in_array($k, $exception)) {
            if ($k == "solo") {
                $labelAttr .= " $v ";
            } else {
                $labelAttr .= " $k='$v' ";
            }
        }
    }
    return $labelAttr;
}


function modifieValeur($array , $keys, $remplace)
{   
    $cle = array_search('actualites',$array);

    $array[$keys] = 'lol';
    var_dump($array);

    return $array;
    if($cle!==false)
    {
        $array[$cle]=$remplace;
        return $array;
    }
 
}