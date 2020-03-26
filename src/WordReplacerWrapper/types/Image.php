<?php


namespace Jsvptf\WordReplacerWrapper\types;


use Jsvptf\WordReplacerWrapper\DataProcessor;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\TemplateProcessor;

class Image implements IType, ITypeTableChild
{
    /**
     * @var string
     */
    protected string $route;
    /**
     * @var int
     */
    private int $width;

    /**
     * @var int
     */
    private int $height;

    /**
     * Image constructor.
     * @param string $route
     * @param int $width
     * @param int $height
     */
    public function __construct(string $route, int $width, int $height)
    {
        $this->setRoute($route);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @inheritDoc
     */
    public function setTo(TemplateProcessor &$templateProcessor, string $key)
    {
        $templateProcessor->setImageValue($key, [
            'path' => $this->getRoute(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function setToCell(Cell &$Cell)
    {
        $variable = uniqid('image');
        $Cell->addText('${' . $variable . '}');

        DataProcessor::$dynamicElements[$variable] = $this;
    }
}