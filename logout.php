<?php
session_start();

//clean session variables
$_SESSION = [];

//limpeza cookies sessao
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

//destroy session
session_destroy();

//voltar para a pagina principal
header('Location: index.php');
exit;
