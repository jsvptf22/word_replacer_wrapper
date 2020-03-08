<?php


namespace Jsvptf\WordReplacerWrapper\types;


use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table as TableAlias;
use PhpOffice\PhpWord\TemplateProcessor;

class Pagination extends Text
{
    /**
     * @inheritDoc
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key)
    {
        $Table = new \PhpOffice\PhpWord\Element\Table([
            'layout' => TableAlias::LAYOUT_FIXED,
            'width' => TblWidth::AUTO,
            'borderSize' => 0,
            'borderColor' => 'ffffff'
        ]);
        $Table->addRow();
        $Cell = $Table->addCell(1);
        $this->setToCell($Cell);

        $templateProcessor->setComplexValue($key, $Table);
    }

    /**
     * @inheritDoc
     */
    public function setToCell(Cell &$Cell)
    {
        $text = $this->getText();
        $Cell->addPreserveText($text);
    }
}