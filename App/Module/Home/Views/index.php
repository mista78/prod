<?php

$layouts["header"] = [
	[

		"attributes" => [
			"class" => "header"
		],
		"item" => [
			[
				"component" => "header",
			]
		]
	]
];

$layouts["sections"] = [
	[

		"item" => [
			[
				"component" => "text",
				"text" => "hello world"
			],
			// [
			// 	"component" => "block-chiffres",
			// ],
			// [
			// 	"component" => "block-demarche",
			// ],
			// [
			// 	"component" => "block-valeur-ajoutee",
			// ],
			// [
			// 	"component" => "banniere-contact",
			// ]

		]
	],
];
$layouts["footer"] = [
	[


		"item" => [
			[
				"component" => "footer",
			]
		]
	]
];
return $layouts;
