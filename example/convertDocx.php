<?php

use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;

require __DIR__ . '/../vendor/autoload.php';

$data = [
    'field1' => 'xxxxxxxxxxx',
    'field2' => 'yyyyyyyyyyy',
    'field3' => 'zzzzzzzzzzz',
];

//default configuration
$WordReplacerWrapper = new WordReplacerWrapper('templateDirectory/test.docx', $data, 'prueba1');
$routes = $WordReplacerWrapper->replaceData();

echo '<pre>';
var_dump($routes);
echo '</pre>';

//dinamic configuration
$WordReplacerWrapper->setData(['field1' => 'sebastian']);
$WordReplacerWrapper->setTemporalDir('prueba2');
$routes = $WordReplacerWrapper->replaceData();

echo '<pre>';
var_dump($routes);
echo '</pre>';
