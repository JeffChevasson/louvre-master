<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @param $url
     * @param $method
     * @param $statusExpected
     * @dataProvider urls
     */
    public function testUrls($url, $method, $statusExpected)
    {
        $client = static::createClient();
        $client->request($method, $url);
        $this->assertSame($statusExpected, $client->getResponse()->getStatusCode());
    }

    public function urls(){
        return [
            ['/', 'GET', 200],
            ['/testdummy', 'GET' , 404 ],
            ['/contact', 'GET' , 200],
            ['/contact', 'POST' , 200],
            ['/billets', 'GET' , 200],
            ['/identification', 'GET' , 302],
            ['/customer', 'GET' , 302],
            ['/recapitulatif_de_la_commande', 'GET' , 302],
            ['/paiement', 'GET' , 302],
            ['/confirmation_du_paiement', 'GET' , 302 ],
            ['/rgpd', 'GET', 200],
        ];
    }
    /**
     * TEST
     * Verifie si le lien "Contact" en page d'accueil
     * redirige bien vers la page Contact
     */
    public function testLinkContactOnHomepage()
    {
        $client = static::createClient ();
        $crawler = $client->request ('GET', '/');
        $link = $crawler->selectLink ("Contact")->link();
        $client->click ($link);
        $this->assertEquals ('App\Controller\ContactController::contactAction',
            $client->getRequest ()->attributes->get ('_controller'));
    }
    /**
     * TEST
     * Verifie si le lien "Achetez vos billets" en page d'accueil
     * redirige bien vers la page Billets
     */
    public function testLinkBuyTicketsOnHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink ("Achat de billets")->link();
        $client->click($link);
        $this->assertEquals('App\Controller\HomeController::orderAction',
            $client->getRequest()->attributes->get('_controller'));
    }
    /**
     * TEST
     * Test les formulaires du tunnel d'achat
     * jusqu'à la page Paiement
     */
    public function testForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/billets');

        $form = $crawler->selectButton('Valider')->form();
        $form['visit[visitedate]'] = '2019-12-12';
        $form['visit[type]'] = 1;
        $form['visit[nbTicket]'] = 1;
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals('App\Controller\HomeController::identifyAction',
            $client->getRequest()->attributes->get('_controller'));

        // visitors_details.html.twig
        $form = $crawler->selectButton('Valider')->form();
        $values = $form->getPhpValues();
        $values['visit_tickets']['tickets'][0]['lastname'] = "Doe";
        $values['visit_tickets']['tickets'][0]['firstname'] = "John";
        $values['visit_tickets']['tickets'][0]['country'] = "FR";
        $values['visit_tickets']['tickets'][0]['birthdate']['day'] = 26;
        $values['visit_tickets']['tickets'][0]['birthdate']['month'] = 2;
        $values['visit_tickets']['tickets'][0]['birthdate']['year'] = 1975;
        $values['visit_tickets']['tickets'][0]['discount'] = 0;

        $client->request('POST', $form->getUri(), $values,
            $form->getPhpFiles());

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        // billing_details.html.twig
        $form = $crawler->selectButton('Suivant')->form();
        $values = $form->getPhpValues();
        $values['app_bundle_visit_customer_type']['customer']['lastname'] = "Doe";
        $values['app_bundle_visit_customer_type']['customer']['firstname'] = "John";
        $values['app_bundle_visit_customer_type']['customer']['email'] = "john.doe@gmail.com";
        $values['app_bundle_visit_customer_type']['customer']['address'] = "15 rue du test";
        $values['app_bundle_visit_customer_type']['customer']['postCode'] = "77250";
        $values['app_bundle_visit_customer_type']['customer']['city'] = "Moret sur Loing";
        $values['app_bundle_visit_customer_type']['customer']['country'] = "France";
        $client->request('POST', $form->getUri(), $values,
            $form->getPhpFiles());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }

}