<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
//utiliser la doc sur https://github.com/dompdf/dompdf
class PdfService
{
    private $domPdf;


    public function __construct()
    {
        $this->domPdf = new DomPdf();
        //options
        $pdfOptions = new Options();
        $pdfOptions->set("defaultFont", "Garamond");
        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        $this->domPdf->load_html($html);
        $this->domPdf->render();
        $this->domPdf->stream("article.pdf", [
            "Attachement" => false
        ]);
    }

    public function generatePdf($html)
    {
        $this->domPdf->load_html($html);
        $this->domPdf->render();
        $this->domPdf->output();
    }
}
