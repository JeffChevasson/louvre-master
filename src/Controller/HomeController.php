<?php
namespace App\Controller;

    use App\Entity\Customer;
    use App\Entity\Ticket;
    use App\Entity\Visit;
    use App\Form\ContactType;
    use App\Form\CustomerType;
    use App\Form\VisitTicketsType;
    use App\Form\VisitType;
    use App\Services\PriceCalculator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Twig\Environment;



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

            return new Response($this->twig->render('home/index.html.twig'));


        }

        /**
         * page 2 choix des billets
         *
         * @Route("/billets", name="tickets", methods={"GET" , "POST"})
         * @param Request $request
         * @return Response
         * @throws \Exception
         */
        public function orderAction(Request $request)
        {

            $visit = $request->getSession()->get('visit');
            //
        }


        /**
         * page 3 identification des visiteurs (entité Identify)
         *
         * @Route("/identification", name="visitors", methods={"GET" , "POST"})
         * @param \App\Controller\Request $request
         * @param SessionInterface $session
         * @param PriceCalculator $calculator
         * @return Response
         */
        public function identifyAction(Request $request, SessionInterface $session, PriceCalculator $calculator): Response
        {
            //On crée un nouvel objet Visit
            $visit = $session->get('visit');

            //On appelle le formulaire TicketType

            $form = $this->createForm(VisitTicketsType::class, $visit);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                // TODO 2       calculer le  prix de la visit et des tickets
                $calculator->computePrice($visit);
                return $this->render('customer/order_summary.html.twig',
                    [
                        'calculator' => $calculator
                    ]);


            }

            // on est en GET. On affiche le formulaire
            return $this->render(('customer/visitors_details.html.twig'), [
                'form' => $form->createView()
            ]);

        }


        /** page 4 coordonnées de l'acheteur (entité Customer)
         * @param $request
         * @return Response
         * @Route("/customer", name="billing_details", methods={"GET" , "POST"}))
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

            if ($form->isSubmitted() && $form->isValid()) {
                //On vérifie que les données entreées sont valides
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($customer);
                    $em->flush();

                    $request->getSession()->get('billing_details');

                    //On redirige l'acheteur vers la page 5
                    return $this->redirectToRoute('order_summary');


                }
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
            return new Response($this->twig->render('customer/order_summary.html.twig'));

        }


        /**
         * page 6 paiement
         * @Route("/paiement", name="payment")
         * @throws \Twig\Error\LoaderError
         * @throws \Twig\Error\RuntimeError
         * @throws \Twig\Error\SyntaxError
         */
        public
        function payAction(): Response
        {
            return new Response($this->twig->render('payment/payment.html.twig'));
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
            return new Response($this->twig->render('payment/payment_confirmation.html.twig'));


        }


        /**
         * page 8 contact
         * @Route("/contact", name="contact")
         * @param Request $request
         * @return Response
         */
        public function contactAction(Request $request): Response
        {
            $form = $this->createForm(ContactType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

            }

        }

    }
