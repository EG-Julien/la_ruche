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

        $data = array();
        $posts = array();
        $postsTemp = json_decode(json_encode($DB->query("SELECT * FROM posts")->fetchAll()), true);



        foreach($postsTemp as $post){
            $teammate = json_decode(json_encode($DB->query("SELECT * FROM teams WHERE posts='".$post['post']."'")->fetchAll()), true);
            $post['teammates'] = $teammate;
            array_push($posts, $post);
        }

        $data['posts']= $posts;

        $this->render($response, "Whoareus.twig", $data);
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