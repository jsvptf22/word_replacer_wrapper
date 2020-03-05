<?php


namespace Jsvptf\WordReplacerWrapper;


use Exception;
use Jsvptf\WordReplacerWrapper\types\IType;
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
     * @var TemplateProcessor
     */
    private TemplateProcessor $TemplateProcessor;

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
     * @throws Exception
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function generateFile(string $output = null){
        if(!$output){
            $output = $this->template;
        }

        $this->TemplateProcessor = new TemplateProcessor($this->template);
        self::setData($this->data, $this->TemplateProcessor);
        $this->TemplateProcessor->saveAs($output);

        return $output;
    }

    /**
     * add element data TemplateProcessor
     * @param $data
     * @param $TemplateProcessor
     * @return void
     * @date 2020-03-05
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public static function setData($data, &$TemplateProcessor){
        foreach ($data as $key => $element){
            if(!$element instanceof  IType){
                throw new Exception("{$key} is not a IType element");
            }
            $element->setTo($TemplateProcessor, $key);
        }
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