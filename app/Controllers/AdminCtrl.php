<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\UploadedFile;


class AdminCtrl extends Controller {

    public function db(){
        return self::getDB();
    }

    public function Index($request, $response) {
        if(isset($_SESSION['idAdmin'])){
            $DB = self::getDB();

            $res = json_decode(json_encode($DB->query("SELECT *  FROM teams")->fetchAll()), true);

            $data = array(
                'users'=>$res
            );

            $this->render($response, "admin/Dashboard.twig", $data);
        } else {
            return $response->withRedirect('/admin/connection');
        }
    }

    public function Connection($request, $response) {
        $this->render($response, "admin/Connection.twig");
    }

    public function PostConnection($request, $response) {
        $info = $request->getParams();

        $DB = self::getDB();

        $res = $DB->query("SELECT id  FROM admin WHERE email LIKE '" . $info['email'] . "' AND password LIKE '" . md5($info['password']) . "'")->fetch();

        if($res){
            $_SESSION['idAdmin'] = $res->id;
            return $response->withRedirect("/admin");
        } else {
            return $response->withRedirect('/admin/connection', 301);
        }
    }

    public function Team($request, $response, $args) {
        if(isset($_SESSION['idAdmin'])){
            $DB = self::getDB();

            $posts = json_decode(json_encode($DB->query("SELECT post FROM posts")->fetchAll()), true);

            if(isset($args['id'])){
                $res = json_decode(json_encode($DB->query("SELECT *  FROM teams WHERE id=".$args['id'])->fetchAll()), true);
                $data = array(
                    'user'=>$res[0],
                    'posts'=> $posts,
                );
            } else {
                $data = array(
                    'posts'=> $posts,
                );
            }

            $this->render($response, "admin/teamEdit.twig", $data);
        } else {
            return $response->withRedirect('/admin/connection');
        }
    }

    public function Equipment($request, $response, $args) {
        if(isset($_SESSION['idAdmin'])){
            $DB = self::getDB();

            $res = json_decode(json_encode($DB->query("SELECT *  FROM equipment")->fetchAll()), true);
            $types = json_decode(json_encode($DB->query("SELECT * FROM equipmentType")->fetchAll()), true);

            $conf = json_decode(json_encode($DB->query("SELECT *  FROM configuration")->fetchAll()), true);

            $data = array(
                'equipments'=>$res,
                'types'=>$types,
                'configurations'=>$conf
            );

            var_dump($types);

            $this->render($response, "admin/equipment.twig", $data);
        } else {
            return $response->withRedirect('/admin/connection');
        }
    }

    public function PostEquipment($request, $response, $args){

        $info = $request->getParams();

        $DB = self::getDB();

        if (isset($args['id'])){
            $DB->query("UPDATE equipment SET name = '".$info['name']."', type='".$info['type']."', description='".$info['description']."', price = " .$info['price']. " WHERE id=".$args['id']);
        } else {
            $DB->query("INSERT INTO equipment (name, type, description, price) VALUES ('".$info['name']."','".$info['type']."','".$info['description']."',".$info['price'].")");
        }

        return $response->withRedirect("/admin/equipment");
    }

    public function DeleteEquipment($request, $response, $args){

        $DB = self::getDB();

        if (isset($args['id'])){
            $DB->query("DELETE FROM equipment WHERE id=".$args['id']);
        }

        return $response->withRedirect("/admin/equipment");
    }

    public function PostConfiguration($request, $response, $args){

        $info = $request->getParams();

        $DB = self::getDB();

        if (isset($args['id'])){
            $DB->query("UPDATE configuration SET name = '".$info['name']."', nbPerson=".$info['nbPerson'].", description='".$info['description']."', price = " .$info['price'].", roomSize='".$info['roomSize']."' WHERE id=".$args['id']);
        } else {
            $DB->query("INSERT INTO configuration (name, description, nbPerson, roomSize, price) VALUES ('".$info['name']."','".$info['description']."',".$info['nbPerson'].",'".$info['roomSize']."',".$info['price'].")");
        }

        return $response->withRedirect("/admin/equipment");
    }

    public function DeleteConfiguration($request, $response, $args){

        $DB = self::getDB();

        if (isset($args['id'])){
            $DB->query("DELETE FROM configuration WHERE id=".$args['id']);
        }

        return $response->withRedirect("/admin/equipment");
    }

    public function PostTeam($request, $response, $args){


        $directory = '/var/www/public/images/profiles/humans/';

        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['teamImg'];

        $info = $request->getParams();

        if(isset($uploadedFile)){
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                $basename = bin2hex(random_bytes(8));
                $filename = sprintf('%s.%0.8s', $basename, $extension);
                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
            }
        }



        $DB = self::getDB();

        if(isset($args['id']) && isset($filename)){
            $DB->query("UPDATE teams SET name = '".$info['nameEdit']."', posts='".$info['post']."', description='".$info['descriptionEdit']."', thumbnail='".$filename."' WHERE id=".$args['id']);
        } elseif (isset($args['id'])){
            $DB->query("UPDATE teams SET name = '".$info['nameEdit']."', posts='".$info['post']."', description='".$info['descriptionEdit']."' WHERE id=".$args['id']);
        } elseif (isset($filename)){
            $DB->query("INSERT INTO teams (name, posts, description, thumbnail) VALUES ('".$info['nameEdit']."','".$info['post']."','".$info['descriptionEdit']."','".$filename."')");
        } else {
            $DB->query("INSERT INTO teams (name, posts, description) VALUES ('".$info['nameEdit']."','".$info['post']."','".$info['descriptionEdit']."')");
        }

        return $response->withRedirect("/admin");
    }

}


