<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/contato.css">

<main>
    <section class="container-grupo">
        <h2>Editar Grupo</h2>

        <?php if (!empty($erro)): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form action="/AgendaPHP/AgendaPHP/Public/grupo/editar?id=<?= htmlspecialchars($grupo['id']) ?>" method="POST">
            <?php if (class_exists('AgendaPHP\\Core\\CSRFToken')): ?>
                <?= \AgendaPHP\Core\CSRFToken::campoFormulario('grupo_edit_form') ?>
            <?php endif; ?>
            <label for="nome">Nome do Grupo:</label>
            <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($grupo['nome']) ?>">
            <button type="submit">Salvar Alterações</button>
            <a href="/AgendaPHP/AgendaPHP/Public/grupo" class="btn btn-secondary">Cancelar</a>
        </form>
    </section>
</main>

<style>
    .container-grupo {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    label {
        display: block;
        margin-bottom: 8px;
    }
    input[type="text"] {
        padding: 8px;
        width: 100%;
        margin-bottom: 16px;
    }
    button {
        padding: 8px 16px;
        margin-right: 10px;
    }
    .btn-secondary {
        background: #ccc;
        color: #333;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
    }
    .erro {
        color: #b00;
        margin-bottom: 16px;
    }
</style>

<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/footer.css">
