<?php
require_once "./Router.php";
require_once "./Ecommerce.php";

$router = new Router();

// rutas para clientes
$router->get('/php/getCustomer', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->getCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setCustomer', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->setCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateCustomer', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->updateCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});


$router->route();