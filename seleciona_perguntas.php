<?php
session_start();
require_once '../php/db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['quiz_ativo'])) {

    if (!isset($_GET['sel_disciplina']) || !isset($_GET['sel_dificuldade'])) {
        header("Location: escolha_quiz.php");
        exit();
    }

    $id_disciplina = intval($_GET['sel_disciplina']);
    $sel_dificuldade = intval($_GET['sel_dificuldade']);

    $sql = "SELECT id, enunciado, respA, respB, respC, respD, correta, imagem
            FROM tb_questions
            WHERE disciplina_id = ?
            AND dificuldade = ?
            ORDER BY RAND()
            LIMIT 5";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $id_disciplina,
        $sel_dificuldade
    );

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $_SESSION['perguntas_quiz'] = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['perguntas_quiz'][] = $row;
    }

    mysqli_stmt_close($stmt);

    if (empty($_SESSION['perguntas_quiz'])) {
        echo "<script>
                alert('Não existem perguntas para esta disciplina e dificuldade.');
                window.location.href='escolha_quiz.php';
              </script>";
        exit();
    }

    $_SESSION['quiz_ativo'] = true;
    $_SESSION['pergunta_atual'] = 0;
    $_SESSION['pontuacao_quiz'] = 0;
    $_SESSION['tempo_inicio'] = time();
    $_SESSION['respostas_dadas'] = [];
}
?>