<?php

include("vendor/autoload.php");
include("config.php");

$a = new Accounts();

$a->setEmail('Djurre');
$a->save();


$app = new \Slim\Slim();

$app->get('/metric/:name', 'MetricController::getMetricInfo');
$app->post('/metric/:name', 'MetricController::addDataPoint');
$app->notFound(function () use ($app) {
    openPage("404.html",array("error"=> "Given path is not found"));
});

$app->run();



function openPage($template, $data = array())
{
    
    $loader = new Twig_Loader_Filesystem("templates/");
   

    $twig = new Twig_Environment($loader);

     $twig->addFilter('var_dump', new Twig_Filter_Function('var_dump'));
    $data['ROOT'] = EXT_ROOT;
    $data['TPL_ROOT'] = EXT_ROOT."/templates/";
  
    die($twig->render($template, $data));
}