<?php


namespace Jsvptf\WordReplacerWrapper\types;


use Exception;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\TemplateProcessor;

class Table implements IType, ITypeTableChild
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Table constructor.
     * @param array $data
     * @throws Exception
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
     * @throws Exception
     */
    public function setData(array $data): void
    {
        foreach ($data as $rowKey => $row){
            foreach ($row as $cellKey => $element){
                if(!$element instanceof ITypeTableChild){
                    throw new Exception("Invalid ITypeTableChild element {$rowKey} - {$cellKey}");
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
        $Table = new \PhpOffice\PhpWord\Element\Table();
        $this->generateTable($Table);

        $templateProcessor->setComplexBlock($key, $Table);
    }

    /**
     * @inheritDoc
     */
    public function setToCell(Cell &$Cell)
    {
        $Table = $Cell->addTable();
        $this->generateTable($Table);
    }

    /**
     * ${CARET}
     * @param \PhpOffice\PhpWord\Element\Table $Table
     * @return void
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    protected function generateTable(\PhpOffice\PhpWord\Element\Table $Table): void
    {
        foreach ($this->getData() as $rowKey => $row) {
            $Row = $Table->addRow();
            foreach ($row as $cellKey => $element) {
                $Cell = $Row->addCell(1);
                $element->setToCell($Cell);
            }
        }
    }
}