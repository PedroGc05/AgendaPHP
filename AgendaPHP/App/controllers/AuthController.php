<?php

namespace AgendaPHP\App\Controllers;

use AgendaPHP\Core\Controller;
use AgendaPHP\Core\Auth;
use AgendaPHP\Core\CSRFToken;

class AuthController extends Controller {
    private $auth;
    
    public function __construct($pdo) {
        $this->auth = new \AgendaPHP\Core\Auth($pdo);
    }
    
    public function login() {
        if ($this->auth->estaLogado()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
            return;
        }
        
        $this->renderView('auth/login', [
            'titulo' => 'Login'
        ]);
    }

    public function autenticar()
    {
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/login');
            return;
        }

        if (!$this->validarCSRF('login_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/login');
            return;
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->setMensagem('error', 'Por favor, preencha todos os campos.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/login');
            return;
        }

        $usuario = $this->auth->login($email, $senha);

        if ($usuario) {
            $this->setMensagem('success', 'Login realizado com sucesso!');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
        } else {
            $this->setMensagem('error', 'Email ou senha inválidos.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/login');
        }
    }
    
    public function cadastro() {
        if ($this->auth->estaLogado()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
            return;
        }
        
        $this->renderView('auth/cadastro', [
            'titulo' => 'Cadastro'
        ]);
    }
    
    public function registrar() {
        if (!$this->isPost()) {
            $this->redirect('auth/cadastro');
            return;
        }
        
        if (!$this->validarCSRF('cadastro_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('auth/cadastro');
            return;
        }
        
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        $cpf = $_POST['cpf'] ?? null;
        $data_nasc = $_POST['data_nasc'] ?? null;
        
        if (empty($nome) || empty($email) || empty($senha)) {
            $this->setMensagem('error', 'Por favor, preencha todos os campos obrigatórios.');
            $this->redirect('auth/cadastro');
            return;
        }
        
        if ($senha !== $confirmarSenha) {
            $this->setMensagem('error', 'As senhas não coincidem.');
            $this->redirect('auth/cadastro');
            return;
        }
        
        if (strlen($senha) < 6) {
            $this->setMensagem('error', 'A senha deve ter pelo menos 6 caracteres.');
            $this->redirect('auth/cadastro');
            return;
        }
        
        if ($this->auth->emailExiste($email)) {
            $this->setMensagem('error', 'Este email já está em uso.');
            $this->redirect('auth/cadastro');
            return;
        }
        
        $resultado = $this->auth->registrar($nome, $email, $senha, $cpf, $data_nasc);
        
        if ($resultado) {
            $this->setMensagem('success', 'Cadastro realizado com sucesso!');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
        } else {
            $this->setMensagem('error', 'Erro ao cadastrar usuário. Por favor, tente novamente.');
            $this->redirect('auth/cadastro');
        }
    }
    
    public function logout() {
        $this->auth->logout();
        $this->setMensagem('success', 'Logout realizado com sucesso!');
        $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
    }
}
?>