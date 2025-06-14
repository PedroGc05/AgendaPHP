<?php
namespace AgendaPHP\App\Controllers;

use AgendaPHP\Core\Controller;
use AgendaPHP\App\Models\Grupo;

class GrupoController extends Controller {
    private $grupoModel;
    
    public function __construct($pdo) {
        require_once __DIR__ . '/../models/Grupo.php';
        $this->grupoModel = new \AgendaPHP\App\Models\Grupo($pdo);
    }
    
    public function index() {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $grupos = $this->grupoModel->listar($usuario_id);
        
        foreach ($grupos as &$grupo) {
            $grupo['total_contatos'] = $this->grupoModel->contarContatosPorGrupo($grupo['id']);
        }
        
        $this->renderView('grupo/index', [
            'grupos' => $grupos,
            'titulo' => 'Meus Grupos'
        ]);
    }
    
    public function criar() {
    $this->verificarAutenticacao();

    $usuario_id = $_SESSION['usuario_id'];

    $grupos = $this->grupoModel->listar($usuario_id);

    foreach ($grupos as &$grupo) {
        $grupo['total_contatos'] = $this->grupoModel->contarContatosPorGrupo($grupo['id']);
    }

    $this->renderView('grupo/criar', [
        'titulo' => 'Adicionar Novo Grupo',
        'grupos' => $grupos 
    ]);
    }
    
    public function salvar() {
        $this->verificarAutenticacao();
        
        if (!$this->isPost()) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo');
            return;
        }
        
        if (!$this->validarCSRF('grupo_form')) {
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo/criar');
            return;
        }
        
        $usuario_id = $_SESSION['usuario_id'];
        
        if (empty($_POST['nome'])) {
            $this->setMensagem('error', 'Nome do grupo é obrigatório.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo/criar');
            return;
        }
        
        $resultado = $this->grupoModel->adicionar(
            $usuario_id,
            $_POST['nome']
        );
        
        if ($resultado) {
            $this->setMensagem('success', 'Grupo adicionado com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao adicionar grupo.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo/criar');
    }
    
    public function excluir($id) {
        $this->verificarAutenticacao();
        
        $usuario_id = $_SESSION['usuario_id'];
        $grupo = $this->grupoModel->buscar($id);
        
        if (!$grupo || $grupo['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Grupo não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo');
            return;
        }
        
        $resultado = $this->grupoModel->excluir($id);
        
        if ($resultado) {
            $this->setMensagem('success', 'Grupo excluído com sucesso!');
        } else {
            $this->setMensagem('error', 'Erro ao excluir grupo.');
        }
        
        $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo');
    }
    
    public function editar($id) {
        $this->verificarAutenticacao();
        $usuario_id = $_SESSION['usuario_id'];
        $grupo = $this->grupoModel->buscar($id);
        if (!$grupo || $grupo['usuario_id'] != $usuario_id) {
            $this->setMensagem('error', 'Grupo não encontrado.');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo');
            return;
        }
        if ($this->isPost()) {
            if (!$this->validarCSRF('grupo_edit_form')) {
                $this->setMensagem('error', 'Erro de validação do formulário.');
                $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo/editar?id=' . $id);
                return;
            }
            $novoNome = $_POST['nome'] ?? '';
            if (empty($novoNome)) {
                $this->setMensagem('error', 'O nome do grupo não pode ser vazio.');
                $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo/editar?id=' . $id);
                return;
            }
            $this->grupoModel->atualizar($id, $novoNome);
            $this->setMensagem('success', 'Grupo atualizado com sucesso!');
            $this->redirect('/AgendaPHP/AgendaPHP/Public/grupo');
            return;
        }
        $this->renderView('grupo/editar', [
            'grupo' => $grupo,
            'titulo' => 'Editar Grupo'
        ]);
    }
}
?>