<?php
require_once "./Router.php";
require_once "./Ecommerce.php";

$router = new Router();

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

$router->delete('/php/deleteCustomer', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->deleteCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getProducts', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->getProducts();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setProduct', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->setProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateProduct', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->updateProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->delete('/php/deleteProduct', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->deleteProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateStock', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->updateStock();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getOrders', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->getOrders();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setOrder', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->setOrder();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateOrderStatus', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->updateOrderStatus();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->delete('/php/deleteOrder', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->deleteOrder();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getStats', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->getStats();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/search', function() {
    $ecommerce = new Ecommerce();
    $response = $ecommerce->search();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->route();