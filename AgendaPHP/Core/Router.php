<?php 
if ($_SERVER['REQUEST_URI'] === '/Home') {
    include __DIR__ . '/../App/views/public/index.php';
    exit;
}

?>