<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CertificateRepository;
use App\Entity\Certificate;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;



class DefaultController extends AbstractController
{
    
    #[Route('/', name: 'default')]
    public function index(CertificateRepository $certificateRepository): Response
    {
        // Fetch data from the database (you might need to adjust this based on your entity/repository)
      
    $certificates = $certificateRepository->findAll();
        return $this->render('base.html.twig', [
          'certificates' => $certificates,
        ]);
         
    }
    
        #[Route('/add', name: 'add', methods: ['POST'])]
        public function add(Request $request,EntityManagerInterface $entityManager): Response
        {
            // Retrieve data from the form
            $firstName = $request->request->get('FirstName');
            $lastName = $request->request->get('LastName');
            $debutDate = $request->request->get('DebutDate');
            $endDate = $request->request->get('EndDate');
            $profil = $request->request->get('Profil');
           
            // Create an instance of your entity and set values
            $certificate = new Certificate();
            $certificate->setFirstName($firstName);
            $certificate->setLastName($lastName);
            $certificate->setDebutDate(new \DateTime($debutDate));
            $certificate->setEndDate(new \DateTime($endDate));
            $certificate->setPosition($profil);
           // dd($certificate);
        

          $entityManager->persist($certificate);
           $entityManager->flush();
          
        
            // Redirect to the page where you want to display the updated table
            return $this->redirectToRoute('default');
        }
        #[Route('/delete', name: 'delete_certificate')]
        public function delete(Request $request,EntityManagerInterface $entityManager,CertificateRepository $certificateRepository): Response
        {
               // Get the ID from the request
       $id = $request->query->get('id');
     

    // Retrieve the certificate entity by ID
    $certificate = $certificateRepository->find($id);

    // Check if the certificate exists
    if (!$certificate) {
        // Handle case where the certificate with the given ID is not found
        throw $this->createNotFoundException('Certificate not found');
    }

    // Remove the certificate from the database
    
    $entityManager->remove($certificate);
    $entityManager->flush();

    $this->addFlash('success', 'Record successfully deleted!');

    // Redirect or return a response as needed
    return $this->redirectToRoute('default');
             
        }
        #[Route('/pdf', name: 'generate_attestation', methods: ['GET'])]
        public function pdf(Request $request, CertificateRepository $certificateRepository): Response
        {
            $certificateId = $request->query->get('certificateId');
            //dd( $certificateId);
        
            // Retrieve the certificate entity by ID
            $certificate = $certificateRepository->find($certificateId);
        
            // Check if the certificate exists
            if (!$certificate) {
                // Handle case where the certificate with the given ID is not found
                throw $this->createNotFoundException('Certificate not found');
            }
        
            // Create a Dompdf instance
            $dompdf = new Dompdf();
        
            // Load HTML content from the template
            $htmlContent = $this->renderView('certificate.html.twig', ['certificate' => $certificate]);
        
            // Load HTML to Dompdf
            $dompdf->loadHtml($htmlContent);
        
            // Set paper size (optional)
            $dompdf->setPaper('A4', 'portrait');
        
            // Render PDF (first step)
            $dompdf->render();
        
            // Stream the file
            $response = new Response($dompdf->output());
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="certificate.pdf"');
        
            return $response;
        }
}
