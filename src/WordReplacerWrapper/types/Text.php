<?php


namespace Jsvptf\WordReplacerWrapper\types;


use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\TemplateProcessor;

class Text implements IType, ITypeTableChild
{
    /**
     * @var string
     */
    protected string $text;

    public function __construct(string $text)
    {
        $this->setText($text);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @inheritDoc
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key)
    {
        $value = $this->getText();
        $templateProcessor->setValue($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function setToCell(Cell &$Cell)
    {
        $text = $this->getText();
        $Cell->addText($text);
    }
}