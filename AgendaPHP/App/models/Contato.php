<?php

namespace AgendaPHP\App\Models;
class Contato {
    private $pdo;
    
    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }

    public function listar($usuario_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, g.nome as grupo_nome 
            FROM contatos c
            LEFT JOIN grupos g ON c.grupo_id = g.id
            WHERE c.usuario_id = ?
            ORDER BY c.nome ASC
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarPorGrupo($usuario_id, $grupo_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM contatos 
            WHERE usuario_id = ? AND grupo_id = ?
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuario_id, $grupo_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function adicionar($usuario_id, $nome, $telefone, $grupo_id = null, $email = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO contatos 
            (usuario_id, nome, telefone, grupo_id, email) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$usuario_id, $nome, $telefone, $grupo_id, $email]);
    }

    public function atualizar($id, $nome, $telefone, $grupo_id = null, $email = null) {
        $stmt = $this->pdo->prepare("
            UPDATE contatos 
            SET nome = ?, 
                telefone = ?, 
                grupo_id = ?, 
                email = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nome, $telefone, $grupo_id, $email, $id]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM contatos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function buscar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM contatos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>