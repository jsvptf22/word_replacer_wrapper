<?php


namespace Jsvptf\WordReplacerWrapper;


use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class DataProcessor
{
    /**
     * @var string
     */
    private string $template;

    /**
     * @var array
     */
    private array $data;

    /**
     * DataProcessor constructor.
     * @param string $template
     * @param array $data
     */
    public function __construct(string $template, array $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * generate a file with data replaced
     * @param string|null $output
     * @return string
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function generateFile(string $output = null){
        if(!$output){
            $output = $this->template;
        }

        $TemplateProcessor = new TemplateProcessor($this->template);
        $TemplateProcessor->setValues($this->data);
        $TemplateProcessor->saveAs($output);

        return $output;
    }

    /**
     * set data to template
     *
     * @param string $template
     * @param array $data
     * @param string|null $output
     * @return string
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public static function replace(string $template, array $data, string $output = null){
        $Instance = new self($template, $data);
        return $Instance->generateFile($output);
    }
}