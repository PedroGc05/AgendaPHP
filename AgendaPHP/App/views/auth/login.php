<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/auth.css">

<div class="container">
    <div class="auth-form">
        <h1>Login</h1>
        
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
        
        <form action="/AgendaPHP/AgendaPHP/Public/auth/autenticar" method="POST">
            <?= \AgendaPHP\Core\CSRFToken::campoFormulario('login_form') ?>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Entrar</button>
            </div>
        </form>
        
        <div class="auth-links">
            <a href="/AgendaPHP/AgendaPHP/Public/auth/cadastro">Não tem conta? Cadastre-se</a>
        </div>
    </div>
</div>