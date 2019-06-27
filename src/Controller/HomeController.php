<?php
namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class HomeController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * page 1 Accueil
     *
     * @Route("/", name="home", methods={"GET"})
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(): Response
    {

            return new Response($this->twig->render('frontend/index.html.twig'));

    }

    /**
     * page 2 choix des billets (entité Visit)
     *
     * @Route("/billets", name="billets", methods={"GET"})
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     *
     */
    public function orderAction(): Response
    {

        return new Response($this->twig->render('frontend/tickets.html.twig'));
    }

    /**
     * page 3 identification des visiteurs (entité Identify)
     *
     * @Route("/identification", name="identification")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function identifyAction(): Response
    {
        return new Response($this->twig->render('frontend/identify.html.twig'));
    }

    /**
     * page 4 coordonnées de l'acheteur (entité Customer)
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/customer", name="customer")
     */
    public function customerAction(): Response
    {
        return new Response($this->twig->render('frontend/customer.html.twig'));
    }

    /**
     * page 5 paiement
     *
     * @Route("/pay", name="payment")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function payAction(): Response
    {
        return new Response($this->twig->render('frontend/payment.html.twig'));
    }

    /**
     * page 6 confirmation
     *
     *@Route("/confirmation", name="paymentconfirmation")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function confirmationAction(): Response
    {
        return new Response($this->twig->render('frontend/paymentconfirmation.html.twig'));
    }

    /**
     * page 7 contact
     *
     * @Route("/contact", name="contact")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function contactAction(): Response
    {
        return new Response($this->twig->render('frontend/contact.html.twig'));
    }

}
