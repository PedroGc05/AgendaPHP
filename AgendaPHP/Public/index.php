<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/App');
define('CONFIG_PATH', ROOT_PATH . '/Config');
define('CORE_PATH', ROOT_PATH . '/Core');

spl_autoload_register(function($className) {
    $className = str_replace('AgendaPHP\\', '', $className);
    $className = str_replace('\\', '/', $className);
    $filePath = ROOT_PATH . '/' . $className . '.php';
    
    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }
    return false;
});

require_once CONFIG_PATH . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

\AgendaPHP\Core\CSRFToken::limparToken();

try {
    require_once CORE_PATH . '/Router.php';
    $router = new Router($pdo);
    $router->dispatch();
} catch (Exception $e) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Erro na aplicação: ' . $e->getMessage()
        ]);
    } else {
        http_response_code(500);
        echo '<h1>Erro na aplicação</h1>';
        echo '<p>' . $e->getMessage() . '</p>';
        
        if (true) {
            echo '<h2>Detalhes do erro:</h2>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }
}
?>