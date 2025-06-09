<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/AgendaPHP/AgendaPHP/Public/css/header.css">
    <title>Agenda Pessoal</title>
</head>
<body>
<header class="navbar nav-red">
    <h1 class="nav-title">Agenda Pessoal</h1>
    <nav class="nav-links">
        <a href="/AgendaPHP/AgendaPHP/Public/Home" class="nav-item-link">Home</a>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="/AgendaPHP/AgendaPHP/Public/contato" class="nav-item-link">Meus Contatos</a>
                    <a href="/AgendaPHP/AgendaPHP/Public/grupo" class="nav-item-link">Meus Grupos</a>
                    <a href="/AgendaPHP/AgendaPHP/Public/usuario/perfil" class="nav-item-link">Meu Perfil</a>
                <?php endif; ?>
    
                <a href="/AgendaPHP/AgendaPHP/Public/sobre" class="nav-item-link">Sobre</a>
                <a href="/AgendaPHP/AgendaPHP/Public/fale-conosco" class="nav-item-link">Fale Conosco</a>
    
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="/AgendaPHP/AgendaPHP/Public/auth/logout" class="nav-item-link">Deslogar</a>
                <?php else: ?>
                    <a href="/AgendaPHP/AgendaPHP/Public/auth/login" class="nav-item-link">Login</a>
                <?php endif; ?>
            </nav>
        </header>