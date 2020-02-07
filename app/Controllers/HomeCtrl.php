<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeCtrl extends Controller {
    public function Home($request, $response) {
        $this->render($response, "Home.twig");
    }

    public function Whoareus($request, $response) {
        $this->render($response, "Whoareus.twig");
    }

    public function Devis($request, $response) {
        $this->render($response, "Devis.twig");
    }

    public function ContactUs($request, $response) {
        $this->render($response, "Contact.twig");
    }
}