<?php

class Ecommerce {
    public function getUsuarios(){
        http_response_code(200);
        print(json_encode([
            "status" => 200,
            "message" => "usuarios",
            "data" => [
                "nombre" => "lsls",
                "apellidos" => "lsls"
            ]
        ]));
    }
    public function setUsuario(){
        //no sé qué hacer

    }
}