<?php


namespace Jsvptf\WordReplacerWrapper;


use ZipArchive;

class HeaderMerger
{

    /**
     * default name for meged result
     */
    const DEFAULT_MERGED_FILENAME = 'merged.docx';

    /**
     * @var string
     */
    private string $headerFile;

    /**
     * @var string
     */
    private string $templateFile;

    public function __construct(string $headerFile, string $templateFile)
    {
        $this->setHeaderFile($headerFile);
        $this->setTemplateFile($templateFile);
    }

    /**
     * @return string
     */
    public function getHeaderFile(): string
    {
        return $this->headerFile;
    }

    /**
     * @param string $headerFile
     */
    public function setHeaderFile(string $headerFile): void
    {
        $this->headerFile = $headerFile;
    }

    /**
     * @return string
     */
    public function getTemplateFile(): string
    {
        return $this->templateFile;
    }

    /**
     * @param string $templateFile
     */
    public function setTemplateFile(string $templateFile): void
    {
        $this->templateFile = $templateFile;
    }

    /**
     * get default output route
     * @return string
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function getDefaultOutput()
    {
        return sprintf(
            "%s/%s",
            Settings::getWorkspace(),
            self::DEFAULT_MERGED_FILENAME
        );
    }

    /**
     * add headers to template
     * @param string $output
     * @return string
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function merge(string $output = null)
    {
        if (!$output) {
            $output = $this->getDefaultOutput();
        }

        copy($this->getTemplateFile(), $output);
        $uncompressedDir = sprintf(
            "%s/%s",
            Settings::getWorkspace(),
            'uncompressedHeader' . rand()
        );

        $sourceZip = new ZipArchive();
        $sourceZip->open($this->getHeaderFile());
        $sourceZip->extractTo($uncompressedDir);
        $sourceZip->close();

        $finalZip = new ZipArchive();
        $finalZip->open($output);

        $headerRoute = $uncompressedDir . "/word/header1.xml";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/header1.xml');
        }

        $headerRoute = $uncompressedDir . "/word/footer1.xml";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/footer1.xml');
        }

        $finalZip->close();

        return $output;
    }
}