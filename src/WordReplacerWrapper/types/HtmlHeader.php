<?php


namespace Jsvptf\WordReplacerWrapper\types;


class HtmlHeader
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
}