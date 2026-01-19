<?php

require_once "./Database.php";

class Ecommerce 
{
    public string $mypos_id;

    public function __construct(){
        $this->mypos_id = $_COOKIE['mypos_id'] ?? '';
    }

    public function getCustomer()
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
            $stmt = $pdo->prepare("SELECT id, nombre, apellidos FROM pos WHERE mypos_id = ?");
            $stmt->execute([$this->mypos_id]);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            $json["status"] = "ok";
            $json["code"] = 200;
            $json["answer"] = "Usuarios obtenidos correctamente";
            $json["usuarios"] = $usuarios;
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "Error del servidor: " . $e->getMessage();
            return $json;
        }
    }

    public function setCustomer(){
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
            
            if (!$input || !isset($input['nombre']) || !isset($input['apellidos']) || empty(trim($input['nombre'])) || empty(trim($input['apellidos']))) {
                http_response_code(400);
                $json["status"] = "error";
                $json["code"] = 400;
                $json["answer"] = "Nombre y apellidos son requeridos y no pueden estar vacíos";
                return $json;
            }

            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO pos (mypos_id, nombre, apellidos) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $this->mypos_id,
                trim($input['nombre']),
                trim($input['apellidos'])
            ]);

            http_response_code(201);
            $json["status"] = "ok";
            $json["code"] = 201;
            $json["answer"] = "Usuario creado correctamente";
            return $json;

        } catch (Exception $e) {
            http_response_code(500);
            $json["status"] = "error";
            $json["code"] = 500;
            $json["answer"] = "client - " . $e->getCode();
            return $json;
        }
    }
}
