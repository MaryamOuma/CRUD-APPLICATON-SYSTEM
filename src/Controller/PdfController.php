<?php
namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PdfController extends AbstractController
{
    #[Route('/review', name: 'pdf_review')]
    public function reviewPdf(SessionInterface $session): Response
    {
        // Retrieve form data from the session
        $dataFromForm1 = $session->get('form1_data', []);
        $dataFromForm2 = $session->get('form2_data', []);

        // Render the template with form data
        return $this->render('review_and_generate_pdf.html.twig', [
            'dataFromForm1' => $dataFromForm1,
            'dataFromForm2' => $dataFromForm2,
        ]);
    }
    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(SessionInterface $session)
    {
          // Retrieve data from the session
          $dataFromForm1 = $session->get('form1_data');
          $dataFromForm2 = $session->get('form2_data');
  
          // Render the PDF template
          $htmlContent = $this->renderView('pdf_template.html.twig', [
              'dataFromForm1' => $dataFromForm1,
              'dataFromForm2' => $dataFromForm2,
          ]);
  
          // Create Dompdf options
          $options = new Options();
          $options->set('isHtml5ParserEnabled', true);
          $options->set('isPhpEnabled', true);
  
          // Instantiate Dompdf with options
          $dompdf = new Dompdf($options);
  
          // Load HTML content into Dompdf
          $dompdf->loadHtml($htmlContent);
  
          // Set paper size (e.g., A4)
          $dompdf->setPaper('A4', 'portrait');
  
          // Render PDF
          $dompdf->render();
  
          // Get the output
          $output = $dompdf->output();
  
          // Create a response and set headers for download
          $response = new Response($output);
          $response->headers->set('Content-Type', 'application/pdf');
          $response->headers->set('Content-Disposition', 'attachment;filename=attestation_pdf.pdf');
          $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
  
          return $response;
    }
}
