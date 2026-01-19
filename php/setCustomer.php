<?php

header('Content-Type: application/json');
require("./Ecommerce.php");

$ecommerce = new Ecommerce();

$ecommerce->setCustomer();

