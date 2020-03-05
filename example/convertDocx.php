<?php

use Jsvptf\WordReplacerWrapper\types\Table;
use Jsvptf\WordReplacerWrapper\types\Text;
use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;

require __DIR__ . '/../vendor/autoload.php';

try {
    $tableData = [
        [
            new Text('a'),
            new Text('b'),
            new Text('c'),
        ],
        [
            new Text('d'),
            new Text('e'),
            new Text('f'),
        ]
    ];
    //default configuration
    $data = [
        'field1' => new Text('xxxxxxxxxxx'),
        'field2' => new Table($tableData),
        'field3' => new Text('zzzzzzzzzzz'),
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

