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
   die($e);
}

$app->get('/', \App\Controllers\HomeCtrl::class . ':Home')->setName("home");
$app->get('/qui-sommes-nous', \App\Controllers\HomeCtrl::class . ':Whoareus')->setName("whoareus");
$app->get('/devis', \App\Controllers\HomeCtrl:: class . ':Devis')->setName("devis");
$app->get('/contact', \App\Controllers\HomeCtrl::class . 'Contact')->setName("contact");
$app->get('/events', \App\Controllers\HomeCtrl::class . 'Events')->setName("events");
$app->get('/matos', \App\Controllers\HomeCtrl::class . 'ListingMaterials')->setName("listing");

$app->get('/admin/connection', \App\Controllers\AdminCtrl::class . ':Connection')->setName("connection");
$app->post('/admin/connection', \App\Controllers\AdminCtrl::class . ':PostConnection');

$app->get('/admin', \App\Controllers\AdminCtrl::class . ':Index')->setName("admin");

$app->get('/admin/team/{id}', \App\Controllers\AdminCtrl::class . ':Team')->setName("adminTeamEdit");
$app->get('/admin/team', \App\Controllers\AdminCtrl::class . ':Team')->setName("adminTeamAdd");
$app->post('/admin/team/{id}', \App\Controllers\AdminCtrl::class . ':PostTeam');
$app->post('/admin/team/', \App\Controllers\AdminCtrl::class . ':PostTeam');

$app->get('/admin/equipment', \App\Controllers\AdminCtrl::class . ':Equipment')->setName("equipment");
$app->post('/admin/equipment/modify/{id}', \App\Controllers\AdminCtrl::class . ':PostEquipment');
$app->post('/admin/equipment/modify/', \App\Controllers\AdminCtrl::class . ':PostEquipment');
$app->post('/admin/equipment/delete/{id}', \App\Controllers\AdminCtrl::class . ':DeleteEquipment');
$app->post('/admin/configuration/modify/{id}', \App\Controllers\AdminCtrl::class . ':PostConfiguration');
$app->post('/admin/configuration/modify/', \App\Controllers\AdminCtrl::class . ':PostConfiguration');
$app->post('/admin/configuration/delete/{id}', \App\Controllers\AdminCtrl::class . ':DeleteConfiguration');

$app->post('/post/contact-us', \App\Controllers\PostCtrl::class . ':ContactUs')->setName("contact_us_post");

$app->run();