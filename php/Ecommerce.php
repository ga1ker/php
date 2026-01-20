<?php

require_once "./Database.php";
require_once "./jwt.php";

class Ecommerce 
{
    public string $mypos_id;
    public string $codeStr = "ecom-";

    public function __construct($session) {
        $jwt = new JWT();
        $payload = $jwt->decrypt($session);
        
        if ($payload && isset($payload['mypos_id'])) {
            $this->mypos_id = $payload['mypos_id'];
        } else {
            $this->mypos_id = '';
        }
    }
    
    public function getCustomer(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = $this->codeStr . 400;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }


            // Api de aizu
            // $ch = curl_init();

            // curl_setopt($ch, CURLOPT_URL, "api.aizu");
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // $response = curl_exec($ch);
            
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, nombre, apellidos FROM pos WHERE mypos_id = ? ORDER BY id");
            $stmt->execute([$this->mypos_id]);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = $this->codeStr . 200;
            $json["answer"] = "Usuarios obtenidos correctamente";
            $json["usuarios"] = $usuarios;
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = $this->codeStr . 500;
            $json["answer"] = "serv - " . $e->getCode();
            return $json;
        }
    }

    public function setCustomer(): array{
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(401);
                $json["status"] = "error";
                $json["code"] = $this->codeStr . 401;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
            $apellidos = isset($_POST["apellidos"]) ? $_POST["apellidos"] : '';
            
            if ($nombre == '' || $apellidos == '') {
                http_response_code(402);
                $json["status"] = "error";
                $json["code"] = $this->codeStr . 402;
                $json["answer"] = "Nombre y apellidos son requeridos y no pueden estar vacíos";
                return $json;
            }

            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO pos (mypos_id, nombre, apellidos) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $this->mypos_id,
                $nombre,
                $apellidos
            ]);

            http_response_code(201);
            $json["status"] = "ok";
            $json["code"] = 201;
            $json["answer"] = "Usuario creado correctamente";
            return $json;

        } catch (Exception $e) {
            http_response_code(501);
            $json["status"] = "error";
            $json["code"] = $this->codeStr . 501;
            $json["answer"] = "serv - " . $e->getCode();
            return $json;
        }
    }

    public function updateCustomer(): array{
        $json = [];

        try{
            if ($this->mypos_id == "") {
                http_response_code(403);
                $json["status"] = "error";
                $json["answer"] = "No hay mypos_id";
                $json["code"] = $this->codeStr . 403;
                return $json;
            }

            $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
            $apellidos = isset($_POST["apellidos"]) ? $_POST["apellidos"] : '';
            $id = isset($_POST["id"]) ? $_POST["id"] : '';
            

            if ($id == '' || $apellidos == '' || $nombre == '') {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = $this->codeStr . 404;
                $json["answer"] = "ID, nombre y apellidos son requeridos";
                return $json;
            }

            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE pos SET nombre = ?, apellidos = ? WHERE id = ? AND mypos_id = ?");
            $result = $stmt->execute([
                $nombre,
                $apellidos,
                $id,
                $this->mypos_id
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(405);
                $json["status"] = "error";
                $json["answer"] = "Cliente no encontrado";
                $json["code"] = $this->codeStr . 405;
                return $json;
            }

            http_response_code(203);
            $json["status"] = "ok";
            $json["answer"] = "Usuario actualizado";
            $json["code"] = $this->codeStr . 203;
            return $json;

        } catch (Exception $e){
            http_response_code(502);
            $json["answer"] = "Error: " . $e->getCode();
            $json["code"] = $this->codeStr . 502;
            $json["status"] = "serv - " . $e->getCode();
            return $json;
        }
    }

    public function deleteCustomer(): array{
        $json = [];
        try{
            if ($this->mypos_id == "") {
                http_response_code(406);
                $json["status"] = "error";
                $json["answer"] = "No hay mypos_id";
                $json["code"] = $this->codeStr . 406;
                return $json;
            }
            
            $id = isset($_POST["id"]) ? $_POST["id"] : '';

            if ($id == '') {
                http_response_code(407);
                $json["status"] = "error";
                $json["code"] = $this->codeStr . 407;
                $json["answer"] = "ID es requerido";
                return $json;
            }
            
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("DELETE FROM pos WHERE id = ? AND mypos_id = ?");
            $result = $stmt->execute([
                intval($id),
                $this->mypos_id
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(408);
                $json["status"] = "error";
                $json["answer"] = "Cliente no encontrado";
                $json["code"] = $this->codeStr . 408;
                return $json;
            }

            http_response_code(203);
            $json["status"] = "ok";
            $json["answer"] = "Usuario eliminado";
            $json["code"] = $this->codeStr . 203;
            return $json;
            
        } catch (Exception $e){
            http_response_code(503);
            $json["status"] = "error";
            $json["code"] = $this->codeStr . 503;
            $json["answer"] = "Error: " . $e->getCode();
            return $json;
        }
    }

    public function getProducts(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $pdo = Database::getConnection();
            
            $id = $_GET['id'] ?? null;
            $categoria = $_GET['categoria'] ?? null;
            $estado = $_GET['estado'] ?? null;
            $busqueda = $_GET['busqueda'] ?? null;
            $stock_bajo = isset($_GET['stock_bajo']) && $_GET['stock_bajo'] === 'true';
            
            if ($id) {
                $sql = "SELECT * FROM productos WHERE id = ? AND mypos_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([intval($id), $this->mypos_id]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$producto) {
                    http_response_code(404);
                    $json["status"] = "error";
                    $json["code"] = 404;
                    $json["answer"] = "Producto no encontrado";
                    return $json;
                }
                
                http_response_code(200);
                $json["status"] = "ok";
                $json["code"] = 200;
                $json["answer"] = "Producto obtenido";
                $json["producto"] = $producto;
                return $json;
            }
            
            $sql = "SELECT * FROM productos WHERE mypos_id = ?";
            $params = [$this->mypos_id];
            
            if ($categoria) {
                $sql .= " AND categoria = ?";
                $params[] = $categoria;
            }
            
            if ($estado) {
                $sql .= " AND estado = ?";
                $params[] = $estado;
            }
            
            if ($busqueda) {
                $sql .= " AND (nombre ILIKE ? OR codigo ILIKE ? OR descripcion ILIKE ?)";
                $busquedaParam = "%" . $busqueda . "%";
                $params[] = $busquedaParam;
                $params[] = $busquedaParam;
                $params[] = $busquedaParam;
            }
            
            if ($stock_bajo) {
                $sql .= " AND stock <= stock_minimo";
            }
            
            $orden = $_GET['orden'] ?? 'nombre';
            $direccion = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY $orden $direccion";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Productos obtenidos correctamente";
            $json["productos"] = $productos;
            $json["total"] = count($productos);
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function setProduct(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            $camposRequeridos = ['codigo', 'nombre', 'precio'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($input[$campo]) || (is_string($input[$campo]) && empty(trim($input[$campo])))) {
                    http_response_code(400);
                    $json["status"] = "error";
                    $json["code"] = 400;
                    $json["answer"] = "Campo requerido: " . $campo;
                    return $json;
                }
            }

            $pdo = Database::getConnection();
            
            $checkStmt = $pdo->prepare("SELECT id FROM productos WHERE mypos_id = ? AND codigo = ?");
            $checkStmt->execute([$this->mypos_id, trim($input['codigo'])]);
            
            if ($checkStmt->fetch()) {
                http_response_code(409);
                $json["status"] = "error";
                $json["code"] = 409;
                $json["answer"] = "El código ya existe";
                return $json;
            }
            
            $sql = "INSERT INTO productos (
                mypos_id, codigo, nombre, descripcion, categoria, 
                precio, costo, stock, stock_minimo, imagen_url, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $this->mypos_id,
                trim($input['codigo']),
                trim($input['nombre']),
                $input['descripcion'] ?? null,
                $input['categoria'] ?? null,
                floatval($input['precio']),
                isset($input['costo']) ? floatval($input['costo']) : null,
                intval($input['stock'] ?? 0),
                intval($input['stock_minimo'] ?? 5),
                $input['imagen_url'] ?? null,
                $input['estado'] ?? 'activo'
            ]);

            http_response_code(201);
            $json["status"] = "ok";
            $json["code"] = 201;
            $json["answer"] = "Producto creado correctamente";
            $json["id"] = $pdo->lastInsertId();
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function updateProduct(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "ID del producto es requerido";
                return $json;
            }

            $camposRequeridos = ['codigo', 'nombre', 'precio'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($input[$campo]) || (is_string($input[$campo]) && empty(trim($input[$campo])))) {
                    http_response_code(400);
                    $json["status"] = "error";
                    $json["code"] = 400;
                    $json["answer"] = "Campo requerido: " . $campo;
                    return $json;
                }
            }

            $pdo = Database::getConnection();
            
            $checkStmt = $pdo->prepare("SELECT id FROM productos WHERE mypos_id = ? AND codigo = ? AND id != ?");
            $checkStmt->execute([$this->mypos_id, trim($input['codigo']), intval($input['id'])]);
            
            if ($checkStmt->fetch()) {
                http_response_code(409);
                $json["status"] = "error";
                $json["code"] = 409;
                $json["answer"] = "El código ya existe para otro producto";
                return $json;
            }
            
            $sql = "UPDATE productos SET 
                    codigo = ?, nombre = ?, descripcion = ?, categoria = ?, 
                    precio = ?, costo = ?, stock = ?, stock_minimo = ?, 
                    imagen_url = ?, estado = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ? AND mypos_id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                trim($input['codigo']),
                trim($input['nombre']),
                $input['descripcion'] ?? null,
                $input['categoria'] ?? null,
                floatval($input['precio']),
                isset($input['costo']) ? floatval($input['costo']) : null,
                intval($input['stock'] ?? 0),
                intval($input['stock_minimo'] ?? 5),
                $input['imagen_url'] ?? null,
                $input['estado'] ?? 'activo',
                intval($input['id']),
                $this->mypos_id
            ]);
            
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Producto no encontrado";
                return $json;
            }

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Producto actualizado correctamente";
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function deleteProduct(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "ID del producto es requerido";
                return $json;
            }

            $pdo = Database::getConnection();
            
            $checkOrdenesStmt = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM ordenes o, 
                LATERAL jsonb_to_recordset(o.items) AS item(producto_id INT)
                WHERE o.mypos_id = ? AND item.producto_id = ?
            ");
            $checkOrdenesStmt->execute([$this->mypos_id, intval($input['id'])]);
            $ordenes = $checkOrdenesStmt->fetch();
            
            if ($ordenes && $ordenes['total'] > 0) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "No se puede eliminar el producto porque tiene órdenes relacionadas";
                return $json;
            }
            
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ? AND mypos_id = ?");
            $stmt->execute([intval($input['id']), $this->mypos_id]);
            
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Producto no encontrado";
                return $json;
            }

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Producto eliminado correctamente";
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function updateStock(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['producto_id']) || !isset($input['cantidad'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "producto_id y cantidad son requeridos";
                return $json;
            }

            $pdo = Database::getConnection();
            
            $checkStmt = $pdo->prepare("SELECT stock FROM productos WHERE id = ? AND mypos_id = ?");
            $checkStmt->execute([intval($input['producto_id']), $this->mypos_id]);
            $producto = $checkStmt->fetch();
            
            if (!$producto) {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Producto no encontrado";
                return $json;
            }

            $nuevoStock = $producto['stock'] + intval($input['cantidad']);
            
            if ($nuevoStock < 0) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "Stock no puede ser negativo";
                return $json;
            }

            $updateStmt = $pdo->prepare("UPDATE productos SET stock = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND mypos_id = ?");
            $updateStmt->execute([$nuevoStock, intval($input['producto_id']), $this->mypos_id]);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Stock actualizado correctamente";
            $json["stock_anterior"] = $producto['stock'];
            $json["stock_nuevo"] = $nuevoStock;
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function getOrders(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $pdo = Database::getConnection();
            
            $id = $_GET['id'] ?? null;
            $estado = $_GET['estado'] ?? null;
            $cliente_id = $_GET['cliente_id'] ?? null;
            $numero_orden = $_GET['numero_orden'] ?? null;
            $desde_fecha = $_GET['desde_fecha'] ?? null;
            $hasta_fecha = $_GET['hasta_fecha'] ?? null;
            
            if ($id) {
                $sql = "SELECT * FROM ordenes WHERE id = ? AND mypos_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([intval($id), $this->mypos_id]);
                $orden = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$orden) {
                    http_response_code(404);
                    $json["status"] = "error";
                    $json["code"] = 404;
                    $json["answer"] = "Orden no encontrada";
                    return $json;
                }
                
                if (!empty($orden['items'])) {
                    $orden['items'] = json_decode($orden['items'], true);
                }
                
                http_response_code(200);
                $json["status"] = "ok";
                $json["code"] = 200;
                $json["answer"] = "Orden obtenida";
                $json["orden"] = $orden;
                return $json;
            }
            
            $sql = "SELECT * FROM ordenes WHERE mypos_id = ?";
            $params = [$this->mypos_id];
            
            if ($estado) {
                $sql .= " AND estado = ?";
                $params[] = $estado;
            }
            
            if ($cliente_id) {
                $sql .= " AND cliente_id = ?";
                $params[] = intval($cliente_id);
            }
            
            if ($numero_orden) {
                $sql .= " AND numero_orden ILIKE ?";
                $params[] = "%" . $numero_orden . "%";
            }
            
            if ($desde_fecha) {
                $sql .= " AND DATE(created_at) >= ?";
                $params[] = $desde_fecha;
            }
            
            if ($hasta_fecha) {
                $sql .= " AND DATE(created_at) <= ?";
                $params[] = $hasta_fecha;
            }
            
            $orden = $_GET['orden'] ?? 'created_at';
            $direccion = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY $orden $direccion";
            
            $limit = $_GET['limit'] ?? 50;
            $offset = $_GET['offset'] ?? 0;
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = intval($limit);
            $params[] = intval($offset);
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($ordenes as &$orden) {
                if (!empty($orden['items'])) {
                    $orden['items'] = json_decode($orden['items'], true);
                }
            }

            $countSql = "SELECT COUNT(*) as total FROM ordenes WHERE mypos_id = ?";
            $countParams = [$this->mypos_id];
            
            if ($estado) {
                $countSql .= " AND estado = ?";
                $countParams[] = $estado;
            }
            
            $countStmt = $pdo->prepare($countSql);
            $countStmt->execute($countParams);
            $total = $countStmt->fetch(PDO::FETCH_ASSOC);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Órdenes obtenidas correctamente";
            $json["ordenes"] = $ordenes;
            $json["total"] = $total['total'] ?? 0;
            $json["pagina_actual"] = floor($offset / $limit) + 1;
            $json["total_paginas"] = ceil(($total['total'] ?? 0) / $limit);
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function setOrder(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['cliente_id']) || !isset($input['items']) || !is_array($input['items']) || empty($input['items'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "cliente_id y items (array no vacío) son requeridos";
                return $json;
            }

            $pdo = Database::getConnection();
            $pdo->beginTransaction();

            $clienteStmt = $pdo->prepare("SELECT nombre, apellidos FROM pos WHERE id = ? AND mypos_id = ?");
            $clienteStmt->execute([intval($input['cliente_id']), $this->mypos_id]);
            $cliente = $clienteStmt->fetch();
            
            if (!$cliente) {
                $pdo->rollBack();
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Cliente no encontrado";
                return $json;
            }

            $clienteNombre = trim($cliente['nombre'] . ' ' . $cliente['apellidos']);
            
            $subtotal = 0;
            $itemsDetalle = [];
            
            foreach ($input['items'] as $index => $item) {
                if (!isset($item['producto_id']) || !isset($item['cantidad']) || $item['cantidad'] <= 0) {
                    $pdo->rollBack();
                    http_response_code(400);
                    $json["status"] = "error";
                    $json["code"] = 400;
                    $json["answer"] = "Item " . ($index + 1) . ": producto_id y cantidad (>0) son requeridos";
                    return $json;
                }
                
                $productoStmt = $pdo->prepare("SELECT id, codigo, nombre, precio, stock FROM productos WHERE id = ? AND mypos_id = ? FOR UPDATE");
                $productoStmt->execute([intval($item['producto_id']), $this->mypos_id]);
                $producto = $productoStmt->fetch();
                
                if (!$producto) {
                    $pdo->rollBack();
                    http_response_code(404);
                    $json["status"] = "error";
                    $json["code"] = 404;
                    $json["answer"] = "Producto no encontrado";
                    return $json;
                }
                
                if ($producto['stock'] < $item['cantidad']) {
                    $pdo->rollBack();
                    http_response_code(400);
                    $json["status"] = "error";
                    $json["code"] = 400;
                    $json["answer"] = "Stock insuficiente para: " . $producto['nombre'];
                    return $json;
                }
                
                $itemSubtotal = $producto['precio'] * $item['cantidad'];
                $subtotal += $itemSubtotal;
                
                $updateStockStmt = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $updateStockStmt->execute([intval($item['cantidad']), intval($item['producto_id'])]);
                
                $itemsDetalle[] = [
                    'producto_id' => intval($item['producto_id']),
                    'codigo' => $producto['codigo'],
                    'nombre' => $producto['nombre'],
                    'cantidad' => intval($item['cantidad']),
                    'precio_unitario' => floatval($producto['precio']),
                    'subtotal' => floatval($itemSubtotal)
                ];
            }
            
            $impuestos = floatval($input['impuestos'] ?? 0);
            $envio = floatval($input['envio'] ?? 0);
            $total = $subtotal + $impuestos + $envio;
            
            $numeroOrden = 'ORD-' . date('Ymd-His') . '-' . rand(1000, 9999);
            
            $ordenStmt = $pdo->prepare("
                INSERT INTO ordenes (
                    mypos_id, numero_orden, cliente_id, cliente_mypos_id, cliente_nombre,
                    items, subtotal, total, impuestos, envio,
                    estado, metodo_pago, direccion_envio, notas
                ) VALUES (?, ?, ?, ?, ?, ?::jsonb, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $ordenStmt->execute([
                $this->mypos_id,
                $numeroOrden,
                intval($input['cliente_id']),
                $this->mypos_id,
                $clienteNombre,
                json_encode($itemsDetalle, JSON_UNESCAPED_UNICODE),
                floatval($subtotal),
                floatval($total),
                $impuestos,
                $envio,
                $input['estado'] ?? 'pendiente',
                $input['metodo_pago'] ?? null,
                $input['direccion_envio'] ?? null,
                $input['notas'] ?? null
            ]);
            
            $ordenId = $pdo->lastInsertId();
            $pdo->commit();

            http_response_code(201);
            $json["status"] = "ok";
            $json["code"] = 201;
            $json["answer"] = "Orden creada correctamente";
            $json["orden_id"] = $ordenId;
            $json["numero_orden"] = $numeroOrden;
            $json["total"] = $total;
            return $json;

        } catch (Exception $e) {
            if (isset($pdo)) {
                try {
                    $pdo->rollBack();
                } catch (Exception $rollbackError) {
                    error_log("Error en rollback: " . $rollbackError->getMessage());
                }
            }
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function updateOrderStatus(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['orden_id']) || !isset($input['estado'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "orden_id y estado son requeridos";
                return $json;
            }
            
            $estadosValidos = ['pendiente', 'procesando', 'completada', 'cancelada'];
            if (!in_array($input['estado'], $estadosValidos)) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "Estado no válido";
                return $json;
            }

            $pdo = Database::getConnection();
            
            if ($input['estado'] === 'cancelada') {
                $this->restaurarStockOrden(intval($input['orden_id']));
            }
            
            $stmt = $pdo->prepare("
                UPDATE ordenes 
                SET estado = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ? AND mypos_id = ?
            ");
            
            $stmt->execute([
                $input['estado'],
                intval($input['orden_id']),
                $this->mypos_id
            ]);
            
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Orden no encontrada";
                return $json;
            }

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Estado actualizado";
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    private function restaurarStockOrden(int $ordenId): void
    {
        try {
            $pdo = Database::getConnection();
            
            $stmt = $pdo->prepare("SELECT items FROM ordenes WHERE id = ? AND mypos_id = ?");
            $stmt->execute([$ordenId, $this->mypos_id]);
            $orden = $stmt->fetch();
            
            if (!$orden || empty($orden['items'])) {
                return;
            }
            
            $items = json_decode($orden['items'], true);
            
            foreach ($items as $item) {
                $updateStmt = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ? AND mypos_id = ?");
                $updateStmt->execute([$item['cantidad'], $item['producto_id'], $this->mypos_id]);
            }
            
        } catch (Exception $e) {
            error_log("Error restaurando stock: " . $e->getMessage());
        }
    }

    public function deleteOrder(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['id'])) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "ID de orden es requerido";
                return $json;
            }

            $pdo = Database::getConnection();
            
            $checkStmt = $pdo->prepare("SELECT estado FROM ordenes WHERE id = ? AND mypos_id = ?");
            $checkStmt->execute([intval($input['id']), $this->mypos_id]);
            $orden = $checkStmt->fetch();
            
            if (!$orden) {
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "Orden no encontrada";
                return $json;
            }
            
            if ($orden['estado'] !== 'pendiente') {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "Solo se pueden eliminar órdenes pendientes";
                return $json;
            }
            
            $this->restaurarStockOrden(intval($input['id']));
            
            $stmt = $pdo->prepare("DELETE FROM ordenes WHERE id = ? AND mypos_id = ?");
            $stmt->execute([intval($input['id']), $this->mypos_id]);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Orden eliminada";
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function getStats(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $pdo = Database::getConnection();
            
            $stockBajoStmt = $pdo->prepare("
                SELECT id, codigo, nombre, stock, stock_minimo 
                FROM productos 
                WHERE mypos_id = ? AND stock <= stock_minimo AND estado = 'activo'
                ORDER BY stock ASC
            ");
            $stockBajoStmt->execute([$this->mypos_id]);
            $stockBajo = $stockBajoStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $ventasStmt = $pdo->prepare("
                SELECT 
                    COUNT(*) as total_ordenes,
                    SUM(CASE WHEN estado = 'completada' THEN total ELSE 0 END) as ventas_totales,
                    COUNT(CASE WHEN estado = 'completada' THEN 1 END) as ordenes_completadas,
                    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as ordenes_pendientes,
                    COUNT(CASE WHEN estado = 'procesando' THEN 1 END) as ordenes_procesando,
                    COUNT(CASE WHEN estado = 'cancelada' THEN 1 END) as ordenes_canceladas
                FROM ordenes 
                WHERE mypos_id = ? 
                AND created_at >= DATE_TRUNC('month', CURRENT_DATE)
            ");
            $ventasStmt->execute([$this->mypos_id]);
            $ventas = $ventasStmt->fetch(PDO::FETCH_ASSOC);
            
            $hoyStmt = $pdo->prepare("
                SELECT 
                    COUNT(*) as ordenes_hoy,
                    SUM(total) as ventas_hoy
                FROM ordenes 
                WHERE mypos_id = ? 
                AND estado = 'completada'
                AND DATE(created_at) = CURRENT_DATE
            ");
            $hoyStmt->execute([$this->mypos_id]);
            $hoy = $hoyStmt->fetch(PDO::FETCH_ASSOC);

            $inventarioStmt = $pdo->prepare("
                SELECT 
                    COUNT(*) as total_productos,
                    SUM(stock) as total_unidades,
                    SUM(stock * precio) as valor_inventario
                FROM productos 
                WHERE mypos_id = ? AND estado = 'activo'
            ");
            $inventarioStmt->execute([$this->mypos_id]);
            $inventario = $inventarioStmt->fetch(PDO::FETCH_ASSOC);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Estadísticas obtenidas";
            $json["stats"] = [
                "stock_bajo" => $stockBajo,
                "ventas_mes" => $ventas,
                "ventas_hoy" => $hoy,
                "inventario" => $inventario
            ];
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }

    public function search(): array
    {
        $json = [];
        try {
            if($this->mypos_id == ''){
                http_response_code(404);
                $json["status"] = "error";
                $json["code"] = 404;
                $json["answer"] = "No hay mypos_id";
                return $json;
            }
            
            $query = $_GET['q'] ?? '';
            
            if (empty($query)) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "Parámetro 'q' es requerido";
                return $json;
            }
            
            $pdo = Database::getConnection();
            $resultados = [];
            
            // Buscar productos
            $productosStmt = $pdo->prepare("
                SELECT id, codigo, nombre, precio, stock, 'producto' as tipo
                FROM productos 
                WHERE mypos_id = ? 
                AND (nombre ILIKE ? OR codigo ILIKE ?)
                AND estado = 'activo'
                LIMIT 10
            ");
            $busquedaParam = "%" . $query . "%";
            $productosStmt->execute([$this->mypos_id, $busquedaParam, $busquedaParam]);
            $productos = $productosStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Buscar clientes
            $clientesStmt = $pdo->prepare("
                SELECT id, nombre, apellidos, 'cliente' as tipo
                FROM pos 
                WHERE mypos_id = ? 
                AND (nombre ILIKE ? OR apellidos ILIKE ?)
                LIMIT 10
            ");
            $clientesStmt->execute([$this->mypos_id, $busquedaParam, $busquedaParam]);
            $clientes = $clientesStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $resultados = array_merge($productos, $clientes);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Búsqueda completada";
            $json["resultados"] = $resultados;
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error: " . $e->getMessage();
            return $json;
        }
    }
}