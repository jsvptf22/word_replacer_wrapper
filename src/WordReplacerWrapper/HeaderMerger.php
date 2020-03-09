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
     * @return string
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function getUncompressedRoute(): string
    {
        return sprintf(
            "%s/%s",
            Settings::getWorkspace(),
            'uncompressedHeader'
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

        $this->extractSource();
        $this->moveFiles($output);

        return $output;
    }

    /**
     * extract a .docx file to get files
     * @return void
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function extractSource(): void
    {
        $uncompressedDir = $this->getUncompressedRoute();

        $sourceZip = new ZipArchive();
        $sourceZip->open($this->getHeaderFile());
        $sourceZip->extractTo($uncompressedDir);
        $sourceZip->close();
    }

    /**
     * @param string $output
     * @return void
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function moveFiles(string $output): void
    {
        $finalZip = new ZipArchive();
        $finalZip->open($output);

        $this->moveHeaders($finalZip);
        $this->moveMedia($finalZip);
        $this->moveRels($finalZip);

        $finalZip->close();
    }

    /**
     * move header1 and footer1 to zip
     * @param ZipArchive $finalZip
     * @return void
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function moveHeaders(ZipArchive &$finalZip): void
    {
        $headerRoute = $this->getUncompressedRoute() . "/word/header1.xml";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/header1.xml');
        }

        $headerRoute = $this->getUncompressedRoute() . "/word/footer1.xml";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/footer1.xml');
        }
    }

    /**
     * move media files
     * @param ZipArchive $finalZip
     * @return void
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function moveMedia(ZipArchive &$finalZip): void
    {
        $mediaRoute = "word/media/";
        $mediaDirectory = sprintf(
            "%s/%s",
            $this->getUncompressedRoute(),
            $mediaRoute
        );

        if (!is_dir($mediaDirectory)) {
            return;
        }

        $files = scandir($mediaDirectory);

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $fileRoute = sprintf("%s/%s", $mediaDirectory, $file);
            $destination = sprintf("%s/%s", $mediaRoute, $file);

            $finalZip->addFile($fileRoute, $destination);
        }
    }

    /**
     * move footer and header rel files
     * @param ZipArchive $finalZip
     * @return void
     * @date 2020-03-08
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function moveRels(ZipArchive &$finalZip): void
    {
        $headerRoute = $this->getUncompressedRoute() . "/word/_rels/header1.xml.rels";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/_rels/header1.xml.rels');
        }

        $headerRoute = $this->getUncompressedRoute() . "/word/_rels/footer1.xml.rels";
        if (is_file($headerRoute)) {
            $finalZip->addFile($headerRoute, 'word/_rels/footer1.xml.rels');
        }
    }
}