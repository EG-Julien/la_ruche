<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeCtrl extends Controller {
    public function Home($request, $response) {
        $this->render($response, "Home.twig");
    }

    public function Whoami($request, $response) {
        $this->render($response, "Whoami.twig");
    }

    public function ContactUs($request, $response) {
        $this->render($response, "Contact.twig");
    }
}