<?php


namespace App\Listener;



use App\Exception\InvalidVisitSessionException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Router;

class ExceptionListener
{
    private $router;
    private $session;

    /**
     * ExceptionListener constructor.
     * @param Router $router
     * @param SessionInterface $session
     */
    public function __construct(Router $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

        public
        /**
         * @param ExceptionEvent $event
         */
        function onKernelException(ExceptionEvent $event)
        {
            $exception = $event->getException ();
            if ($exception instanceof InvalidVisitSessionException) {

                $url = $this->router->generate ('home');
                $message = ('Une erreur s\'est produite. Veuillez saisir à nouveau votre commande.');
                $this->session->getFlashBag ()->add ('danger', $message);
                $event->setResponse (new RedirectResponse($url));
            }

        }
}