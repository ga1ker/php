<?php

class Shopify {

    public string $mypos_id;
    public string $shopify_domain;
    public string $access_token;
    public string $api_version = '2025-01';
    public string $base_url;
    public string $codeStr = "shopify-";

    public function __construct() {
        $this->mypos_id = $_COOKIE['mypos_id'] ?? '';
        $this->shopify_domain = $_COOKIE['shopify_domain'] ?? '';
        $this->access_token = $_COOKIE['access_token'] ?? '';

        if ($this->shopify_domain && $this->access_token) {
            $this->base_url = "https://{$this->shopify_domain}/admin/api/{$this->api_version}";
        }
    }

    private function getShopify(string $method, string $endpoint, array $data = []): array {
        $json = [];
        try {
            if (!$this->shopify_domain || !$this->access_token) {
                http_response_code(401)
                $json['status'] = 'error';
                $json['code'] = $this->codeStr . 401;
                $json['answer'] = "Credenciales de Shopify no configuradas";
                return $json;
            }

            $url = $this->base_url . $endpoint;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-Shopify-Access-Token: ' . $this->access_token 
            ]);

            if (in_array($method, ['POST', 'PUT', 'PATCH']) && !empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);

            if ($error) {
                throw new Exception('CURL Error: ' . $error);
            }

            $decodedResponse = json_decode($response, true);

            if ($httpCode >= 400) {
                http_response_code($httpCode);
                $json['status'] = "error";
                $json['code'] = $this->codeStr . $httpCode;
                $json['answer'] = "Shopify API Error";
                $json['shopify_error'] = $decodedResponse['errors'] ?? $response;
                return $json;
            }

