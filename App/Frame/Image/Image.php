<?php  

function resizedName($file, $size){
    $sizeAvailable = [
        [
            "name" => "xs",
            "size" => 400,
        ],
        [
            "name" => "sm",
            "size" => 800,
        ],
        [
            "name" => "md",
            "size" => 1500,
        ],
        [
            "name" => "lg",
            "size" => 3000,
        ],
    ];
    $info = pathinfo($file);
    $chemin = WEBROOT . "img" . DS . $file;
    $imgsize = getimagesize($chemin);

    foreach ($sizeAvailable as $key => $mx) {
        if($mx["size"] <= $imgsize[0]) {
            $max = $mx["name"];
        }
    }
    foreach ($sizeAvailable as $key => $mx) {
        $size = ($size < $imgsize[0]) ? $size : $imgsize[0];
        if($mx["size"] <= $size) {
            $min = $mx["name"];
        }
    }
    $return = WEBROOT . "img/" . $info['filename'] . '_max_' . $max . '_' . $min . '.' . $info['extension'];
    return $return;
}

function resizeImage($file, $max, $min, $width, $height){
    $pathinfo   = pathinfo(trim($file, '/'));
    $output     = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_max_' . $max . '_' . $min . '.' . $pathinfo['extension'];

    $info                         = getimagesize($file);
    list($width_old, $height_old) = $info;

    switch ( $info[2] ) {
        case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
        case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
        case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
        default: return false;
    }

    $heightRatio = $height_old / $height;
    $widthRatio  = $width_old /  $width;

    $optimalRatio = $widthRatio;
    if ($heightRatio < $widthRatio) {
        $optimalRatio = $heightRatio;
    }
    $height_crop = ($height_old / $optimalRatio);
    $width_crop  = ($width_old  / $optimalRatio);

    $image_crop = imagecreatetruecolor( $width_crop, $height_crop );
    $image_resized = imagecreatetruecolor($width, $height);

    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
        $transparency = imagecolortransparent($image);
        if ($transparency >= 0) {
            $transparent_color  = imagecolorsforindex($image, $trnprt_indx);
            $transparency       = imagecolorallocate($image_crop, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
            imagefill($image_crop, 0, 0, $transparency);
            imagecolortransparent($image_crop, $transparency);
            imagefill($image_resized, 0, 0, $transparency);
            imagecolortransparent($image_resized, $transparency);
        }elseif ($info[2] == IMAGETYPE_PNG) {
            imagealphablending($image_crop, false);
            imagealphablending($image_resized, false);
            $color = imagecolorallocatealpha($image_crop, 0, 0, 0, 127);
            imagefill($image_crop, 0, 0, $color);
            imagesavealpha($image_crop, true);
            imagefill($image_resized, 0, 0, $color);
            imagesavealpha($image_resized, true);
        }
    }

    imagecopyresampled($image_crop, $image, 0, 0, 0, 0, $width_crop, $height_crop, $width_old, $height_old);
    imagecopyresampled($image_resized, $image_crop, 0, 0, ($width_crop - $width) / 2, ($height_crop - $height) / 2, $width, $height, $width, $height);

    switch ( $info[2] ) {
      case IMAGETYPE_GIF:   imagegif($image_resized, $output, 80);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, 80);   break;
      case IMAGETYPE_PNG:   imagepng($image_resized, $output, 9);    break;
      default: return false;
    }

    return true;
}
