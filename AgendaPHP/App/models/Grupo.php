<?php

namespace AgendaPHP\App\Models;
class Grupo {
    private $pdo;
    public $nome;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function listar($usuario_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM grupos 
            WHERE usuario_id = ? 
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function adicionar($usuario_id, $nome) {
        $stmt = $this->pdo->prepare("
            INSERT INTO grupos (usuario_id, nome)
            VALUES (?, ?)
        ");
        return $stmt->execute([$usuario_id, $nome]);
    }
    
    public function atualizar($id, $nome) {
        $stmt = $this->pdo->prepare("
            UPDATE grupos 
            SET nome = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nome, $id]);
    }
    
    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM grupos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function buscar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function contarContatosPorGrupo($grupo_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total 
            FROM contatos 
            WHERE grupo_id = ?
        ");
        $stmt->execute([$grupo_id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function salvar()
{
    $pdo = Conexao::conectar(); 
    $sql = "INSERT INTO grupos (nome) VALUES (:nome)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $this->nome, \PDO::PARAM_STR);
    return $stmt->execute();
}
}
?>
