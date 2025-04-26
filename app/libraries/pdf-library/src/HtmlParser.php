<?php

class HtmlParser
{
    private $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function parse()
    {
        // Basic parsing logic (strip tags, split into lines, etc.)
        $lines = explode("\n", $this->html);
        $parsedHtml = [];

        foreach ($lines as $line) {
            $parsedHtml[] = strip_tags($line, '<b><i><u><p><h1><h2><h3>');
        }

        return $parsedHtml;
    }
}