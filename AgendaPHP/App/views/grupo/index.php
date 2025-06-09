<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/grupo.css">

<main>
    <section class="container">
        <div class="groups-header">
            <h1><?= $titulo ?? 'Meus Grupos' ?></h1>
            <div class="actions">
                <a href="/AgendaPHP/AgendaPHP/Public/grupo/criar" class="btn">Adicionar Grupo</a>
                <a href="/AgendaPHP/AgendaPHP/Public/contato" class="btn btn-secondary">Voltar para Contatos</a>
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
        
        <?php if (empty($grupos)): ?>
            <p class="no-data">Você ainda não possui grupos cadastrados.</p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="/AgendaPHP/AgendaPHP/Public/grupo/criar" class="btn">Criar Primeiro Grupo</a>
            </div>
        <?php else: ?>
            <section class="groups-list">
                <?php foreach ($grupos as $grupo): ?>
                    <article class="group-card">
                        <div class="group-info">
                            <h3><?= htmlspecialchars($grupo['nome']) ?></h3>
                            
                            <div class="group-stats">
                                <p><strong>Contatos no grupo:</strong> <span class="total-contacts"><?= $grupo['total_contatos'] ?></span></p>
                            </div>
                        </div>
                        <aside class="group-actions">
                            <a href="/AgendaPHP/AgendaPHP/Public/contato/grupo?id=<?= $grupo['id'] ?>" class="btn-view">Ver Contatos</a>
                            <a href="/AgendaPHP/AgendaPHP/Public/grupo/editar?id=<?= $grupo['id'] ?>" class="btn-edit">Editar</a>
                            <a href="/AgendaPHP/AgendaPHP/Public/grupo/excluir?id=<?= $grupo['id'] ?>" class="btn-delete" 
                               onclick="return confirm('Tem certeza que deseja excluir este grupo? Os contatos não serão excluídos.')">Excluir</a>
                        </aside>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </section>
</main>