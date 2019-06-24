<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index(): Response
    {
        return new Response('Voici la première itération de la première page du Projet Louvre Tickets');
    }
}