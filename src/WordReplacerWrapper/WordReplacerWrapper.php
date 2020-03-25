<?php

namespace Jsvptf\WordReplacerWrapper;

use Exception;
use NcJoes\OfficeConverter\OfficeConverter;
use NcJoes\OfficeConverter\OfficeConverterException;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class WordReplacerWrapper
{
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
        if (RouteVerifier::checkFile($templateRoute)) {
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
     * @throws Exception
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
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function generateTemporalTemplate()
    {
        $workspace = Settings::getWorkspace();
        $filename = basename($this->templateRoute);
        $route = sprintf("%s/%s", $workspace, $filename);

        $content = file_get_contents($this->templateRoute);

        if (!file_put_contents($route, $content)) {
            throw new Exception("Error al copiar la plantilla");
        }

        return $route;
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
     * get the required variables to replace
     * @return string[]
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @throws Exception
     * @date 2020-03-25
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function getRequiredFields()
    {
        $temporalTemplate = $this->generateTemporalTemplate();
        $TemplateProcessor = new TemplateProcessor($temporalTemplate);
        return $TemplateProcessor->getVariables();
    }
}
