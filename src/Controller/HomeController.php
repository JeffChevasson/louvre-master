<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * page 1 presentation
     *
     * @Route("/", name="homepage"]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('frontend/index.html.twig');
    }

    /**
     * page 2 choix des billets
     *
     * @Route("/order", name="billets")
     * @param Request $request
     */
    public function orderAction(Request $request)
    {
        $visit = $request->getSession()->get('visit');
        //
    }

    /**
     * page 3 identification des visiteurs
     *
     * @Route("/identification", name="identification")
     */
    public function identifyAction()
    {
        //
    }

    /**
     * page 4 coordonnées de l'acheteur
     *
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
     */
    public function payAction()
    {
        //
    }

    /**
     * page 6 confirmation
     *
     *@Route("/confirmation", name="paymentconfirmation"
     */
    public function confirmationAction()
    {
        //
    }

    /**
     * page 7 contact
     *
     * @Route("/contact", name="contact"
     */
    public function contactAction()
    {
        //
    }

}
