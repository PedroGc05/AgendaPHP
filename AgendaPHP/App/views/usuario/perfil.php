<?php
use AgendaPHP\Core\CSRFToken;
?>

<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/perfil.css">

<div class="container">
    <div class="profile-container">
        <h1>Meu Perfil</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-section">
            <h2>Informações Pessoais</h2>
            <form action="/AgendaPHP/AgendaPHP/Public/usuario/atualizar" method="POST">
                <?= CSRFToken::campoFormulario('perfil_form') ?>
                
                <div class="form-group">
                    <label for="nome">Nome Completo*</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($usuario['cpf'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="data_nasc">Data de Nascimento</label>
                    <input type="date" id="data_nasc" name="data_nasc" value="<?= $usuario['data_nasc'] ?? '' ?>">
                </div>
                
                <div class="form-group">
                    <label>Data de Cadastro</label>
                    <p class="profile-info">
                        <?= date('d/m/Y H:i', strtotime($usuario['data_cadastro'])) ?>
                    </p>
                </div>
                
                <?php if (!empty($usuario['ultimo_login'])): ?>
                <div class="form-group">
                    <label>Último Acesso</label>
                    <p class="profile-info">
                        <?= date('d/m/Y H:i', strtotime($usuario['ultimo_login'])) ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn">Atualizar Informações</button>
                </div>
            </form>
        </div>
        
        <div class="profile-section">
            <h2>Alterar Senha</h2>
            <form action="/AgendaPHP/AgendaPHP/Public/usuario/alterarSenha" method="POST">
                <?= CSRFToken::campoFormulario('senha_form') ?>
                
                <div class="form-group">
                    <label for="senha_atual">Senha Atual*</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                
                <div class="form-group">
                    <label for="nova_senha">Nova Senha*</label>
                    <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                    <small class="form-hint">Mínimo de 6 caracteres</small>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha*</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn">Alterar Senha</button>
                </div>
            </form>
        </div>

        <div class="profile-section danger-zone">
            <div class="warning-box">
                <h3>Excluir Conta</h3>
                <p>Atenção: Esta ação não pode ser desfeita. Todos os seus dados, contatos e grupos serão permanentemente excluídos.</p>
                
                <form action="/AgendaPHP/AgendaPHP/Public/usuario/excluir-conta" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')">
                    <?= CSRFToken::campoFormulario('excluir_conta_form') ?>
        
                    <div class="form-group">
                        <label for="senha_confirmacao">Digite sua senha para confirmar*</label>
                        <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
                    </div>
        
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">Excluir Minha Conta</button>
                    </div>
                </form>
            </div>
        </div>
                
        <div class="profile-section">
            <h2>Estatísticas da Conta</h2>
            
            <?php
            require_once __DIR__ . '/../../models/Contato.php';
            require_once __DIR__ . '/../../models/Grupo.php';
            
            $contatoModel = new \AgendaPHP\App\Models\Contato($GLOBALS['pdo']);
            $grupoModel = new \AgendaPHP\App\Models\Grupo($GLOBALS['pdo']);
            
            $contatos = $contatoModel->listar($usuario['id']);
            $grupos = $grupoModel->listar($usuario['id']);
            
            $totalContatos = count($contatos);
            $totalGrupos = count($grupos);
            ?>
            
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-label">Total de contatos</span>
                    <span class="stat-value"><?= $totalContatos ?></span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Total de grupos</span>
                    <span class="stat-value"><?= $totalGrupos ?></span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Dias na plataforma</span>
                    <span class="stat-value">
                        <?= ceil((time() - strtotime($usuario['data_cadastro'])) / (60*60*24)) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-20">
            <a href="/AgendaPHP/AgendaPHP/Public/contato" class="btn">Gerenciar Contatos</a>
            <a href="/AgendaPHP/AgendaPHP/Public/grupo" class="btn">Gerenciar Grupos</a>
            <a href="/AgendaPHP/AgendaPHP/Public/Home" class="btn btn-secondary">Voltar para Home</a>
        </div>
    </div>
</div>