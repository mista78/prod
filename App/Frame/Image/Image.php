<?php

function resizedName($file)
{
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
    $html = "<picture>";
    $info = pathinfo($file);
    $chemin = ROOT . "Public/img" . DS . $file;
    $imgsize = getimagesize($chemin);

    foreach ($sizeAvailable as $key => $mx) {
        if ($mx["size"] <= $imgsize[0]) {
            $max = $mx["name"];
        }
    }
    foreach ($sizeAvailable as $key => $mx) {
        if ($mx["size"] <= $imgsize[0]) {
            $min = $mx["name"];
            $html .= "<source media='(max-width: " . $mx["size"] . "px)' srcset='" . WEBROOT . "Public/img/" . $info['filename'] . "_" . $max . "_" . $min . "." . $info['extension'] . "' alt='".$file."' />";
        }
    }
    $html .= "<img src='" . WEBROOT . "Public/img" . DS . $file . "' alt='".$file."' />";
    $html .= "</picture>";
    return $html;
}

# function resize image
function resizeImage($chemin, $sizename, $size, $width, $height)
{
    $info = pathinfo($chemin);
    $infosize                         = getimagesize($chemin);
    list($width_old, $height_old) = $infosize;
    $heightRatio = $height_old / $height;
    $widthRatio  = $width_old /  $width;

    $optimalRatio = $widthRatio;
    if ($heightRatio < $widthRatio) {
        $optimalRatio = $heightRatio;
    }
    $height_crop = ($height_old / $optimalRatio);
    $width_crop  = ($width_old  / $optimalRatio);

    $extension = $info['extension'];
    $newname = $info['dirname'] . '/' . $info['filename'] . '_' . $sizename . '_' . $size . '.' . $extension;
    $image = null;
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
        case 'JPG':
        case 'JPEG':
            $image = imagecreatefromjpeg($chemin);
            break;
        case 'png':
        case 'PNG':
            # color transparent

            $image = imagecreatefrompng($chemin);

            break;
        case 'gif':
        case 'GIF':
            $image = imagecreatefromgif($chemin);
            break;
    }
    $new = imagecreatetruecolor($width_crop, $height_crop);
    imagesavealpha($new, true);
    $transparent = imagecolorallocatealpha($new, 0, 0, 0, 127);
    imagefill($new, 0, 0, $transparent);
    imagecopyresampled($new, $image, 0, 0, 0, 0, $width_crop, $height_crop, imagesx($image), imagesy($image));
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
        case 'JPG':
        case 'JPEG':
            imagejpeg($new, $newname);
            break;
        case 'png':
        case 'PNG':
            imagepng($new, $newname);
            break;
        case 'gif':
        case 'GIF':
            imagegif($new, $newname);
            break;
    }
    imagedestroy($new);
    imagedestroy($image);
}
