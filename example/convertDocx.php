<?php

use Jsvptf\WordReplacerWrapper\types\Image;
use Jsvptf\WordReplacerWrapper\types\Pagination;
use Jsvptf\WordReplacerWrapper\types\Table;
use Jsvptf\WordReplacerWrapper\types\Text;
use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;

require __DIR__ . '/../vendor/autoload.php';

try {
    $nestedTableData = [
    [
        new Text('a'),
        new Image('./images/test.png', 80, 80),
        new Text('c'),
    ],
        [
            new Text('d'),
            new Table([
                [
                    new Text('x'),
                    new Text('y'),
                    new Text('z'),
                ],
                [
                    new Text('q'),
                    new Image('./images/test.png', 180, 180),
                    new Text('y'),
                ]
            ]),
            new Text('f'),
        ]
    ];

    $tableData = [
        [
            new Text('a'),
            new Text('b'),
            new Text('c'),
        ],
        [
            new Text('d'),
            new Table($nestedTableData),
            new Pagination('Page {PAGE} of {NUMPAGES}.'),
        ]
    ];
    //default configuration
    $data = [
        'field1' => new Text('xxxxxxxxxxx'),
        'field2' => new Table($tableData),
        'field3' => new Image('./images/test.png', 80, 80),
        'field4' => new Pagination('Page {PAGE} of {NUMPAGES}.')
    ];

    $WordReplacerWrapper = new WordReplacerWrapper('templateDirectory/test.docx', $data, 'prueba1');
    $routes = $WordReplacerWrapper->replaceData();

    echo '<pre>';
    var_dump($routes);
    echo '</pre>';

    //dynamic configuration
    $WordReplacerWrapper->setData(['field1' => new Text('sebastian')]);
    $WordReplacerWrapper->setTemporalDir('prueba2');
    $routes = $WordReplacerWrapper->replaceData();

    echo '<pre>';
    var_dump($routes);
    echo '</pre>';
} catch (Exception $e) {
    var_dump($e->getMessage());
    exit;
}

