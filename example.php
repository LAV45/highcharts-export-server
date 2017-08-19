<?php

function getStream($data, $format)
{
    $data = [
        'infile' => json_encode($data),
        'constr' => 'Chart',
        'type' => $format
    ];
    $data = json_encode($data);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/json',
            'content' => $data
        ]
    ]);

    $data = file_get_contents('http://localhost:3000', false, $context);

    if ($format !== 'svg') {
        $data = base64_decode($data);
    }

    return $data;
}

$data = [
    [1, 1],
    [2, 4],
    [3, 6],
    // ...
];

$format = 'jpg';

$options = [
    'yAxis' => [
        'title' => [
            'text' => null,
        ]
    ],
    'legend' => [
        'enabled' => false
    ],
    'title' => [
        'text' => null
    ],
];

$options['series'][0]['data'] = $data;

$fileData = getStream($options, $format);

file_put_contents("graphic.{$format}", $fileData);