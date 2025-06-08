<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/public.css">

<div class="container">
    <div class="contato-container">
        <h1>Fale Conosco</h1>
        
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
        
        <div class="contato-info">
            <p>Tem alguma dúvida, sugestão ou problema com a aplicação? Entre em contato conosco!</p>
            <p>Nossa equipe terá prazer em ajudar.</p>
        </div>
        
        <form action="/enviar-mensagem" method="POST" class="contato-form">
            <?= \AgendaPHP\Core\CSRFToken::campoFormulario('contato_site_form') ?>
            
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="assunto">Assunto*</label>
                <input type="text" id="assunto" name="assunto" required>
            </div>
            
            <div class="form-group">
                <label for="mensagem">Mensagem*</label>
                <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Enviar Mensagem</button>
            </div>
        </form>
        
        <div class="contato-alternativo">
            <h2>Outros canais</h2>
            <p><strong>Email:</strong> contato@agendaphp.com</p>
            <p><strong>Telefone:</strong> (99) 9999-9999</p>
        </div>
    </div>
</div>