            $json['status'] = 'ok';
            $json['code'] = $this->codeStr . $httpCode;
            $json['answer'] = 'Operación completada';
            $json['data'] = $decodedResponse;
            return $json;
        } catch (Exception $e) {
            http_response_code(500)
            $json['status'] = 'error';
            $json['code'] = $this->codeStr . 500;
            $json['answer'] = 'Error: ' . $e->getMessage();
            return $json;
        }
    }

    public function getProducts(): array {
        $json = [];
        try {
            if ($this->mypos_id == '') {
                http_response_code(400);
                $json['status'] = 'error';
                $json['code'] = $this->codeStr . 400;
                $json['answer'] = 'No hay mypos_id';
                return $json;
            }

            $product_id = $_GET['id'] ?? null;
            $limit = $_GET['limit'] ?? 50;
            $page = $_GET['page'] ?? 1;
            $collection_id = $_GET['collection_id'] ?? null;
            $status = $_GET['status'] ?? 'active';

            if ($product_id) {
                $endpoint = '/products/{product_id}.json';
                $result = $this->getShopify('GET', $endpoint);

                if ($result['status'] === 'ok') {
                    $product = $result['data']['product'] ?? [];
                    if ($product) {
                        $this->syncProductToLocal($product);
                    }
                }

                return $result;
            }

            $endpoint = '/products.json?limit={$limit}&page={$page}&status={$status}';
            if ($collection_id) {
                $endpoint = "/collections/{$collection_id}/products.json?limit={limit}&page={$page}";
            }

            $result = $this->getShopify('GET', $endpoint);

            if ($result['status'] === 'ok' && isset($result['data']['products'])) {
                foreach ($result['data']['products'] as $product) {
                    $this->syncProductToLocal($product);
                }
            }

            return $result;
        } catch (Exception $e) {
            http_response_code(500)
            $json['status'] = 'error';
            $json['code'] = $this->codeStr . 500;
            $json['answer'] = 'Error: ' . $e->getMessage();
            return $json;
        }
    }

    private function syncProductToLocal(array $shopifyProduct): void {
        try {
            $pdo = Database::getConnection();

            $checkStmt = $pdo->prepare('SELECT id FROM productos WHERE mypos_id = ? AND shopify_id = ?');
            $checkStmt->execute([$this->mypos_id, $shopifyProduct['id']]);

            if ($checkStmt->fetch()) {
                $updateStmt = $pdo->prepare("
                    UPDATE productos SET
                        codigo = ?,
                        nombre = ?,
                        descripcion = ?,
                        precio = ?,
                        stock = ?,
                        estado = ?,
                        update_at = CURRENT_TIMESTAMP
                        WHERE mypos_id = ? AND shopify_id = ?
                ");

                $totalStock = 0;
                foreach ($shopifyProduct['variants'] as $variant) {
                    $totalStock += $variant['inventory_quantity'];
                }

                updateStmt->execute([
                    $shopifyProduct['variant'][0]['sku'] ?? 'SHOPIFY-' . $shopifyProduct['id'],
                    $shopifyProduct['title'],
                    $shopifyProduct['body_html'] ?? '',
                    $shopifyProduct['variants'][0]['price'] ?? 0,
                    $totalStock,
                    $shopifyProduct['status'] === 'active' ? 'activo' : 'inactivo',
                    $this->mypos_id,
                    $shopifyProduct['id']
                ]);
            } else {
                $insertStmt = $pdo->prepare("
                    INSERT INTO productos (
                        mypos_id, shopify_id, codigo, nombre, descripcion,
                        precio, stock, estado, imagen_url
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $totalStock = 0;
                foreach ($shopifyProduct['variants'] as $variant) {
                    $totalStock += $variant['inventory_quantity'];
                }

                $imageUrl = !empty($shopifyProduct['images']) ? $shopifyProduct['images'][0]['src'] : null;

                $insertStmt->execute([
                    $this->mypos_id,
                    $shopifyProduct['id'],
                    $shopifyProduct['variants'][0]['sku'] ?? 'SHOPIFY-' . $shopifyProduct['id'],
                    $shopifyProduct['title'],
                    $shopifyProduct['body_html'] ?? '',
                    $shopifyProduct['variants'][0]['price'] ?? 0;
                    $totalStock,
                    $shopifyProduct['status'] === 'active' ? 'activo' : 'inactivo',
                    $imageUrl
                ]);
            }
        } catch (Exception $e) {
            error_log('Error sincronizando producto: ' . $e->getMessage());
        }
    }

    public function createProduct(): array {
        $json = [];
        try {
            if ($this->mypos_id == '') {
                http_response_code(400);
                $json['status'] = 'error';
                $json['code'] = $this->codeStr . 400;
                $json['answer'] = 'No hay mypos_id';
                return $json;
            }

            $input = json_decode(file_get_contents('php://input'), true);

            $requiredFields = ['title', 'variants'];
            foreach ($requiredFields as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    $json['status'] = 'error';
                    $json['code'] = $this->codeStr . 400;
                    $json['answer'] = 'Campo requerido: ' . $field;
                    return $json;
                }
            }

            $productData = [
                'product' => [
                    'title' => $input['title'],
                    'body_html' => $input['description'] ?? '',
                    'vendor' => $input['vendor'] ?? ,
                    'product_type' => $input['product_type'] ?? ,
                    'tags' => $input['tags'] ?? ,
                    'status' => $input['status'] ?? 'draft',
                    'variants' => []
                ]
            ];

            foreach ($input['variants'] as $variant) {
                $productData['product']['variants'][] = [
                    'price' => $variant['price'],
                    'sku' => $variant['sku'] ?? '',
                    'inventory_quantity' => $variant['inventory_quantity'] ?? 0,
                    'inventory_managament' => 'shopify'
                ];
            }

            if (!empty($input['images'])) {
                $productData['product']['images'] = array_map(function($img) {
                    return ['src' => img];
                }, $input['images']);
            }

            $result = $this->getShopify('POST', '/products.json', $productData);

            if ($result['status'] === 'ok' && isset($result['data']['product'])) {
                $this->syncProductToLocal($result['data']['product']);
            }

            return $result;
        } catch (Exception $e) {
            http_response_code(500);
            $json['status'] = 'error';
            $json['code'] = $this->codeStr . 500;
            $json['answer'] = 'Error: ' . $e.getMessage();
            return $json;
        }
    }

    public function updateProduct(): any {
        $json = [];
        try {
            if ($this->mypos_id == '') {
                http_response_code(400);
                $json['status'] = 'error';
                $json['code'] = $this->codeStr . 400;
                $json['answer'] = 'Error al obtener mypos_id';
                return $json;
            } 

            $input = json_decode(file_get_contents('php://input'), true);

            if (empty($input['id'])) {
                http_response_code(400);
                $json['status'] = 'error';
                $json['code'] = $this->codeStr . 400;
                $json['answer'] = 'ID del producto es requerido';
                return $json;
            }

            $productData = ['product' => []];

            if (isset($input['title'])) $productData['product']['title'] = $input ['title']
        }
    }

}
