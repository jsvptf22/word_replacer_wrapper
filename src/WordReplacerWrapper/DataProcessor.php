<?php


namespace Jsvptf\WordReplacerWrapper;


use Exception;
use Jsvptf\WordReplacerWrapper\types\HtmlHeader;
use Jsvptf\WordReplacerWrapper\types\IType;
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
     * @var HtmlHeader
     */
    protected HtmlHeader $htmlHeader;

    /**
     * @var HtmlHeader
     */
    protected HtmlHeader $htmlFooter;

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
            $this->setHtmlHeader($data['header']);
            unset($data['header']);
        }

        if (isset($data['footer'])) {
            $this->setHtmlFooter($data['footer']);
            unset($data['footer']);
        }

        $this->data = $data;
    }

    /**
     * @return HtmlHeader
     */
    public function getHtmlHeader(): HtmlHeader
    {
        return $this->htmlHeader;
    }

    /**
     * @param HtmlHeader $htmlHeader
     */
    public function setHtmlHeader(HtmlHeader $htmlHeader): void
    {
        $this->htmlHeader = $htmlHeader;
    }

    /**
     * @return HtmlHeader
     */
    public function getHtmlFooter(): HtmlHeader
    {
        return $this->htmlFooter;
    }

    /**
     * @param HtmlHeader $htmlFooter
     */
    public function setHtmlFooter(HtmlHeader $htmlFooter): void
    {
        $this->htmlFooter = $htmlFooter;
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
        $htmlHeader = $this->getHtmlHeader();
        $htmlFooter = $this->getHtmlFooter();

        if ($htmlHeader || $htmlFooter) {
            $HeaderGenerator = new HeaderGenerator($htmlHeader, $htmlFooter);
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