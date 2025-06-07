<?php
/**
 * Configuração genérica de banco de dados usando PDO, compatível com MySQL (XAMPP) ou SQLite.
 */

session_start();

$db_driver = 'mysql'; 
// $db_driver = 'sqlite';

if ($db_driver === 'mysql') {
    $host   = 'localhost';
    $dbname = 'agenda';
    $user   = 'root';
    $pass   = '';

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro ao conectar no MySQL: " . $e->getMessage());
    }

} elseif ($db_driver === 'sqlite') {
    $databaseFile = __DIR__ . '/../database/agenda.sqlite';

    if (!file_exists($databaseFile)) {
        if (!is_dir(__DIR__ . '/../database')) {
            mkdir(__DIR__ . '/../database', 0755, true);
        }
        touch($databaseFile);
    }

    $dsn = "sqlite:{$databaseFile}";

    try {
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                email TEXT UNIQUE,
                senha TEXT,
                cpf TEXT,
                data_nasc DATE
            );
            CREATE TABLE IF NOT EXISTS tarefas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER,
                descricao TEXT,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
            );
            CREATE TABLE IF NOT EXISTS contatos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER,
                nome TEXT,
                telefone TEXT,
                email TEXT,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
            );
            CREATE TABLE IF NOT EXISTS compromissos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER,
                titulo TEXT,
                data DATE,
                horario TEXT,
                local TEXT,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
            );
        ");
    } catch (PDOException $e) {
        die("Erro ao conectar no SQLite: " . $e->getMessage());
    }

} else {
    die("Driver inválido definido em database.php. Use 'mysql' ou 'sqlite'.");
}
?>