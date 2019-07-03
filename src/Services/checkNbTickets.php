<?php


namespace App\Services;


use App\Entity\Visit;
use App\Form\VisitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class checkNbTickets
{
    public function checkNbTickets(Request $request): Response
    {
        $visit = new Visit();
        $session = $request->getSession();
        $ticket = $session->get("ticket");
        $nombre_tickets = $ticket->getNbticket();
        $form = $this->createFormBuilder();
        for ($i = 0; $i < $nombre_tickets; $i++) {
            $form->add($i, VisitType::class, [
                'label' => "VISITEUR N°" . ($i + 1)])
                ->getForm();
        }
        $formBillet = $form->getForm();
        $formBillet->handleRequest($request);
        $form->getData();
        if ($request->isMethod('POST')) {
            if ($formBillet->isSubmitted() && $formBillet->isValid()) {
                $data = $formBillet->getData();
                for ($i = 0; $i < $nombre_tickets; $i++) {
                    $ticket->addBillet($data[$i]);
                }
                return $this->redirectToRoute('billing_details', [
                    'ticket' => $ticket
                ]);
            }
        }
        return $this->render('customer/visitors_details.html.twig', [
            'visit' => $visit,
            'title' => 'Choix du visit',
            'formBillet' => $formBillet->createView()
        ]);
    }
}