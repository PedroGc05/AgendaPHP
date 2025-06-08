<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/contato.css">

<div class="container">
    <div class="contacts-header">
        <h1><?= $titulo ?? 'Meus Contatos' ?></h1>
        <div class="actions">
            <a href="/AgendaPHP/AgendaPHP/Public/contato/criar" class="btn">Adicionar Contato</a>
            <a href="/AgendaPHP/AgendaPHP/Public/grupo" class="btn">Gerenciar Grupos</a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($filtro_ativo) && $filtro_ativo): ?>
        <p class="filter-info">
            Exibindo contatos do grupo: <strong><?= $grupo_atual['nome'] ?></strong> 
            <a href="/AgendaPHP/AgendaPHP/Public/contato" class="filter-reset">Mostrar todos</a>
        </p>
    <?php endif; ?>
    
    <?php if (empty($contatos)): ?>
        <p class="no-data">Nenhum contato encontrado.</p>
    <?php else: ?>
        <div class="contacts-list">
            <?php foreach ($contatos as $contato): ?>
                <div class="contact-card">
                    <div class="contact-info">
                        <h3><?= htmlspecialchars($contato['nome']) ?></h3>
                        <p><strong>Telefone:</strong> <?= htmlspecialchars($contato['telefone']) ?></p>
                        <?php if (!empty($contato['email'])): ?>
                            <p><strong>Email:</strong> <?= htmlspecialchars($contato['email']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($contato['grupo_nome'])): ?>
                            <p><strong>Grupo:</strong> <?= htmlspecialchars($contato['grupo_nome']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="contact-actions">
                        <a href="/AgendaPHP/AgendaPHP/Public/contato/editar?id=<?= $contato['id'] ?>" class="btn-edit">Editar</a>
                        <a href="/AgendaPHP/AgendaPHP/Public/contato/excluir?id=<?= $contato['id'] ?>" class="btn-delete" 
                        onclick="return confirm('Tem certeza que deseja excluir este contato?')">Excluir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>