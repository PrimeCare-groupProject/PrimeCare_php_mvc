<?php

require_once 'HtmlParser.php';
require_once 'CssParser.php';
require_once 'PdfRenderer.php';

class HtmlToPdf
{
    private $html;
    private $css;
    private $outputFile;

    public function __construct($html = '', $css = '', $outputFile = 'output/output.pdf')
    {
        $this->html = $html;
        $this->css = $css;
        $this->outputFile = $outputFile;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function setCss($css)
    {
        $this->css = $css;
    }

    public function setOutputFile($outputFile)
    {
        $this->outputFile = $outputFile;
    }

    public function generatePdf()
    {
        // Parse HTML
        $htmlParser = new HtmlParser($this->html);
        $parsedHtml = $htmlParser->parse();

        // Parse CSS
        $cssParser = new CssParser($this->css);
        $parsedCss = $cssParser->parse();

        // Render PDF
        $pdfRenderer = new PdfRenderer($parsedHtml, $parsedCss);
        $pdfRenderer->render($this->outputFile);

        echo "PDF generated successfully: " . $this->outputFile . "\n";
    }
}