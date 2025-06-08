<?php

namespace AgendaPHP\Core;

class Auth {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function login($email, $senha) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            $this->atualizarLoginData($usuario['id']);
            
            return $usuario;
        }
        
        return false;
    }
    
    public function registrar($nome, $email, $senha, $cpf = null, $data_nasc = null) {
        if ($this->emailExiste($email)) {
            return false;
        }
        
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, cpf, data_nasc) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([$nome, $email, $senhaHash, $cpf, $data_nasc]);
        
        if ($result) {
            return $this->login($email, $senha);
        }
        
        return false;
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_nome']);
        unset($_SESSION['usuario_email']);
        
        session_destroy();
    }
    
    public function estaLogado() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['usuario_id']);
    }
    
    public function emailExiste($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        return (int)$stmt->fetchColumn() > 0;
    }
    
    private function atualizarLoginData($usuarioId) {
        $stmt = $this->pdo->prepare("
            UPDATE usuarios 
            SET ultimo_login = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$usuarioId]);
    }
    
    public function recuperarSenha($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return false;
        }
        
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', time() + 3600);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO recuperacao_senha (usuario_id, token, expira) 
            VALUES (?, ?, ?)
        ");
        $resultado = $stmt->execute([$usuario['id'], $token, $expira]);
        
        if ($resultado) {
            return true;
        }
        
        return false;
    }
}
?>