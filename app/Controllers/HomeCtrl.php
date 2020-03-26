<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeCtrl extends Controller {
    public function Home($request, $response) {
        $this->render($response, "Home.twig");
    }

    public function Whoareus($request, $response) {
        $DB = self::getDB();
        $q = $DB->prepare("SELECT * FROM teams");
        $q->execute();
        $teams = $q->fetchAll();
        $this->render($response, "Whoareus.twig", compact($teams));
    }

    public function Devis($request, $response) {
        $this->render($response, "Devis.twig");
    }

    public function Events($request, $response) {
        $this->render($response, "Events.twig");
    }

    public function Contact($request, $response) {
        $this->render($response, "Contact.twig");
    }

    public function ListingMaterials($request, $response) {

        $DB = self::getDB();

        $this->render($response, "ListingMaterials.twig");
    }

    public function ContactUs($request, $response) {
        return;
    }
}