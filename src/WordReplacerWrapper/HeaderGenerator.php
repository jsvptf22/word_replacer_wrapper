<?php


namespace Jsvptf\WordReplacerWrapper;


use Jsvptf\WordReplacerWrapper\types\HtmlHeader;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class HeaderGenerator
{
    /**
     * default name for headers filename
     */
    const DEFAULT_HEADER_FILENAME = 'headers.docx';

    /**
     * @var string
     */
    protected string $filename;

    /**
     * @var HtmlHeader
     */
    protected HtmlHeader $htmlHeader;

    /**
     * @var HtmlHeader
     */
    protected HtmlHeader $htmlFooter;

    /**
     * HeaderGenerator constructor.
     * @param HtmlHeader $htmlHeader
     * @param HtmlHeader $htmlFooter
     * @param string $filename
     */
    public function __construct(
        HtmlHeader $htmlHeader = null,
        HtmlHeader $htmlFooter = null,
        string $filename = null
    )
    {
        $this->setHtmlHeader($htmlHeader);
        $this->setHtmlFooter($htmlFooter);
        $this->setFilename($filename);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $headersFilename
     */
    public function setFilename(string $headersFilename = null): void
    {
        if (!$headersFilename) {
            $headersFilename = self::DEFAULT_HEADER_FILENAME;
        }

        $this->filename = $headersFilename;
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
     * make the final file with header and footer
     * @return string
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function generateFile()
    {
        $PhpWord = new PhpWord();
        $Section = $PhpWord->addSection();

        $htmlHeader = $this->getHtmlHeader();
        $htmlFooter = $this->getHtmlFooter();

        if ($htmlHeader) {
            $this->addHeader($Section, $htmlHeader);
        }

        if ($htmlFooter) {
            $this->addFooter($Section, $htmlFooter);
        }

        $output = sprintf(
            "%s/%s",
            Settings::getWorkspace(),
            $this->getFilename()
        );
        $PhpWord->save($output);

        return $output;
    }

    /**
     * add Header to Section
     * @param Section $Section
     * @param HtmlHeader $htmlHeader
     * @return void
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function addHeader(Section $Section, HtmlHeader $htmlHeader): void
    {
        $Header = $Section->addHeader();
        Html::addHtml(
            $Header,
            $htmlHeader->getText(),
            false,
            false
        );
    }

    /**
     * add Footer to Section
     * @param Section $Section
     * @param HtmlHeader $htmlFooter
     * @return void
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function addFooter(Section $Section, HtmlHeader $htmlFooter): void
    {
        $Footer = $Section->addFooter();
        Html::addHtml(
            $Footer,
            $htmlFooter->getText(),
            false,
            false
        );
    }
}