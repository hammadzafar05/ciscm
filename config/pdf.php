<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('public/tmp/'),
    'font_path' => base_path('resources/fonts/'),
    'font_data' => [
        'dejavusans' => [
            'R'  => 'DejaVuSans.ttf',    // regular font
            'B'  => 'DejaVuSans-Bold.ttf',       // optional: bold font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
        // ...add as many as you want.
    ]
];
