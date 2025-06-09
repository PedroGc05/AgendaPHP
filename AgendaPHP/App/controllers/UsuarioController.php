<?php
namespace AgendaPHP\App\Controllers;

use AgendaPHP\Core\Controller;

class UsuarioController extends Controller {
    private $usuarioModel;
    private $pdo;
    
    public function __construct($pdo) {
        require_once __DIR__ . '/../models/Usuario.php';
        $this->usuarioModel = new \AgendaPHP\App\Models\Usuario($pdo);
        $this->pdo = $pdo;
    }
    
    public function perfil() {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $usuario = $this->usuarioModel->buscar($usuario_id);
        
        if (!$usuario) {
            $this->setMensagem('error', 'Usuário não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
            return;
        }
        
        $this->renderView('usuario/perfil', [
            'usuario' => $usuario,
            'titulo' => 'Meu Perfil'
        ]);
    }
    
    public function atualizar() {
        $this->verificarAutenticacao();
        
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if (!$this->validarCSRF('perfil_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        $usuario_id = $_SESSION['usuario_id'];
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $cpf = $_POST['cpf'] ?? null;
        $data_nasc = $_POST['data_nasc'] ?? null;
        
        if (empty($nome) || empty($email)) {
            $this->setMensagem('error', 'Nome e email são campos obrigatórios.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if ($this->usuarioModel->emailExisteOutroUsuario($email, $usuario_id)) {
            $this->setMensagem('error', 'Este email já está em uso por outro usuário.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        $resultado = $this->usuarioModel->atualizar($usuario_id, [
            'nome' => $nome,
            'email' => $email,
            'cpf' => $cpf,
            'data_nasc' => $data_nasc
        ]);
        
        if ($resultado) {
            // Atualizar a sessão
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            
            $this->setMensagem('success', 'Perfil atualizado com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao atualizar perfil.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
    }
    
    public function alterarSenha() {
        $this->verificarAutenticacao();
        
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if (!$this->validarCSRF('senha_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        $usuario_id = $_SESSION['usuario_id'];
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';
        
        if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
            $this->setMensagem('error', 'Todos os campos de senha são obrigatórios.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if ($nova_senha !== $confirmar_senha) {
            $this->setMensagem('error', 'A nova senha e a confirmação não coincidem.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if (strlen($nova_senha) < 6) {
            $this->setMensagem('error', 'A nova senha deve ter pelo menos 6 caracteres.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        if (!$this->usuarioModel->verificarSenha($usuario_id, $senha_atual)) {
            $this->setMensagem('error', 'A senha atual está incorreta.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }
        
        $resultado = $this->usuarioModel->alterarSenha($usuario_id, $nova_senha);

        if ($resultado) {
            $this->setMensagem('success', 'Senha alterada com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao alterar a senha.');
        }

        $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
    }

    public function excluirConta()
    {
        $this->verificarAutenticacao();

        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }

        if (!$this->validarCSRF('excluir_conta_form')) {
            $this->setMensagem('error', 'Erro de validação do formulário. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $senha = $_POST['senha_confirmacao'] ?? '';

        if (empty($senha)) {
            $this->setMensagem('error', 'Por favor, confirme sua senha para excluir a conta.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
            return;
        }

        // Verificar se a senha está correta
        if (!$this->usuarioModel->verificarSenha($usuario_id, $senha)) {
            $this->setMensagem('error', 'Senha incorreta. A exclusão da conta não foi realizada.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil'); // Corrigido: redireciona de volta ao perfil
            return;
        }

        $resultado = $this->usuarioModel->excluir($usuario_id);

        if ($resultado) {
            session_start();
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['success'] = 'Sua conta foi excluída com sucesso.';
            $this->redirect('/AgendaPHP/AgendaPHP/Public/Home');
        } else {
            $this->setMensagem('error', 'Ocorreu um erro ao excluir a conta. Por favor, tente novamente.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/usuario/perfil');
        }
    }
}
?>