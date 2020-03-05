<?php


namespace Jsvptf\WordReplacerWrapper\types;


use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;

class Table implements IType
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Table constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setData($data);
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
     * @throws \Exception
     */
    public function setData(array $data): void
    {
        foreach ($data as $rowKey => $row){
            foreach ($row as $cellKey => $element){
                if(!$element instanceof ITypeTableChild){
                    throw new \Exception("Invalid ITypeTableChild element {$rowKey} - {$cellKey}");
                }
            }
        }
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key)
    {
        $Table = new \PhpOffice\PhpWord\Element\Table(array('borderSize' => 12, 'borderColor' => 'green'));
        foreach ($this->getData() as $rowKey => $row){
            $Row = $Table->addRow();
            foreach ($row as $cellKey => $element){
                $Cell = $Row->addCell(1);
                $element->setToCell($Cell, $key);
            }
        }

        $templateProcessor->setComplexBlock($key, $Table);
    }
}