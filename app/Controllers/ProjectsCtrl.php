<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProjectsCtrl extends Controller {

    public function RenderProjects($request, $response, $args) {
        $q = self::getDB()->prepare("SELECT * FROM projects WHERE slug = ?");
        $q->execute([$args['prject_id']]);
        $p = $q->fetch();

        if (empty($p)) {
            return $this->render($response, "404.php");
        }

        return $this->render($response, "Projects.twig", ['project' => $q]);
    }

}