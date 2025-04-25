<?php

require_once '../src/HtmlToPdf.php';

$html = "
<h1>Welcome to HtmlToPdf</h1>
<p>This is a simple paragraph.</p>
<b>Bold Text</b>
<i>Italic Text</i>
<u>Underlined Text</u>
";

$css = "
h1 { font-size: 20px; color: red; }
p { font-size: 14px; color: blue; }
";

$pdfGenerator = new HtmlToPdf($html, $css, '../output/example1.pdf');
$pdfGenerator->generatePdf();