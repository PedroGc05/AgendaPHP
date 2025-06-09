<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/contato.css">


<section class="container-grupo">
    <h2>Criar novo grupo</h2>    

<?php if (!empty($erro)): ?>
    <div class="erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if (!isset($_SESSION['usuario_id'])): ?>
    <div class="erro">Você não está logado. Faça login para cadastrar e visualizar grupos.</div>
<?php endif; ?>

<form action="/AgendaPHP/AgendaPHP/Public/grupo/salvar" method="POST">
    <?php if (class_exists('AgendaPHP\\Core\\CSRFToken')): ?>
        <?= \AgendaPHP\Core\CSRFToken::campoFormulario('grupo_form') ?>
    <?php endif; ?>
    <label for="nome">Nome do Grupo:</label>
    <input type="text" name="nome" id="nome" required placeholder="Digite o nome do grupo">
    <button type="submit">Criar Grupo</button>
</form>

<hr>

<h3>Grupos Cadastrados</h3>

<div class="lista-grupos">
    <?php if (!empty($grupos)): ?>
        <?php foreach ($grupos as $grupo): ?>
            <article class="grupo-card">
                <strong>ID:</strong> <?= htmlspecialchars($grupo['id']) ?><br>
                <strong>Nome:</strong> <?= htmlspecialchars($grupo['nome']) ?><br>
                <strong>Contatos:</strong> <?= $grupo['total_contatos'] ?>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum grupo cadastrado ainda.</p>
    <?php endif; ?>
</div>
</section>


<style>
    .form-grupo {
        margin-bottom: 20px;
    }

    input[type="text"] {
        padding: 8px;
        width: 300px;
        margin-right: 10px;
    }

    button {
        padding: 8px 16px;
    }

    .lista-grupos {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .grupo-card {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 6px;
        background-color: #f9f9f9;
        max-width: 400px;
    }

</style>

<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/footer.css">