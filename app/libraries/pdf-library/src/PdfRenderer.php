<?php

class PdfRenderer
{
    private $html;
    private $css;

    public function __construct($html, $css)
    {
        $this->html = $html;
        $this->css = $css;
    }

    public function render($outputFile)
    {
        // Simulate PDF rendering
        $pdfContent = "PDF Content:\n\n";

        foreach ($this->html as $line) {
            $pdfContent .= $line . "\n";
        }

        $pdfContent .= "\nCSS Rules:\n";
        foreach ($this->css as $property => $value) {
            $pdfContent .= "$property: $value\n";
        }

        // Save to file
        file_put_contents($outputFile, $pdfContent);
    }
}