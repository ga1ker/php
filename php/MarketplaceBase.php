<?php

abstract class MarketplaceBase
{
    protected string $marketplaceName;
    protected array $config = []; // credenciales del marketplace

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
    }

    protected function validateConfig(): void // validar las credenciales
    {
        if (empty($this->config)) {
            throw new Exception(
                "Configuración vacía para {$this->marketplaceName}"
            );
        }
    }

    public function getMarketplaceName(): string // obtener el nombre del marketplace
    {
        return $this->marketplaceName;
    }

    protected function request( // los request de GET, POST, UPDATE
        string $url,
        string $method = 'GET',
        array $headers = [],
        array $body = []
    ): array {
        $ch = curl_init();
        
        curl_setopt_array($ch, [ // configuración de curl
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
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

        return json_decode($response, true) ?? []; // convertir respuesta JSON a array
    }

    protected function log(string $message): void // registrar mensajes de log
    {
        error_log("[{$this->marketplaceName}] {$message}");
    }

    public function supportsListing(): bool // por defecto, no todos tienen lista de productos
    {
        return false;
    }

    public function listProducts(): array // solo debe usarse si supportsListing() === true
    {
        throw new Exception(
            "{$this->marketplaceName} no soporta el listado de productos"
        );
    }

    // métodos que todas las clases de mp deben implementar
    abstract public function createProduct(array $product): array;
    abstract public function updateProduct(string $id, array $product): array;
    abstract public function deleteProduct(string $id): bool;
}