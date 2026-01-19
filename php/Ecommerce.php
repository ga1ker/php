<?php

require_once "./Database.php";

class Ecommerce 
{
    public string $mypos_id = "asdasd";

    public function getUsuarios()
    {
        try {
            $pdo = Database::getConnection();

            $stmt = $pdo->prepare("SELECT id, nombre, apellidos FROM pos WHERE mypos_id = ?");
            $stmt->execute([$this->mypos_id]);
            $usuarios = $stmt->fetchAll();

            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "message" => "Usuarios obtenidos correctamente",
                "data" => $usuarios
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => "Error al obtener usuarios"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function setUsuario()
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['nombre']) || !isset($input['apellidos'])) {
            http_response_code(400);
            echo json_encode([
                "status" => 400,
                "message" => "Nombre y apellidos son requeridos"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("INSERT INTO pos (mypos_id, nombre, apellidos) VALUES (?, ?, ?) ON CONFLICT (mypos_id, id) DO NOTHING");
        $result = $stmt->execute([
            $this->mypos_id,
            $input['nombre'],
            $input['apellidos']
        ]);

        http_response_code(201);
        echo json_encode([
            "status" => 201,
            "message" => "Usuario creado correctamente",
            "data" => ["mypos_id" => $this->mypos_id]
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "message" => "Error: " . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

}
