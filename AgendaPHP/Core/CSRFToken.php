<?php

namespace AgendaPHP\Core;

class CSRFToken {

    public static function gerarToken($formName = 'default') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));

        $_SESSION['csrf_tokens'][$formName] = [
            'token' => $token,
            'time' => time() 
        ];

        return $token;
    }

    public static function validarToken($token, $formName = 'default', $duracaoTempo = 3600) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_tokens'][$formName])) {
            return false;
        }

        $storedToken = $_SESSION['csrf_tokens'][$formName]['token'];
        $tokenTime = $_SESSION['csrf_tokens'][$formName]['time'];

        if (time() - $tokenTime > $duracaoTempo) {
            self::removerToken($formName);
            return false;
        }

        if (hash_equals($storedToken, $token)) {
            self::removerToken($formName);
            return true;
        }

        return false;
    }

    public static function removerToken($formName = 'default') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['csrf_tokens'][$formName])) {
            unset($_SESSION['csrf_tokens'][$formName]);
        }
    }

    public static function limparToken($tempoMaximo = 86400) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_tokens'])) {
            return;
        }

        $horarioAtual = time();

        foreach ($_SESSION['csrf_tokens'] as $formName => $data) {
            if ($horarioAtual - $data['time'] > $tempoMaximo) {
                unset($_SESSION['csrf_tokens'][$formName]);
            }
        }
    }

    public static function campoFormulario($formName = 'default') {
        $token = self::gerarToken($formName);
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}


?>