<?php


namespace Jsvptf\WordReplacerWrapper;


use Jsvptf\WordReplacerWrapper\types\Table;
use PhpOffice\PhpWord\PhpWord;

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
     * @var Table
     */
    protected Table $TableHeader;

    /**
     * @var Table
     */
    protected Table $TableFooter;

    /**
     * HeaderGenerator constructor.
     * @param Table $TableHeader
     * @param Table $TableFooter
     * @param string $filename
     */
    public function __construct(
        Table $TableHeader = null,
        Table $TableFooter = null,
        string $filename = null
    )
    {
        $this->setTableHeader($TableHeader);
        $this->setTableFooter($TableFooter);
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
     * make the final file with header and footer
     * @return string
     * @date 2020-03-07
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function generateFile()
    {
        $PhpWord = new PhpWord();
        $Section = $PhpWord->addSection();

        $tableHeader = $this->getTableHeader();
        $tableFooter = $this->getTableFooter();

        if ($tableHeader) {
            $Table = $Section->addHeader()->addTable();
            $tableHeader->generateTable($Table);
        }

        if ($tableFooter) {
            $Table = $Section->addFooter()->addTable();
            $tableFooter->generateTable($Table);
        }

        $output = sprintf(
            "%s/%s",
            Settings::getWorkspace(),
            $this->getFilename()
        );
        $PhpWord->save($output);

        return $output;
    }
}