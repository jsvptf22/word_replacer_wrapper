<?php


namespace Jsvptf\WordReplacerWrapper\types;


use PhpOffice\PhpWord\Element\Cell;

interface ITypeTableChild
{
    /**
     * add data to table cell
     * @param Cell $Cell
     * @return mixed
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function setToCell(Cell &$Cell);
}