# word replacer wrapper

replace words on .docx files and convert to pdf


install with

    composer require jhon/word_replacer_wrapper

# Dependencies

    You need to install LibreOffice.

# Usage

You must include vendor/autoload.php

    require __DIR__ . '/vendor/autoload.php';

Class namespace

    use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;

Default configuration

    //template to process
    $template = 'templateDirectory/test.docx';

Define data to convert
    
    //array of ITypeTableChild elements
    $tableData = [
            [
                new Text('a'),
                new Text('b'),
            ],
            [
                new Text('d'),
                new Table($nestedTableData),
            ]
    ];
    
    //default configuration, array of IType elements
     
    $data = [
        'field1' => new Text('xxxxxxxxxxx'),
        'field2' => new Table($tableData),
        'field3' => new Image('./images/test.png', 80, 80),
    ];

    //folder for create files
    $temporalDirectory = 'testOne';

    $WordReplacerWrapper = new WordReplacerWrapper($template, $data, $temporalDirectory);

    //execute replace and convert to pdf
    $routes = $WordReplacerWrapper->replaceData();

    //routes
    ["template"]=>
      string(17) "testOne/test.docx"
    ["document"]=>
      string(26) "testOne/document_test.docx"
    ["pdf"]=>
      string(25) "testOne/document_test.pdf"

# Dinamic configuration

    $WordReplacerWrapper->setData(['field1' => new Text('sebastian')]);
    $WordReplacerWrapper->setTemporalDir('testTwo');
    $routes = $WordReplacerWrapper->replaceData();

    //routes
    ["template"]=>
      string(17) "testTwo/test.docx"
    ["document"]=>
      string(26) "testTwo/document_test.docx"
    ["pdf"]=>
      string(25) "testTwo/document_test.pdf"
      
# Header and footer
you must add header and footer key to data, implementing a Jsvptf\WordReplacerWrapper\types\Table class and the template file MUST has content on header and footer space, you can see it on     example/convertDocx.php

    $data = [
        'header' => new Table($tableData),
        'footer' => new Table($tableData)
        ...
    ];
    

You can find the complete example on example directory
