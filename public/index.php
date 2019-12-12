<?php
@session_start();

date_default_timezone_set('Europe/Paris');

require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\App(
    [
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]
);

require '../app/container.php';

try {
    $DB = new PDO('mysql:dbname=' . $dbname . ';host=' . $dbhost . ';charset=utf8', "$dbuser", "$dbpassword");
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    \App\Controllers\Controller::setDB($DB);
} catch (Exception $e) {
   // $DB = die($e);
}

$app->get('/', \App\Controllers\HomeCtrl::class . ':Home');
$app->get('/whoami', \App\Controllers\HomeCtrl::class . ':Whoami')->setName("whoami");
$app->get('/contact', \App\Controllers\HomeCtrl:: class . ':ContactUs')->setName("contact_us");

$app->get('/projects/{ project_id }', \App\Controllers\ProjectsCtrl::class . ':RenderProjects')->setName("project_render");

$app->post('/post/contact-us', \App\Controllers\PostCtrl::class . ':ContactUs')->setName("contact_us_post");

$app->run();