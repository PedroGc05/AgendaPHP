<?php 

class Router {
    private $routes = [];
    private $pdo; // Add this property declaration

    public function __construct($pdo) {
        $this->pdo = $pdo;

        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(__DIR__));
        }

        if (!defined('APP_PATH')) {
            define('APP_PATH', ROOT_PATH . '/App');
        }

        $this->addRoute('/Home', function () {
            require_once APP_PATH . '/controllers/HomeController.php';
            $controller = new \AgendaPHP\App\Controllers\HomeController($this->pdo);
            $controller->index();
        });

        $this->addRoute('/contato', function(){
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->index();
        });

        $this->addRoute('/contato/criar', function () {
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->criar();
        });

        $this->addRoute('/contato/salvar', function () {
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->salvar();
        });

        $this->addRoute('/contato/editar', function () {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->editar($id);
        });

        $this->addRoute('/contato/atualizar', function () {
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->atualizar();
        });

        $this->addRoute('/contato/excluir', function () {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->excluir($id);
        });

        $this->addRoute('/contato/grupo', function () {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            require_once APP_PATH . '/controllers/ContatoController.php';
            $controller = new \AgendaPHP\App\Controllers\ContatoController($this->pdo);
            $controller->filtrarGrupo($id);
        });

        $this->addRoute('/sobre', function () {
            require_once APP_PATH . '/controllers/HomeController.php';
            $controller = new \AgendaPHP\App\Controllers\HomeController($this->pdo);
            $controller->sobre();
        });

        $this->addRoute('/fale-conosco', function () {
            require_once APP_PATH . '/controllers/HomeController.php';
            $controller = new \AgendaPHP\App\Controllers\HomeController($this->pdo);
            $controller->contato();
        });


        $this->addRoute('/auth/login', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->login();
        });

        $this->addRoute('/auth/autenticar', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->autenticar();
        });

        $this->addRoute('/auth/cadastro', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->cadastro();
        });

        $this->addRoute('/auth/registrar', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->registrar();
        });

        $this->addRoute('/auth/logout', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->logout();
        });

        $this->addRoute('/auth/recuperar-senha', function () {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controller = new \AgendaPHP\App\Controllers\AuthController($this->pdo);
            $controller->recuperarSenha();
        });

        $this->addRoute('/grupo', function () {
            require_once APP_PATH . '/controllers/GrupoController.php';
            $controller = new \AgendaPHP\App\Controllers\GrupoController($this->pdo);
            $controller->index();
        });

        $this->addRoute('/grupo/criar', function () {
            require_once APP_PATH . '/controllers/GrupoController.php';
            $controller = new \AgendaPHP\App\Controllers\GrupoController($this->pdo);
            $controller->criar();
        });

        $this->addRoute('/grupo/salvar', function () {
            require_once APP_PATH . '/controllers/GrupoController.php';
            $controller = new \AgendaPHP\App\Controllers\GrupoController($this->pdo);
            $controller->salvar();
        });

        $this->addRoute('/grupo/editar', function () {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            require_once APP_PATH . '/controllers/GrupoController.php';
            $controller = new \AgendaPHP\App\Controllers\GrupoController($this->pdo);
            $controller->editar($id);
        });

        $this->addRoute('/grupo/excluir', function () {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            require_once APP_PATH . '/controllers/GrupoController.php';
            $controller = new \AgendaPHP\App\Controllers\GrupoController($this->pdo);
            $controller->excluir($id);
        });

        $this->addRoute('/usuario/perfil', function () {
            require_once APP_PATH . '/controllers/UsuarioController.php';
            $controller = new \AgendaPHP\App\Controllers\UsuarioController($this->pdo);
            $controller->perfil();
        });

        $this->addRoute('/usuario/atualizar', function () {
            require_once APP_PATH . '/controllers/UsuarioController.php';
            $controller = new \AgendaPHP\App\Controllers\UsuarioController($this->pdo);
            $controller->atualizar();
        });

        $this->addRoute('/usuario/alterarSenha', function () {
            require_once APP_PATH . '/controllers/UsuarioController.php';
            $controller = new \AgendaPHP\App\Controllers\UsuarioController($this->pdo);
            $controller->alterarSenha();
        });

    }

    public function addRoute($url, $handler)
    {
        $this->routes[$url] = $handler;
    }

    public function dispatch()
    {
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        $basePath = '/AgendaPHP/AgendaPHP/Public';
        if (strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }

        if (empty($url)) {
            $url = '/';
        }

        if ($pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }

        if (isset($this->routes[$url])) {
            $handler = $this->routes[$url];
            if (is_callable($handler)) {
                call_user_func($handler);
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Página não encontrada</h1>";
        echo "<p>A página que você está procurando não existe.</p>";
        echo "<p><a href='/AgendaPHP/AgendaPHP/Public/Home'>Voltar para a página inicial</a></p>";
    }

}

?>