# word_replacer_wrapper
replace words on docx files and converts to pdf

install with
    composer require jhon/word_replacer_wrapper
    
    

you must include vendor/autoload.php

    require __DIR__ . '/vendor/autoload.php';

class namespace

    use Jsvptf\WordReplacerWrapper\WordReplacerWrapper;




default configuration

    //template to process
    $template = 'templateDirectory/test.docx';

    //define data to convert
    $data = [
        'field1' => 'text to replace on field1',
        'field2' => 'text to replace on field2',
        'field3' => 'text to replace on field3',
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



dinamic configuration

    $WordReplacerWrapper->setData(['field1' => 'sebastian']);
    $WordReplacerWrapper->setTemporalDir('testTwo');
    $routes = $WordReplacerWrapper->replaceData();

    //routes
    ["template"]=>
      string(17) "testTwo/test.docx"
    ["document"]=>
      string(26) "testTwo/document_test.docx"
    ["pdf"]=>
      string(25) "testTwo/document_test.pdf"
