<link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/contato.css">

<div class="container">
    <div class="form-container">
        <h1>Adicionar Novo Contato</h1>
        
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
        
        <form action="/AgendaPHP/AgendaPHP/Public/contato/salvar" method="POST">
            <?= \AgendaPHP\Core\CSRFToken::campoFormulario('contato_form') ?>
            
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone*</label>
                <input type="tel" id="telefone" name="telefone" required 
                    pattern="[0-9()\- ]+" title="Digite apenas números, parênteses, traços e espaços">
                <small class="form-hint">Formato: (99) 99999-9999</small>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
            
            <div class="form-group">
                <label for="grupo_id">Grupo</label>
                <select name="grupo_id" id="grupo_id">
                    <option value="">Selecione um grupo (opcional)</option>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?= $grupo['id'] ?>"><?= $grupo['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Salvar Contato</button>
                <a href="/AgendaPHP/AgendaPHP/Public/contato" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>