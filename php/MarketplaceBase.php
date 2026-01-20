<?php

require_once "./MarketplaceBase.php";
require_once "./Shopify.php";
require_once "./Amazon.php";
require_once "./Woocommerce.php";
require_once "./tiendaNube.php";
require_once "./MercadoLibre.php";

class MarketplaceBase {

    public static function create(
        string $type,
        array $config
    ): MarketplaceBase {
        switch (strtolower($type)){

        case 'shopify':
            return new Shopify($config);

        case 'woocomerce':
            return new Woocomerce($config);

        case 'mercadolibre':
            return new MercadoLibre($config);

        case 'amazon':
            return new Amazon($config);

        case 'tiendanube':
            return new TiendaNube($config);

        default:
            throw new Exception("Marketplace no sosportado: ${type}");
        }
    }

    protected string $marketplaceName; // nombre que se definira con Sh,Woo,tN,Amz,ML
    protected array $config = []; // credenciales o configuración

    public function __construct(array $config) { // mi constructor base
        $this->config = $config;
        $this->validateConfig();
    }

    protected function validateConfig(): void { //validamos que tenga minimo la config inicial
        if (empty($this->config)) {
            throw new Exception("Configuración vacía para {$this->marketplaceName}");
        }
    }

    public function getMarketplaceName(): string { // solo obtenemos el nombre del sitio
        return $this->getMarketplaceName;
    }

    protected function request ( // realiza petición HTTP "GET, POST, PUT, DELETE"
        string $url,
        string $method = 'GET',
        string $headers = [],
        string $body = []
    ): array {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADERS => $headers,
            CURLOPT_TIMEOUT => 30,
        ]);

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true) ?? [];
    }

    protected function log(string $message): void { // simple log
        error_log("[{$this->marketplaceName}] {$message}");
    }

    public function supportListing(): bool { // las otras clases lo sobrescriben si aplica
        return false;
    }

    public function listProducts(): array { // solo podría usarse si supportListing = true
        throw new Exception(
            "{$this->marketplaceName} no soporta el listado de productos"
        );
    }

    public function createProduct(array $product): array;

    public function updateProduct(string $id, array $product): array;

    public function deleteProduct(string $id): bool;
}
