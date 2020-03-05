<?php

namespace Jsvptf\WordReplacerWrapper;

use Exception;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;
use NcJoes\OfficeConverter\OfficeConverter;
use NcJoes\OfficeConverter\OfficeConverterException;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

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
     * route to template
     *
     * @var string
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected string $templateRoute;

    /**
     * directory to save files
     *
     * @var string
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected string $temporalDir;

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
     * @param string|null $temporalDir
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function __construct(
        string $templateRoute,
        array $data = [],
        string $temporalDir = null
    )
    {
        $this->setTemporalDir($temporalDir);
        $this->setTemplate($templateRoute);
        $this->setData($data);
    }

    /**
     * define the temporal directory
     *
     * @param string $temporalDir
     * @return boolean
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function setTemporalDir(string $temporalDir = null)
    {
        if (!$temporalDir) {
            $temporalDir = sys_get_temp_dir();
        }

        if (RouteVerifier::checkDirectory($temporalDir)) {
            $this->temporalDir = $temporalDir;
        }

        return true;
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
     * define data to replace
     *
     * @param array $data
     * @return array
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public function setData(array $data)
    {
        return $this->data = $data;
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
        $template = $this->generateTemporalTemplate();
        $document = $this->processTemplate($template);
        $pdf = $this->convertDocument($document);

        return [
            'template' => sprintf("%s/%s", $this->temporalDir, $template),
            'document' => sprintf("%s/%s", $this->temporalDir, $document),
            'pdf' => sprintf("%s/%s", $this->temporalDir, $pdf),
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

        $adapter = new LocalAdapter($this->temporalDir);
        $this->Filesystem = new Filesystem($adapter);
        $this->Filesystem->write($filename, $content, true);

        return $filename;
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
    public function processTemplate(string $template)
    {
        $temporal = sprintf("%s/%s", $this->temporalDir, $template);
        $document = sprintf("%s/document_%s", $this->temporalDir, $template);

        $file = DataProcessor::replace($temporal, $this->data, $document);
        return basename($file);
    }

    /**
     * converts document to pdf and html
     *
     * @param string $document
     * @return string
     * @throws OfficeConverterException
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    protected function convertDocument(string $document)
    {
        $temporal = sprintf("%s/%s", $this->temporalDir, $document);
        $filename = pathinfo($document, PATHINFO_FILENAME);
        $newName = "{$filename}.pdf";

        $converter = new OfficeConverter($temporal);
        $converter->convertTo($newName);

        return $newName;
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
