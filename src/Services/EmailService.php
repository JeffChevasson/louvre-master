<?php


namespace App\Services;

use App\Entity\Visit;
use App\Form\ContactType;
use Twig\Environment;
use Symfony\Bundle\MonologBundle\SwiftMailer;

class EmailService
{
    //Ce service prend trois arguments : l'envoi de mail, le template et l'adresse d'expédition
    //Ces trois arguments sont repris dans le fichier Services.yml
    //Dans services.yml, j'indique quelle est l'adresse mail pour la variable $emailfrom

    protected $mailer;
    protected $templating;
    private $emailfrom;



    public function __construct(\Swift_Mailer $mailer, Environment $templating, $emailfrom)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;

    }

    /**
     * @param $visit
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailConfirmation(Visit $visit)
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

    /**
     * @param $data
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailContact($data)
    {
        $message = (new \Swift_Message())
                    ->setFrom($data['email'])
            ->setTo($this->emailfrom)
            ->setBody($this->templating->render('email/contact.html.twig', [
                'data' => $data
            ]))
            ->setContentType('text/html');

        $this->mailer->send($message);
    }


}
