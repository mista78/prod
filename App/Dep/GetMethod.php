<?php

function GetMethod(string $path, string $file, $index = 1): array
{
	$data = [];
	if (is_file($file) &&  file_exists($file)) {
		$file =  file_get_contents($file);
		preg_match_all('#' . $path . '#', $file, $matches);
		foreach ($matches[$index] as  $value) {
			$data[] = $value;
		}
	}
	return $data;
}


function maps($cb, ...$data)
{
	return array_map($cb, $data);
}
