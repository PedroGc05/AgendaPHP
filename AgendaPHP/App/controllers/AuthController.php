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

    public function registrar()
    {
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }

        if (!$this->validarCSRF('cadastro_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $data_nasc = $_POST['data_nasc'] ?? '';

        if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($data_nasc)) {
            $this->setMensagem('error', 'Por favor, preencha todos os campos obrigatórios.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) !== 11) {
            $this->setMensagem('error', 'CPF inválido. Digite 11 números.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }

        if ($senha !== $confirmarSenha) {
            $this->setMensagem('error', 'As senhas não coincidem.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }
        
        if (strlen($senha) < 6) {
            $this->setMensagem('error', 'A senha deve ter pelo menos 6 caracteres.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }
        
        if ($this->auth->emailExiste($email)) {
            $this->setMensagem('error', 'Este email já está em uso.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
            return;
        }
        
        $resultado = $this->auth->registrar($nome, $email, $senha, $cpf, $data_nasc);
        
        if ($resultado) {
            $this->setMensagem('success', 'Cadastro realizado com sucesso!');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
        } else {
            $this->setMensagem('error', 'Erro ao cadastrar usuário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/auth/cadastro');
        }
    }
    
    public function logout() {
        $this->auth->logout();
        $this->setMensagem('success', 'Logout realizado com sucesso!');
        $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
    }
    
    public function recuperarSenha() {
        $erro = $sucesso = '';
        if ($this->isPost()) {
            if (!$this->validarCSRF('recuperar_senha_form')) {
                $erro = 'Erro de validação do formulário.';
            } else {
                $cpf = $_POST['cpf'] ?? '';
                $data_nasc = $_POST['data_nasc'] ?? '';
                $nova_senha = $_POST['nova_senha'] ?? '';
                $confirmar_senha = $_POST['confirmar_senha'] ?? '';
                if (empty($cpf) || empty($data_nasc) || empty($nova_senha) || empty($confirmar_senha)) {
                    $erro = 'Preencha todos os campos.';
                } elseif ($nova_senha !== $confirmar_senha) {
                    $erro = 'As senhas não coincidem.';
                } elseif (strlen($nova_senha) < 6) {
                    $erro = 'A senha deve ter pelo menos 6 caracteres.';
                } else {
                    require_once __DIR__ . '/../models/Usuario.php';
                    global $pdo;
                    $usuarioModel = new \AgendaPHP\App\Models\Usuario($pdo);
                    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cpf = ? AND data_nasc = ?");
                    $stmt->execute([$cpf, $data_nasc]);
                    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
                    if ($usuario) {
                        $usuarioModel->alterarSenha($usuario['id'], $nova_senha);
                        $sucesso = 'Senha redefinida com sucesso!';
                    } else {
                        $erro = 'Dados não conferem. Verifique o CPF e a data de nascimento.';
                    }
                }
            }
        }
        $this->renderView('auth/recuperar_senha', [
            'erro' => $erro,
            'sucesso' => $sucesso
        ]);
    }
}
?>