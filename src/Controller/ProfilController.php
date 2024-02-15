<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, SessionInterface $session): Response
    {
        // Create the form using the YourFormType class
        $form = $this->createForm(ProfilFormType::class);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form data (you can customize this part)
            //$formData = $form->getData();

            // For now, just dump the form data
           // dump($formData);
           $formData = $form->getData();
           $session->set('form2_data', $formData);


            // You can redirect or return a response as needed
            
          // return $this->redirectToRoute('pdf_generation_page');
          return $this->redirectToRoute('pdf_review');
        }

        // Render the template with the form
        return $this->render('profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
