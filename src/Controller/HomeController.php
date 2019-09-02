<?php

namespace App\Controller;

use App\Exception\InvalidVisitSessionException;
use App\Form\VisitCustomerType;
use App\Form\VisitTicketsType;
use App\Form\VisitType;
use App\Manager\VisitManager;
use Exception;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AbstractController
{
    /**
     * page 1 presentation
     *
     * @Route("/", name="home")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render ('home/index.html.twig');
    }

    /**
     * page 2 choix des billets (entité Ticket)
     *
     * @Route("/billets", name="tickets", methods={"GET" , "POST"})
     * @param Request $request
     * @param VisitManager $visitManager
     * @return Response
     */
    public function orderAction(Request $request, VisitManager $visitManager): Response
    {
        $visit = $visitManager->initVisit ();

        $form = $this->createForm (VisitType::class, $visit);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ())
        {
            $visitManager->generateEmptyTickets($visit);
            return $this->redirectToRoute ('visitors');
        }
        return $this->render ('ticket/tickets.html.twig', array('form' => $form->createView ()));
    }


    /**
     * page 3 identification des visiteurs (entité Ticket)
     *
     * @Route("/identification", name="visitors", methods={"GET" , "POST"})
     * @param Request $request
     * @param VisitManager $visitManager
     * @return Response
     * @throws Exception
     */
    public function identifyAction(Request $request,  VisitManager $visitManager): Response
    {
        $visit = $visitManager->getCurrentVisit ();

        $form = $this->createForm (VisitTicketsType::class, $visit);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {

            $visitManager->computePrice ($visit);

            $this->addFlash ('success', 'Les informations sur les visiteurs ont bien été enregistrées');

            return $this->redirectToRoute ('billing_details');
        }

        return $this->render (('customer/visitors_details.html.twig'), [
            'form' => $form->createView (),
        ]);
    }

    /** page 4 coordonnées de l'acheteur (entité Customer)
     * @param Request $request
     * @param VisitManager $visitManager
     * @return Response
     * @throws InvalidVisitSessionException
     * @Route("/customer", name="billing_details", methods={"GET" , "POST"})
     */
    public function customerAction(Request $request, VisitManager $visitManager): Response
    {
        $visit = $visitManager->getCurrentVisit();

        $form = $this->createForm (VisitCustomerType::class, $visit);
        $form->handleRequest ($request);

         if ($form->isSubmitted () && $form->isValid ()) {
            return $this->redirectToRoute ('order_summary');
        }

        return $this->render (('customer/billing_details.html.twig'),
            [
                'form' => $form->createView ()
            ]);
    }

    /**
     * page 5 Récapitulatif de la commande
     *
     * @Route("/recapitulatif_de_la_commande", name="order_summary")
     * @param VisitManager $visitManager
     * @return Response
     * @throws InvalidVisitSessionException
     */
    public
    function summaryAction(VisitManager $visitManager): Response
    {
        $visit = $visitManager->getCurrentVisit ();

        return $this->render ('customer/order_summary.html.twig', [
            'visit' => $visit
        ]);
    }

    /**
     * page 6 paiement
     * @Route("/paiement", name="payment")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return Response
     * @throws InvalidVisitSessionException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public
    function payAction(Request $request, VisitManager $visitManager): Response
    {
        $visit = $visitManager->getCurrentVisit ();
        if ($request->getMethod () === "POST") {
            $token = $request->request->get ('stripeToken');

            $secretkey = $this->getParameter ('stripe_secret_key');

            Stripe::setApiKey ($secretkey);
            try {

                Charge::create ([
                    'amount' => $visitManager->computePrice ($visit) * 100,
                    'currency' => 'eur',
                    'source' => $token,
                    'description' => 'Réservation sur la billetterie du Musée du Louvre']);

                $visitManager->postPaymentAction ($visit);

                $this->addFlash ('notice', 'Paiement enregistré');

                return $this->redirectToRoute ('payment_confirmation');

            } catch (Card $e) {
                $this->addFlash ('warning', 'Paiement échoué');
            }
        }
        return new Response($this->renderView ('payment/payment.html.twig'));
    }
    /**
     * page 7 confirmation
     * @Route("/confirmation_du_paiement", name="payment_confirmation")
     * @param VisitManager $visitManager
     * @return Response
     * @throws InvalidVisitSessionException
     */
    public
    function confirmationAction( VisitManager $visitManager): Response
    {
        $visit = $visitManager->getCurrentVisit ();


        $visitManager->cleanCurrentVisit();


        return $this->render ('payment/payment_confirmation.html.twig', [
            'visit' => $visit
        ]);
  }

    /**
     * page 9 RGPD
     * @Route("/rgpd", name="rgpd")
     * @return Response
     */
    public function rgpdAction(): Response
    {
        return $this->render ('information/rgpd.html.twig');
    }
}
