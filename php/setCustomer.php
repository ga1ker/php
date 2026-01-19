<?php
header('Content-Type: application/json; charset=utf-8');
require("./Ecommerce.php");

$ecommerce = new Ecommerce();

$response = $ecommerce->setCustomer();
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
