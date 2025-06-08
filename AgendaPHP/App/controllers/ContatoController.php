<?php
namespace AgendaPHP\App\Controllers;

use AgendaPHP\Core\Controller;
use AgendaPHP\App\Models\Contato;
use AgendaPHP\App\Models\Grupo;

class ContatoController extends Controller {
    private $contatoModel;
    private $grupoModel;
    
    public function __construct($pdo) {
        require_once __DIR__ . '/../models/Contato.php';
        require_once __DIR__ . '/../models/Grupo.php';
        
        $this->contatoModel = new Contato($pdo);
        $this->grupoModel = new Grupo($pdo);
    }
    
    public function index() {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $contatos = $this->contatoModel->listar($usuario_id);
        
        $this->renderView('contato/index', [
            'contatos' => $contatos,
            'titulo' => 'Meus Contatos'
        ]);
    }
    
    public function criar() {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $grupos = $this->grupoModel->listar($usuario_id);
        
        $this->renderView('contato/criar', [
            'grupos' => $grupos,
            'titulo' => 'Adicionar Novo Contato'
        ]);
    }
    
    public function salvar() {
        $this->verificarAutenticacao();
        
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        if (!$this->validarCSRF('contato_form')) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato/criar');
            return;
        }
        
        $usuario_id = $_SESSION['usuario_id'];
        
        if (empty($_POST['nome']) || empty($_POST['telefone'])) {
            $this->setMensagem('error', 'Nome e telefone são campos obrigatórios.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato/criar');
            return;
        }
        
        $resultado = $this->contatoModel->adicionar(
            $usuario_id,
            $_POST['nome'],
            $_POST['telefone'],
            !empty($_POST['grupo_id']) ? $_POST['grupo_id'] : null,
            !empty($_POST['email']) ? $_POST['email'] : null
        );
        
        if ($resultado) {
            $this->setMensagem('success', 'Contato adicionado com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao adicionar contato.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
    }
    
    public function editar($id) {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $contato = $this->contatoModel->buscar($id);
        
        if (!$contato || $contato['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Contato não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        $grupos = $this->grupoModel->listar($usuario_id);
        
        $this->renderView('contato/editar', [
            'contato' => $contato,
            'grupos' => $grupos,
            'titulo' => 'Editar Contato'
        ]);
    }
    
    public function atualizar() {
        $this->verificarAutenticacao();
        
        if (!$this->isPost() || !isset($_POST['id'])) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        if (!$this->validarCSRF('contato_edit_form')) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        $id = $_POST['id'];
        $usuario_id = $_SESSION['usuario_id'];
        $contato = $this->contatoModel->buscar($id);
        
        if (!$contato || $contato['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Contato não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        if (empty($_POST['nome']) || empty($_POST['telefone'])) {
            $this->setMensagem('error', 'Nome e telefone são campos obrigatórios.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato/editar?id=' . $id);
            return;
        }
        
        $resultado = $this->contatoModel->atualizar(
            $id,
            $_POST['nome'],
            $_POST['telefone'],
            !empty($_POST['grupo_id']) ? $_POST['grupo_id'] : null,
            !empty($_POST['email']) ? $_POST['email'] : null
        );
        
        if ($resultado) {
            $this->setMensagem('success', 'Contato atualizado com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao atualizar contato.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
    }
    
    public function excluir($id) {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $contato = $this->contatoModel->buscar($id);
        
        if (!$contato || $contato['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Contato não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        $resultado = $this->contatoModel->excluir($id);
        
        if ($resultado) {
            $this->setMensagem('success', 'Contato excluído com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao excluir contato.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
    }
    
    public function filtrarGrupo($grupo_id) {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $contatos = $this->contatoModel->listarPorGrupo($usuario_id, $grupo_id);
        $grupo = $this->grupoModel->buscar($grupo_id);
        
        if (!$grupo || $grupo['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Grupo não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/contato');
            return;
        }
        
        $this->renderView('contato/index', [
            'contatos' => $contatos,
            'titulo' => 'Contatos: ' . $grupo['nome'],
            'filtro_ativo' => true,
            'grupo_atual' => $grupo
        ]);
    }
}
?>