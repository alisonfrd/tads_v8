<?php

define('ROOT_PATH', getcwd());

header('Content-Type: application/json');

try
{  
    require('src/ServiceBootstrap.php');

    $serviceBootstrap = new ServiceBootstrap();

    $output = $serviceBootstrap->execute ();

    $response = ['status' => $serviceBootstrap->getStatus ()];

    echo json_encode($response);
}
catch (Exception $e) 
{
    if ($e->getMessage ())
        $serviceBootstrap->addError ($e->getMessage ());

    $response = ['errors' =>  $serviceBootstrap->getErrors ()];

    echo json_encode($response);
}
