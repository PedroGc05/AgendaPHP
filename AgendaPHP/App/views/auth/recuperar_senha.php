<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/auth.css">

<main>
    <section class="recuperar-senha-container">
        <h2>Recuperar Senha</h2>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if (!empty($sucesso)): ?>
            <div class="sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>
        <form action="/AgendaPHP/AgendaPHP/Public/auth/recuperar-senha" method="POST">
            <?php if (class_exists('AgendaPHP\\Core\\CSRFToken')): ?>
                <?= \AgendaPHP\Core\CSRFToken::campoFormulario('recuperar_senha_form') ?>
            <?php endif; ?>
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" required placeholder="Digite seu CPF">
            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" name="data_nasc" id="data_nasc" required>
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" name="nova_senha" id="nova_senha" required>
            <label for="confirmar_senha">Confirmar Nova Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required>
            <button type="submit">Recuperar Senha</button>
        </form>
        <aside>
            <a href="/AgendaPHP/AgendaPHP/Public/auth/login">Voltar ao Login</a>
        </aside>
    </section>
</main>

<style>
.recuperar-senha-container {
    max-width: 400px;
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
input[type="text"], input[type="date"], input[type="password"] {
    padding: 8px;
    width: 100%;
    margin-bottom: 16px;
}
button {
    padding: 8px 16px;
    margin-right: 10px;
}
.sucesso {
    color: #080;
    margin-bottom: 16px;
}
.erro {
    color: #b00;
    margin-bottom: 16px;
}
</style>
