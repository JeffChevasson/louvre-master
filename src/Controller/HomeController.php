<?php
namespace App\Controller;


    use App\Entity\Customer;
    use App\Entity\Ticket;
    use App\Entity\Visit;
    use App\Form\ContactType;
    use App\Form\CustomerType;
    use App\Form\TicketType;
    use App\Form\VisitTicketsType;
    use App\Form\VisitType;
    use App\Services\checkNbTickets;
    use App\Services\EmailService;
    use App\Services\PriceCalculator;
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
         * @throws \Twig\Error\LoaderError
         * @throws \Twig\Error\RuntimeError
         * @throws \Twig\Error\SyntaxError
         */
        public function indexAction()
        {
            return $this->render('home/index.html.twig');


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
            $form = $this->createForm(VisitType::class);
            $form->handleRequest($request);
            //si la requête est en POST

            if ($form->isSubmitted() && $form->isValid())
            {
                $visit = $form->getData();

                for ($i = 1; $i <= $visit->getNbticket() ;$i++ ){

                    $visit->addTicket(new Ticket());
                }
                $request->getSession()->set('visit', $visit);

                //On redirige l'acheteur vers la page 5
                return $this->redirectToRoute('visitors');

            }

            //On est en GET. On affiche le formulaire
            return $this->render('ticket/tickets.html.twig', array('form'=>$form->createView()));
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
            $visit = $session->get('visit');

            //On appelle le formulaire VisitTicketType

            $form = $this->createForm(VisitTicketsType::class, $visit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
             //$ticket = $form->getData ();
             // TODO 2       calculer le  prix de la visit et des tickets

                $calculator->computePrice($visit);
                        }

            // on est en GET. On affiche le formulaire
            return $this->render(('customer/visitors_details.html.twig'), [
                'form' => $form->createView()
            ]);

        }


        /** page 4 coordonnées de l'acheteur (entité Customer)
         * @param $request
         * @return Response
         * @Route("/customer", name="billing_details", methods={"GET" , "POST"})
         */
        public function customerAction(Request $request): Response
        {
            //On crée un nouvel objet Customer
            $customer = new Customer();

            //On appelle le formulaire CustomerType
            $request->getSession()->set('customer', $customer);

            //A partir du formulaire on le génère
            $form = $this->createForm(CustomerType::class, $customer);
            $form->handleRequest($request);
            //si la requête est en POST

            if ($form->isSubmitted() && $form->isValid())
            {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($customer);
                    $em->flush();

                    $request->getSession()->get('billing_details');

                    //On redirige l'acheteur vers la page 5
                    return $this->redirectToRoute('order_summary');



            }
            //Si on est en GET. On affiche le formulaire
            return $this->render(('customer/billing_details.html.twig'),
                [
                    'form' => $form->createView()
                ]);
            /**return new Response($this->twig->render('frontend/customer.html.twig'));*/
        }


        /**
         * page 5 Récapitulatif de la commande
         *
         * @Route("/recapitulatif_de_la_commande", name="order_summary")
         * @throws \Twig\Error\LoaderError
         * @throws \Twig\Error\RuntimeError
         * @throws \Twig\Error\SyntaxError
         */
        public
        function summaryAction(): Response
        {
            return new Response($this->render('customer/order_summary.html.twig'));

        }


        /**
         * page 6 paiement
         * @Route("/paiement", name="payment")
         * @param Request $request
         * @param Visit $visit
         * @return Response
         */
        public
        function payAction(): Response

      {
          /**
            //On crée un nouvel objet Visit
            $visit = $session->get('visit');

            if($request->getMethod () === "POST")
            {
                //Création de la charge - Stripe
                $token = $request->request->get ('stripeToken');

                //Chargement de la clé secète de Stripe
                $secretkey = $this->getParameter ('stripe_secret_key');

                \Stripe\Stripe::setApiKey('sk_test_Blup8XKxZ7cppLvxLgpzXbX000WHYCC6rQ');

                $charge = \Stripe\Charge::create([
                    'amount' => 999,
                    'currency' => 'usd',
                    'source' => 'tok_visa',
                    'receipt_email' => 'jenny.rosen@example.com',
                ]);
            }
        * */


            return new Response($this->render('payment/payment.html.twig'));
        }


        /**
         * page 7 confirmation
         * @Route("/confirmation_du_paiement", name="payment_confirmation")
         * @throws \Twig\Error\LoaderError
         * @throws \Twig\Error\RuntimeError
         * @throws \Twig\Error\SyntaxError
         */
        public
        function confirmationAction(): Response
        {
            return new Response($this->render('payment/payment_confirmation.html.twig'));


        }


        /**
         * page 8 contact
         * @Route("/contact", name="contact")
         * @param Request $request
         * @param EmailService $emailService
         * @return Response
         */
        public function contactAction(Request $request, EmailService $emailService): Response
        {
            $form = $this->createForm(ContactType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                try {
                    $emailService->sendMailContact($form->getData());
                } catch (LoaderError $e) {
                } catch (RuntimeError $e) {
                } catch (SyntaxError $e) {
                }

                $this->addFlash('notice', 'message.conact.send');
                return $this->redirectToRoute('home');
            }

}