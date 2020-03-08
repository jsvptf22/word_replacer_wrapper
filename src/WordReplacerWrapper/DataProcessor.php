<?php


namespace Jsvptf\WordReplacerWrapper;


use Exception;
use Jsvptf\WordReplacerWrapper\types\IType;
use Jsvptf\WordReplacerWrapper\types\Table;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class DataProcessor
{
    /**
     * @var string
     */
    protected string $template;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var Table
     */
    protected Table $TableHeader;

    /**
     * @var Table
     */
    protected Table $TableFooter;

    /**
     * @var TemplateProcessor
     */
    protected TemplateProcessor $TemplateProcessor;

    /**
     * DataProcessor constructor.
     * @param string $template
     * @param array $data
     */
    public function __construct(
        string $template,
        array $data
    )
    {
        $this->setTemplate($template);
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        if (isset($data['header'])) {
            $this->setTableHeader($data['header']);
            unset($data['header']);
        }

        if (isset($data['footer'])) {
            $this->setTableFooter($data['footer']);
            unset($data['footer']);
        }

        $this->data = $data;
    }

    /**
     * @return Table
     */
    public function getTableHeader(): Table
    {
        return $this->TableHeader;
    }

    /**
     * @param Table $TableHeader
     */
    public function setTableHeader(Table $TableHeader): void
    {
        $this->TableHeader = $TableHeader;
    }

    /**
     * @return Table
     */
    public function getTableFooter(): Table
    {
        return $this->TableFooter;
    }

    /**
     * @param Table $TableFooter
     */
    public function setTableFooter(Table $TableFooter): void
    {
        $this->TableFooter = $TableFooter;
    }

    /**
     * @return TemplateProcessor
     */
    public function getTemplateProcessor(): TemplateProcessor
    {
        return $this->TemplateProcessor;
    }

    /**
     * @param TemplateProcessor $TemplateProcessor
     */
    public function setTemplateProcessor(TemplateProcessor $TemplateProcessor): void
    {
        $this->TemplateProcessor = $TemplateProcessor;
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
    public function generateFile(string $output = null)
    {
        $template = $this->checkHeaders();
        $TemplateProcessor = new TemplateProcessor($template);
        $this->setTemplateProcessor($TemplateProcessor);
        $this->replaceVars();

        $output = $output ?? $template;
        $this->getTemplateProcessor()->saveAs($output);

        return $output;
    }

    /**
     * generate a document with headers
     * @return string
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function checkHeaders()
    {
        $tableHeader = $this->getTableHeader();
        $tableFooter = $this->getTableFooter();

        if ($tableHeader || $tableFooter) {
            $HeaderGenerator = new HeaderGenerator($tableHeader, $tableFooter);
            $headerFile = $HeaderGenerator->generateFile();

            $template = $this->mergeFiles($headerFile, $this->getTemplate());
            $this->setTemplate($template);
        }

        return $this->getTemplate();
    }

    /**
     * add headers to template
     * @param string $headerFile
     * @param string $template
     * @return mixed
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function mergeFiles(string $headerFile, string $template)
    {
        $HeaderMerger = new HeaderMerger($headerFile, $template);
        return $HeaderMerger->merge();
    }

    /**
     * replace vars with their value
     *
     * @return void
     * @throws Exception
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function replaceVars()
    {
        foreach ($this->getData() as $key => $element) {
            if (!$element instanceof IType) {
                throw new Exception("{$key} is not a IType element");
            }

            $TemplatePlateProcessor = $this->getTemplateProcessor();
            $element->setTo($TemplatePlateProcessor, $key);
        }

        if (isset($TemplatePlateProcessor)) {
            $this->setTemplateProcessor($TemplatePlateProcessor);
        }
    }
}