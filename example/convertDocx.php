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
            ]), new Text('f'),
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
            new Image('./images/test.png', 80, 80),
            new Pagination('Page {PAGE} of {NUMPAGES}.'),
        ]
    ];
    //default configuration
    $data = [
        'header' => new Table($tableData),
        'footer' => new Table($tableData),
        'field2' => new Text('other word'),
        //'field1' => new Table($tableData),
        'field3' => new Image('./images/test.png', 80, 80),
        'field4' => new Pagination('Page {PAGE} of {NUMPAGES}.'),
        'styleText' => new Text('some with style', [
            'size' => 20,
            'bold' => true,
            'name' => 'Courier New'
        ])
    ];

    $WordReplacerWrapper = new WordReplacerWrapper(
        'templateDirectory/test.docx', //docx template
        $data, //data to replace
        'prueba1', //destination folder
        'libreoffice' //libreoffice binary
    );
    var_dump($WordReplacerWrapper->getRequiredFields());
    $routes = $WordReplacerWrapper->replaceData();

    echo '<pre>';
    var_dump($routes);
    echo '</pre>';

    //dynamic configuration
    $WordReplacerWrapper->setData(['field1' => new Text('sebastian')]);
    $WordReplacerWrapper->setWorkspace('prueba2');
    $routes = $WordReplacerWrapper->replaceData();

    echo '<pre>';
    var_dump($routes);
    echo '</pre>';
} catch (Throwable $th) {
    var_dump($th);
    exit;
}
