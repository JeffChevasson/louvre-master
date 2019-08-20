<?php

namespace App\Controller;


use App\Entity\Customer;
use App\Entity\Ticket;
use App\Exception\InvalidVisitSessionException;
use App\Form\CustomerType;
use App\Form\VisitCustomerType;
use App\Form\VisitTicketsType;
use App\Form\VisitType;
use App\Manager\VisitManager;
use App\Services\PublicHolidaysService;
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
     * @param PublicHolidaysService $publicHolidaysService
     * @return Response
     */
    public function orderAction(Request $request, VisitManager $visitManager, PublicHolidaysService $publicHolidaysService): Response
    {
        $visit = $visitManager->initVisit ();

        $publicHolidays = $publicHolidaysService->getPublicHolidaysOfThisYear ();

        //A partir du formulaire on le génère
        $form = $this->createForm (VisitType::class, $visit);
        $form->handleRequest ($request);

        //si la requête est en POST
        if ($form->isSubmitted () && $form->isValid ()) //on compte le nombre de tickets
        {
            $visit = $form->getData ();
            for ($i = 1; $i <= $visit->getNbticket (); $i++) {
                $visit->addTicket (new Ticket());
            }

            $request->getSession ()->set ('visit', $visit);

            //On redirige l'acheteur vers la page 3
            return $this->redirectToRoute ('visitors');
        }

        //On est en GET. On affiche le formulaire
        return $this->render ('ticket/tickets.html.twig', array('form' => $form->createView ()));
    }


    /**
     * page 3 identification des visiteurs (entité Ticket)
     *
     * @Route("/identification", name="visitors", methods={"GET" , "POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @param VisitManager $visitManager
     * @return Response
     * @throws Exception
     */
    public function identifyAction(Request $request, SessionInterface $session, VisitManager $visitManager): Response
    {
        //On appelle objet Visit
        $visit = $visitManager->getCurrentVisit ();

        //On appelle le formulaire VisitTicketType
        $form = $this->createForm (VisitTicketsType::class, $visit);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {

            //on calcul ici le montant total de la visite
            $visitManager->computePrice ($visit);

            // on affiche un message flash
            $this->addFlash ('success', 'votre choix a bien été enregistré 1');

            //On redirige l'acheteur vers la page 4
            return $this->redirectToRoute ('billing_details');
        }

        // on est en GET. On affiche le formulaire
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

        //On crée un nouvel objet Customer
        //$customer = new Customer();
        //On appelle l'objet Visit
        $visit = $visitManager->getCurrentVisit();
        //$visit->setCustomer ($customer);

        //A partir du formulaire on le génère
        $form = $this->createForm (VisitCustomerType::class, $visit);
        $form->handleRequest ($request);

        //si la requête est en POST
        if ($form->isSubmitted () && $form->isValid ()) {
            //On redirige l'acheteur vers la page 5
            return $this->redirectToRoute ('order_summary');
        }

        //Si on est en GET. On affiche le formulaire
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
        //On appelle l'objet Visit
        $visit = $visitManager->getCurrentVisit ();

        // on est en GET. On affiche le formulaire
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
        //On appelle l'objet Visit
        $visit = $visitManager->getCurrentVisit ();
        if ($request->getMethod () === "POST") {
            //Création de la charge - Stripe
            $token = $request->request->get ('stripeToken');

            //Chargement de la clé secrète de Stripe
            $secretkey = $this->getParameter ('stripe_secret_key');

            Stripe::setApiKey ($secretkey);
            try {

                $charge = Charge::create (array(
                    'amount' => $visitManager->computePrice ($visit) * 100,
                    'currency' => 'eur',
                    'source' => $token,
                    'description' => 'Réservation sur la billetterie du Musée du Louvre'
                ));

                // Création du booking code
                $visitManager->generateBookingCodeWithEmail ($visit);

                // enregistrement dans la base
                $em = $this->getDoctrine ()->getManager ();
                $em->persist ($visit);
                $em->flush ();
                $this->addFlash ('notice', 'Paiement enregistré');

                return $this->redirectToRoute ('payment_confirmation');

            } catch (Card $e) {
                $this->addFlash ('warning', 'Paiement échoué');
            }

            //On redirige l'acheteur vers la page 7
        }
        return new Response($this->renderView ('payment/payment.html.twig'));
    }

    /**
     * page 7 confirmation
     * @Route("/confirmation_du_paiement", name="payment_confirmation")
     * @param SessionInterface $session
     * @param VisitManager $visitManager
     * @return Response
     * @throws InvalidVisitSessionException
     */
    public
    function confirmationAction(SessionInterface $session, VisitManager $visitManager): Response
    {
        //On appelle l'objet Visit
        $visit = $visitManager->getCurrentVisit ();

        $this->addFlash ('notice', 'Paiement enregistré');

        // on est en GET. On affiche le formulaire
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
