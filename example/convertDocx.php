<?php

use Jsvptf\WordReplacerWrapper\types\Text;
use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;

require __DIR__ . '/../vendor/autoload.php';

try {
    //default configuration
    $data = [
        'field1' => new Text('xxxxxxxxxxx'),
        'field2' => new Text('yyyyyyyyyyy'),
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

