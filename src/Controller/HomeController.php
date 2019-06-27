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
     * page 2 choix des billets
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
     * page 3 identification des visiteurs
     *
     * @Route("/identification", name="identification")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function identifyAction()
    {
        return new Response($this->twig->render('frontend/tickets.html.twig'));
    }

    /**
     * page 4 coordonnées de l'acheteur
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/customer", name="customer")
     */
    public function customerAction()
    {
        //
    }

    /**
     * page 5 paiement
     *
     * @Route("/pay", name="payment")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function payAction()
    {
        //
    }

    /**
     * page 6 confirmation
     *
     *@Route("/confirmation", name="paymentconfirmation")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function confirmationAction()
    {
        //
    }

    /**
     * page 7 contact
     *
     * @Route("/contact", name="contact")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function contactAction()
    {
        //
    }

}
