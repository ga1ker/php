<?php

require_once "./Ecommerce.php"

class Shopify extends Ecommerce {

    private function callShopifyAPI($endpoint, $method = 'GET', $data = null) {
        //Aquí implemento lo de la API okva
    }

    public function getCustomer(): array {
        if ($_GET['use_shopify'] ?? false) {
            $shopifyData = $this->callShopifyAPI('customer');

            return[
                "status" => "ok",
                "code" => $this->codeStr . 200,
                "answer" => "Clientes de Shopify",
                "usuarios" => "transformedData"
            ];
        }

        return parent::getCustomer();
    }
}