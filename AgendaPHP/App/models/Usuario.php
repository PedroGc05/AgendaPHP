<?php

namespace AgendaPHP\App\Models;

class Usuario {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function buscar($id) {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, cpf, data_nasc, ultimo_login, data_cadastro FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("
            UPDATE usuarios 
            SET nome = ?, email = ?, cpf = ?, data_nasc = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $dados['nome'], 
            $dados['email'], 
            $dados['cpf'], 
            $dados['data_nasc'],
            $id
        ]);
    }
    
    public function emailExisteOutroUsuario($email, $id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    public function verificarSenha($id, $senha) {
        $stmt = $this->pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($resultado && password_verify($senha, $resultado['senha'])) {
            return true;
        }
        
        return false;
    }
    
    public function alterarSenha($id, $novaSenha) {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        return $stmt->execute([$senhaHash, $id]);
    }
    
    public function listarContatos($id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total_contatos
            FROM contatos
            WHERE usuario_id = ?
        ");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total_contatos'] ?? 0;
    }
    
    public function listarGrupos($id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total_grupos
            FROM grupos
            WHERE usuario_id = ?
        ");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total_grupos'] ?? 0;
    }

    public function excluir($id){
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                $this->pdo->commit();
                return true;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>