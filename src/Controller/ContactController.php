<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Services\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ContactController extends AbstractController
{
    /**
     * page 8 contact
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param EmailService $emailService
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function contactAction(Request $request, EmailService $emailService)
    {
        $form = $this->createForm (ContactType::class);
        $form->handleRequest ($request);
        if ($form->isSubmitted () && $form->isValid ()) {
            $emailService->sendMailContact ($form->getData ());
            $this->addFlash ('notice', 'Votre message a bien été envoyé');
            return $this->redirect ($this->generateUrl ('home'));
        }
        return $this->render ('contact/contact.html.twig', array('form' => $form->createView ()));
    }
}
