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

    /**
     * @var array
     */
    protected array $style;

    public function __construct(string $text, array $style = [])
    {
        $this->setText($text);
        $this->setStyle($style);
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
     * @return array
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    /**
     * @param array $style
     */
    public function setStyle(array $style): void
    {
        $this->style = $style;
    }

    /**
     * @inheritDoc
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key)
    {
        $value = $this->getText();
        $style = $this->getStyle();

        if($style){
            $Text = new \PhpOffice\PhpWord\Element\Text($value, $style);
            $templateProcessor->setComplexValue($key, $Text);
        }else{
            $templateProcessor->setValue($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function setToCell(Cell &$Cell)
    {
        $text = $this->getText();
        $Cell->addText($text, $this->getStyle());
    }
}