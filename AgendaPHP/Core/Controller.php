<?php

namespace AgendaPHP\Core;

class Controller {
    
    protected function renderView($view, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../App/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} não encontrada");
        }
        
        include __DIR__ . '/../App/views/parciais/header.php';
        include $viewPath;
        include __DIR__ . '/../App/views/parciais/footer.php';
    }
    
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    protected function verificarAutenticacao() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->setMensagem('error', 'Você precisa estar logado para acessar esta página.');
            $this->redirect('/auth/login');
        }
        
        return true;
    }
    
    protected function setMensagem($tipo, $mensagem) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION[$tipo] = $mensagem;
    }
    
    protected function getMensagem($tipo) {
        if (!isset($_SESSION[$tipo])) {
            return null;
        }
        
        $mensagem = $_SESSION[$tipo];
        unset($_SESSION[$tipo]);
        
        return $mensagem;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function validarCSRF($formName = 'default') {
        if (!isset($_POST['csrf_token']) || 
            !CSRFToken::validarToken($_POST['csrf_token'], $formName)) {
                
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            return false;
        }
        
        return true;
    }
}
?>