<?php


namespace App\Services;

use App\Entity\Visit;
use App\Form\ContactType;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use Symfony\Bundle\MonologBundle\SwiftMailer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailService
{
    //Ce service prend trois arguments : l'envoi de mail, le template et l'adresse d'expédition
    //Ces trois arguments sont repris dans le fichier Services.yml
    //Dans services.yml, j'indique quelle est l'adresse mail pour la variable $emailfrom

    protected $mailer;
    protected $templating;
    private $emailfrom;

    public function __construct(Swift_Mailer $mailer, Environment $templating, $emailfrom)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;

    }

    /**
     * @param $visit
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendMailConfirmation(Visit $visit)
    {
        $email = $visit->getCustomer()->getEmail();

        $message = (new Swift_Message())
            ->setContentType('text/html')
            ->setSubject('votre commande')
            ->setFrom($this->emailfrom)
            ->setTo($email);

        $cid = $message->embed(\Swift_Image::fromPath ('img/logo_musee_du_louvre.jpg'));

        $message->setBody ($this->templating->render('payment/payment_confirmation_mail.html.twig', [
            'visit' => $visit,
            'cid' => $cid
            ]));
        return $this->mailer->send($message);
    }

    /**
     * @param $data
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendMailContact($data)
    {
        $message = (new Swift_Message())
            ->setFrom($data['email'])
            ->setTo($this->emailfrom)
            ->setBody($this->templating->render('contact/contact_mail.html.twig', [
                'data' => $data
            ]))
            ->setContentType('text/html');

        $this->mailer->send($message);
    }


}
