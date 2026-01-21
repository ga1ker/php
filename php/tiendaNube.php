<?php

require_once "./MarketplaceBase.php";

class TiendaNube extends MarketplaceBase {
    protected string $marketplaceName = 'tiendaNube';

    public function createProduct(array $product): array
    {
        // hay que implementar la lógica
        $this->log("Creando producto: " . ($product['name'] ?? 'Sin nombre'));
        
        $url = $this->config['api_url'] . '/products';
        $headers = [
            'Authorization: Bearer ' . $this->config['api_token'],
            'Content-Type: application/json'
        ];
        
        $response = $this->request($url, 'POST', $headers, $product);
        
        return $response;
    }

    public function updateProduct(string $id, array $product): array // actualizar un producto en TiendaNube
    {
        // hay que implementar la lógica
        $this->log("Actualizando producto ID: {$id}");
        
        $url = $this->config['api_url'] . "/products/{$id}";
        $headers = [
            'Authorization: Bearer ' . $this->config['api_token'],
            'Content-Type: application/json'
        ];
        
        $response = $this->request($url, 'PUT', $headers, $product);
        
        return $response;
    }

    public function deleteProduct(string $id): bool
    {
        // hay que implementar la lógica
        $this->log("Eliminando producto ID: {$id}");
        
        $url = $this->config['api_url'] . "/products/{$id}";
        $headers = [
            'Authorization: Bearer ' . $this->config['api_token']
        ];
        
        $response = $this->request($url, 'DELETE', $headers);
        
        return isset($response['success']) && $response['success'] === true;
    }

    public function supportsListing(): bool 
    {
        return true; // si soporta listado de productos
    }

    public function listProducts(): array // obtener lista de productos
    {
        $this->log("Listando productos");
        
        $url = $this->config['api_url'] . '/products';
        $headers = [
            'Authorization: Bearer ' . $this->config['api_token']
        ];
        
        $response = $this->request($url, 'GET', $headers);
        
        return $response['products'] ?? [];
    }

    protected function validateConfig(): void // validar configuración
    {
        parent::validateConfig(); // primero validar configuración base
        
        // validar que existan las credenciales
        if (!isset($this->config['api_token']) || empty($this->config['api_token'])) {
            throw new Exception("API token no configurado para TiendaNube");
        }
        
        if (!isset($this->config['api_url']) || empty($this->config['api_url'])) {
            throw new Exception("API URL no configurada para TiendaNube");
        }
        
        $this->log("Configuración validada correctamente");
    }
}