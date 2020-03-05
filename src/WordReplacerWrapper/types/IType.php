<?php


namespace Jsvptf\WordReplacerWrapper\types;


use PhpOffice\PhpWord\TemplateProcessor;

interface IType
{
    /**
     * add element data to TemplateProcessor
     * @param TemplateProcessor $templateProcessor
     * @param string $key
     * @return mixed
     * @date 2020-03-05
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key);
}