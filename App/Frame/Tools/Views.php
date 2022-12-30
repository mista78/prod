<?php 

    function View() {
        $args = func_get_args();
        $view = array_shift($args);
        $data = array_shift($args);
        $file = APP. "Views/{$view}.php";
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require $file;
            $content = ob_get_clean();
            return $content;
        }
        return "";
    }
    function hours_tofloat($val){
        if (empty($val)) {
            return 0;
        }
        $parts = explode(':', $val);
        return $parts[0] + floor(($parts[1]/60)*100) / 100;
    }

    function float_tohours($val){
        if (empty($val)) {
            return 0;
        }
        $hours = floor($val);
        $minutes = round(($val - $hours) * 60);
        // format minutes to 2 digits
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        // format hours to 2 digits
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        return $hours . ':' . $minutes;
    }