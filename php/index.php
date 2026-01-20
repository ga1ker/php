<?php
require_once "./Router.php";
require_once "./Ecommerce.php";

$router = new Router();

$router->get('/php/getCustomer', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->getCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setCustomer', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->setCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/updateCustomer', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->updateCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/deleteCustomer', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->deleteCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getProducts', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->getProducts();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setProduct', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->setProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateProduct', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->updateProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/deleteProduct', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->deleteProduct();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateStock', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->updateStock();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getOrders', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->getOrders();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/setOrder', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->setOrder();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->put('/php/updateOrderStatus', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->updateOrderStatus();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/php/deleteOrder', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->deleteOrder();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/getStats', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->getStats();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/php/search', function() {
    $session = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE['session'] ?? '';
    if (empty($session)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "code" => "ecom-400",
            "answer" => "Token requerido"
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    $ecommerce = new Ecommerce($session);
    $response = $ecommerce->search();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/shopify/getCustomer', function() {
    $shopify = new Shopify();
    $response = $shopify->getCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->post('/shopify/setCustomer', function() {
    $shopify = new Shopify();
    $response = $shopify->setCustomer();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->get('/shopify/getProducts', function() {
    $shopify = new Shopify();
    $response = $shopify->getProducts();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

$router->route();