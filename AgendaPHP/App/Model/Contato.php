<?php
class Contato {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function listar($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM contatos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionar($usuario_id, $nome, $telefone, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO contatos (usuario_id, nome, telefone, email) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$usuario_id, $nome, $telefone, $email]);
    }

    public function atualizar($id, $nome, $telefone, $email) {
        $stmt = $this->pdo->prepare("UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nome, $telefone, $email, $id]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM contatos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>