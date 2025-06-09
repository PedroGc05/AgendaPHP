<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/auth.css">

<div class="container">
    <div class="auth-form">
        <h1>Cadastro</h1>
        
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
        
        <form action="/AgendaPHP/AgendaPHP/Public/auth/registrar" method="POST">
            <?= \AgendaPHP\Core\CSRFToken::campoFormulario('cadastro_form') ?>
            
            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha*</label>
                <input type="password" id="senha" name="senha" required 
                       minlength="6" title="A senha deve ter pelo menos 6 caracteres">
                <small class="form-hint">Mínimo de 6 caracteres</small>
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha*</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            
            <div class="form-group">
                <label for="cpf">CPF*</label>
                <input type="text" id="cpf" name="cpf" required>
                <small class="form-hint">Digite apenas números</small>
            </div>
            
            <div class="form-group">
                <label for="data_nasc">Data de Nascimento*</label>
                <input type="date" id="data_nasc" name="data_nasc" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Cadastrar</button>
            </div>
        </form>
        
        <div class="auth-links">
            <a href="/AgendaPHP/AgendaPHP/Public/auth/login">Já possui conta? Faça login</a>
        </div>
    </div>
</div>