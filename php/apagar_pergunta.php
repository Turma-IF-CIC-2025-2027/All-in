<?php
require_once '../sessao.php';
verificar_tipo(['admin', 'prof']);

require_once '../db_connect.php';

$id_pergunta = intval($_GET['id'] ?? 0);

if ($id_pergunta <= 0) {
    header('Location: ../public/lista_perguntas.php?msg=erro');
    exit;
}

$sql  = "DELETE FROM tb_questions WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    header('Location: ../public/lista_perguntas.php?msg=erro');
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $id_pergunta);
$sucesso = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($sucesso) {
    header('Location: ../public/lista_perguntas.php?msg=apagado');
} else {
    header('Location: ../public/lista_perguntas.php?msg=erro');
}

exit;
