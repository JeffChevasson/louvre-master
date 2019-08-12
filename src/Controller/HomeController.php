<?php
namespace App\Controller;


use App\Entity\Customer;
use App\Entity\Ticket;
use App\Entity\Visit;
use App\Form\ContactType;
use App\Form\CustomerType;
use App\Form\VisitTicketsType;
use App\Form\VisitType;
use App\Services\EmailService;
use App\Services\PriceCalculator;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Controller\ContactController;





class HomeController extends AbstractController
{
    /**
     * page 1 presentation
     *
     * @Route("/", name="home")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction()
    {
        return $this->render ('home/index.html.twig');


    }

    /**
     * page 2 choix des billets
     *
     * @Route("/billets", name="tickets", methods={"GET" , "POST"})
     * @param Request $request
     * @return Response
     */
    public function orderAction(Request $request): Response
    {


        //A partir du formulaire on le génère
        $form = $this->createForm (VisitType::class);
        $form->handleRequest ($request);
        //si la requête est en POST

        if ($form->isSubmitted () && $form->isValid ()) {
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
     * page 3 identification des visiteurs (entité Identify)
     *
     * @Route("/identification", name="visitors", methods={"GET" , "POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @param PriceCalculator $calculator
     * @param Ticket $ticket
     * @return Response
     */
    public function identifyAction(Request $request, SessionInterface $session, PriceCalculator $calculator): Response
    {
        //On crée un nouvel objet Visit
        $visit = $session->get ('visit');

        //On appelle le formulaire VisitTicketType

        $form = $this->createForm (VisitTicketsType::class, $visit);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {

            $calculator->computePrice ($visit);

            //On redirige l'acheteur vers la page 4
            return $this->redirectToRoute ('billing_details');
        }

        // on est en GET. On affiche le formulaire
        return $this->render (('customer/visitors_details.html.twig'), [
            'form' => $form->createView (),
        ]);

    }


    /** page 4 coordonnées de l'acheteur (entité Customer)
     * @param $request
     * @return Response
     * @Route("/customer", name="billing_details", methods={"GET" , "POST"})
     */
    public function customerAction(SessionInterface $session, Request $request): Response
    {
        //On crée un nouvel objet Customer
        $customer = new Customer();
        $visit = $session->get ('visit');
        $visit->setCustomer ($customer);

        //On appelle le formulaire CustomerType

        //A partir du formulaire on le génère
        $form = $this->createForm (CustomerType::class, $customer);
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
        /**return new Response($this->twig->render('frontend/customer.html.twig'));*/
    }


    /**
     * page 5 Récapitulatif de la commande
     *
     * @Route("/recapitulatif_de_la_commande", name="order_summary")
     * @param SessionInterface $session
     * @return Response
     */
    public
    function summaryAction(SessionInterface $session): Response
    {

        //On crée un nouvel objet Visit
        $visit = $session->get ('visit');

        //On redirige l'acheteur vers la page 6
        /** return $this->redirectToRoute ('payment'); */

        // on est en GET. On affiche le formulaire
        return $this->render ('customer/order_summary.html.twig', [
            'visit' => $visit
        ]);

    }


    /**
     * page 6 paiement
     * @Route("/paiement", name="payment")
     * @param Visit $visit
     * @param SessionInterface $session
     * @param Request $request
     * @param Stripe $token
     * @return Response
     */
    public
    function payAction(SessionInterface $session, Request $request): Response

    {
        //On crée un nouvel objet Visit
        $visit = $session->get ('visit');
        if ($request->getMethod () === "POST") {
            //Création de la charge - Stripe
            $token = $request->request->get ('stripeToken');

            //Chargement de la clé secrète de Stripe
            $secretkey = $this->getParameter ('stripe_secret_key');

            Stripe::setApiKey ($secretkey);
//            try{
            $charge = Charge::create ([
                'amount' => $visit->getTotalAmount ($visit) * 100,
                'currency' => 'eur',
                'source' => $token,
                'description' => 'Réservation sur la billetterie du Musée du Louvre'
            ]);
            $visit->setBookingCode ($charge['id']);

            // enregistrement dans la base
            $em = $this->getDoctrine ()->getManager ();
            $em->persist ($visit);
            $em->flush ();
            $this->addFlash ('notice', 'Paiement enregistré');

            return $this->redirectToRoute ('payment_confirmation');

//            }catch(\Exception $e){
//
//                $this->addFlash('warning', 'Paiement échoué');
//            }

            //Redirection
            //On redirige l'acheteur vers la page 7
        }

        return new Response($this->renderView ('payment/payment.html.twig'));
    }


    /**
     * page 7 confirmation
     * @Route("/confirmation_du_paiement", name="payment_confirmation")
     * @param SessionInterface $session
     * @return Response
     */
    public
    function confirmationAction(SessionInterface $session): Response
    {

        //On crée un nouvel objet Visit
        $visit = $session->get ('visit');

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
