<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <title>Agenda Pessoal</title>
</head>
<body>
<header class="navbar nav-red">
    <h1 class="nav-title">Agenda Pessoal</h1>
    <nav class="nav-links">
        <a href="/Home" class="nav-item-link">Home</a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="deslogar.php" class="nav-item-link">Deslogar</a>
        <?php else: ?>
            <a href="login.php" class="nav-item-link">Login</a>
        <?php endif; ?>
    </nav>
</header>
</body>
</html>