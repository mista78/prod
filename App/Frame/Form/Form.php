<?php


function FormStart($options = [])
{
    $attr = attribute($options, ['tags','methode','action']);
    $meth = isset($options['methode']) ? $options['methode'] : 'POST';
    $acti = isset($options['action']) ? $options['action'] : null;
    return "<form method='{$meth}' action='{$acti}'  {$attr}>";
}

function FormEnd($html = null) {
    return "</form>";
}

function input($name, $label = null, $options = [])
{
    $html  = "";
    $exeception =  ['type','surrond'];
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $attr  = attribute($options, $exeception);
    $type  = isset($options['type']) ? $options['type'] : 'text';
    $label = label($name, $label);
    $html .= '<input id="input'.ucfirst($name).'" type="'.$type.'" ';
    $html .=' name="'.$name.'"  value="'. getValue($name) .'" '.$attr.' />';
    return $label . surround($html, $surrond);
}

function textarea($name, $label = null, $options = [])
{
    $html = "";
    $attr = attribute($options, ['surrond']);
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $label = label($name, $label);
    $html = '<textarea name="' . $name .'" '. $attr .'>'. getValue($name) .'</textarea>';
    return $label . surround($html, $surrond);
}

function select($name, $label, $options = [])
{
    $tabs = [];
    $attr = attribute($options, ['surrond','options']);
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $options['options'] = isset($options['options']) ? $options['options'] : [];
    $label = label($name, $label);
    $html = "<select name='$name' $attr>";
    foreach ($options['options'] as $k => $v) {
        if (getValue($name) == $k) {
            $selected = "selected";
        } else {
            $selected = "";
        }

        $html .= "<option value='$k' $selected> $v </option>";
    }
    $html .= "</select>";
    return $label . surround($html, $surrond);
}

function datalist($name, $label, $options = [])
{
    $tabs = [];
    $attr = attribute($options, ['surrond','options']);
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $options['options'] = isset($options['options']) ? $options['options'] : [];
    $label = label($name, $label);
    $html = "<input list='idMa{$name}' id='id{$name}' name='$name' step='1'/>";
    $html .= "<datalist id='idMa{$name}'>";
    foreach ($options['options'] as $k => $v) {
        if (getValue($name) == $k) {
            $selected = "selected";
        } else {
            $selected = "";
        }

        $html .= "<option value='$v'>";
    }
    $html .= "</datalist>";
    return $label . surround($html, $surrond);
}

function checkbox($name, $label = null, $options = array())
{

    $exeception =  ['type','surrond'];
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $attr  = attribute($options, $exeception);
    $type  = isset($options['type']) ? $options['type'] : 'checkbox';
    $label = label($name, $label);

    $value = getValue($name);

    $html = "<input type='hidden' name='$name' value='0'> <input class='check' type='$type' name='$name' value='1' ". (empty($value && $value > 0) ? '' : 'checked') .">";
    if (isset($options['message'])) {
        $html .= tag($options['message'], "span", "check");
    }
    return $label . surround($html, $surrond);
}

function submit($options = [])
{
    $surrond = isset($options['surrond']) ? $options['surrond'] : [];
    $attr  = attribute($options, ['surrond']);
    $name = isset($options["name"]) ? $options["name"] : "Envoyer";
    $html = '<button type="submit" ' . $attr . '>'. $name .'</button> ';

    if (isset($options['return'])) {
        $html .= '<a href="/" class="btn btn-default">Return</a>';
    }
    return surround($html, $surrond);
}
