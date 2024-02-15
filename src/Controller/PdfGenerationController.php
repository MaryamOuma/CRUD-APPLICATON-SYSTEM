<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PdfGenerationController extends AbstractController
{
    #[Route('/pdf-generation', name: 'pdf_generation_page')]
    public function pdfGenerationPage(SessionInterface $session): Response
    {
        // Retrieve data from the session
        $dataFromForm1 = $session->get('form1_data');
        $dataFromForm2 = $session->get('form2_data');

        // Create PDF
        $dompdf = new Dompdf(new Options());
        $dompdf->loadHtml($this->renderView('pdf_template.html.twig', [
            'dataFromForm1' => $dataFromForm1,
            'dataFromForm2' => $dataFromForm2,
        ]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="generated_pdf.pdf"',
            ]
        );
    }
}
