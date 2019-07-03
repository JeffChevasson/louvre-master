<?php


namespace App\Services;

use App\Entity\Visit;
use App\Form\ContactType;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Bundle\MonologBundle\SwiftMailer;

class EmailService
{
    //Ce service prend trois arguments : l'envoi de mail, le template et l'adresse d'expédition
    //Ces trois arguments sont repsi dans le fichier Services.yml
    //Dans services.yml, j'indique auelle est l'adresse mail pour la variable $emailfrom

    protected $mailer;
    protected $templating;
    private $emailfrom;

    private $translator;

    public function __construct(SwiftMailer\MessageFactory $mailer, Environment $templating, $emailfrom)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;
        $this->translator = $translator;
    }

    /**
     * @param $visit
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailConfirmation($visit)
    {
        $email = $visit->getCustomer()->getEmail();

        $message = (new \Swift_Message())
            ->setContentType('text/html')
            ->setSubject('votre commande')
            ->setFrom($this->emailfrom)
            ->setTo($email);
        $message->setBody($this->templating->render('contact/email/registration.html.twig', [
            'visit' => $visit
            ]));
        return $this->mailer->send($message);
    }


}