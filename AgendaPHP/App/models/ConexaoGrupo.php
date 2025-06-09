<?php
namespace AgendaPHP\App\Models;

class Conexao {
    public static function conectar() {
        return new \PDO('mysql:host=localhost;dbname=agenda', 'usuario', 'senha');
    }
}