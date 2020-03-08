<?php

namespace Jsvptf\WordReplacerWrapper;

use Exception;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use NcJoes\OfficeConverter\OfficeConverter;
use NcJoes\OfficeConverter\OfficeConverterException;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;

class WordReplacerWrapper
{
    /**
     * instance of Filesystem
     *
     * @var Filesystem
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected Filesystem $Filesystem;

    /**
     * @var DataProcessor|null
     */
    protected ?DataProcessor $DataProcessor = null;

    /**
     * route to template
     *
     * @var string
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected string $templateRoute;

    /**
     * data to replace
     *
     * @var array
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected array $data;

    /**
     * initial configuration
     *
     * @param string $templateRoute
     * @param array $data
     * @param string|null $workspace
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function __construct(
        string $templateRoute,
        array $data = [],
        string $workspace = null
    )
    {
        $this->setWorkspace($workspace);
        $this->setTemplate($templateRoute);
        $this->setData($data);
    }

    /**
     * @param string $template
     * @return DataProcessor|null
     */
    public function getDataProcessor(string $template): ?DataProcessor
    {
        $DataProcessor = $this->DataProcessor;

        if ($this->DataProcessor instanceof DataProcessor) {
            $DataProcessor->setTemplate($template);
            $DataProcessor->setData($this->getData());
        } else {
            $DataProcessor = new DataProcessor($template, $this->getData());
        }

        $this->setDataProcessor($DataProcessor);

        return $this->DataProcessor;
    }

    /**
     * @param DataProcessor|null $DataProcessor
     */
    public function setDataProcessor(?DataProcessor $DataProcessor): void
    {
        $this->DataProcessor = $DataProcessor;
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
        $this->data = $data;
    }


    /**
     * define temporal directory to save files
     * @param string $workspace
     * @return void
     * @throws Exception
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function setWorkspace(string $workspace): void
    {
        Settings::setWorkspace($workspace);
    }

    /**
     * define the template route
     *
     * @param string $templateRoute
     * @return bool
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     *
     * @date 2020
     */
    public function setTemplate(string $templateRoute)
    {
        if (RouteVerifier::checkFile($templateRoute, self::acceptedExtensions())) {
            $this->templateRoute = $templateRoute;
        }

        return true;
    }

    /**
     * make a document based on template
     *
     * @return array
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @throws OfficeConverterException
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function replaceData()
    {
        $temporalTemplate = $this->generateTemporalTemplate();
        $document = $this->processTemplate($temporalTemplate);
        $pdf = $this->convertDocument($document);

        return [
            'template' => $temporalTemplate,
            'document' => $document,
            'pdf' => $pdf
        ];
    }

    /**
     * copy template to temporal directory
     *
     * @return string
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function generateTemporalTemplate()
    {
        $filename = basename($this->templateRoute);
        $content = file_get_contents($this->templateRoute);

        $workspace = Settings::getWorkspace();
        $adapter = new LocalAdapter($workspace);
        $this->Filesystem = new Filesystem($adapter);
        $this->Filesystem->write($filename, $content, true);

        return sprintf("%s/%s", $workspace, $filename);
    }

    /**
     * replace data on template and converts to pdf
     *
     * @param string $template
     * @return string
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected function processTemplate(string $template)
    {
        $output = sprintf("%s/document.docx", Settings::getWorkspace());
        $DataProcessor = $this->getDataProcessor($template);
        return $DataProcessor->generateFile($output);
    }

    /**
     * converts document to pdf
     *
     * @param string $document
     * @return string
     * @throws OfficeConverterException
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected function convertDocument(string $document)
    {
        $workspace = Settings::getWorkspace();
        $filename = basename($document);
        $destination = "{$filename}.pdf";

        $converter = new OfficeConverter($document, $workspace);
        $converter->convertTo($destination);

        return sprintf("%s/%s", $workspace, $destination);
    }

    /**
     * accepted template extensions
     *
     * @return array
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public static function acceptedExtensions()
    {
        return ['docx'];
    }
}
