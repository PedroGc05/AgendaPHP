<?php
namespace AgendaPHP\App\Controllers;

use AgendaPHP\Core\Controller;

class HomeController extends Controller {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function index() {
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $userData = [];
        
        if ($isLoggedIn) {
            $userData = [
                'nome' => $_SESSION['usuario_nome'] ?? 'Usuário'
            ];
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM contatos WHERE usuario_id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $totalContatos = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            $userData['totalContatos'] = $totalContatos;
        }
        
        $this->renderView('public/index', [
            'titulo' => 'Bem-vindo à Agenda PHP',
            'isLoggedIn' => $isLoggedIn,
            'userData' => $userData
        ]);
    }
    
    public function sobre() {
        $this->renderView('public/sobre', [
            'titulo' => 'Sobre a Agenda PHP'
        ]);
    }
    
    public function contato() {
        $this->renderView('public/contato', [
            'titulo' => 'Fale Conosco'
        ]);
    }
}
?>