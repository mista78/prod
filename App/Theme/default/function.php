<?php


// if (isset($_GET['display']) && $_GET['display'] == "before") {
// 	$html = "";
// 	$html = CacheStart("style", function () {
// 		$dir = ROOT . 'Public' . DS . 'assets' . DS . 'sc/css/' . DS;
// 		$style = glob($dir . '*.css');
// 		$re = '/styles-(screen|print)-?(and-)?(.*).?(-)?(.*).?(\..+)/m';
// 		$html = "";
// 		foreach ($style as $value) {
// 			preg_match_all($re, $value, $matches, PREG_SET_ORDER, 0);
// 			if ($matches) {
// 				[0 => $file, 1 => $vue, 3 => $media] = $matches[0];
// 				$content = file_get_contents($dir . $file);
// 				if ($vue === "print") {
// 					$html .= "<style media='$vue'>" . $content . "</style>";
// 				} else if ($vue === "all") {
// 					$html .= "<style>" . $content . "</style>";
// 				} else if ($vue === "screen") {
// 					if ($media === "all") {
// 						$html .= "<style>" . $content . "</style>";
// 					} else {
// 						[$break, $width, $size, $extend] = explode("-", $media);
// 						$html .= "<style media='({$break}-{$width}:{$size}{$extend})' type='text/css'>$content</style>";
// 					}
// 				}
// 			}
// 		}
// 		return $html;
// 	});
// 	Config("test", $html);
// }
