<?php
function iniciar_sessao($id, $nome, $tipo) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['id_user']   = $id;
    $_SESSION['nome_user'] = $nome;
    $_SESSION['tipo_user'] = $tipo;
}

function verificar_sessao() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id_user'])) {
        header('Location: ../public/login.php');
        exit();
    }
}

function verificar_tipo($tipos) {
    verificar_sessao();
    if (is_string($tipos)) {
        $tipos = [$tipos];
    }
    if (!in_array($_SESSION['tipo_user'], $tipos)) {
        header('Location: ../public/login.php');
        exit();
    }
}

?>