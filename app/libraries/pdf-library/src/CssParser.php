<?php

class CssParser
{
    private $css;

    public function __construct($css)
    {
        $this->css = $css;
    }

    public function parse()
    {
        // Basic CSS parsing logic
        $rules = explode(";", $this->css);
        $parsedCss = [];

        foreach ($rules as $rule) {
            $parts = explode(":", $rule);
            if (count($parts) == 2) {
                $property = trim($parts[0]);
                $value = trim($parts[1]);
                $parsedCss[$property] = $value;
            }
        }

        return $parsedCss;
    }
}