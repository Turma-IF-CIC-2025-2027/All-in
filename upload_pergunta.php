<?php
session_start();

require_once 'db_connect.php';
require_once 'sessao.php';
require_once 'upload_image.php';

verificar_tipo(professor);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_disciplina = intval($_POST['id_disciplina']);
    $id_tema = intval($_POST['id_tema']);
    $enunciado = trim($_POST['perg_texto']);
    $resp_a = trim($_POST['op_a']);
    $resp_b = trim($_POST['op_b']);
    $resp_c = trim($_POST['op_c']);
    $resp_d = trim($_POST['op_d']);
    $resposta_correta = $_POST['resp_correta'];
    $dificuldade = intval($_POST['perg_dificuldade']);

    $image_path = processarImagemUpload()

    $sql = "
        INSERT INTO tb_questions
        (
            enunciado,
            respA,
            respB,
            respC,
            respD,
            correta,
            disciplina_id,
            tema_id,
            imagem,
            dificuldade
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssiisi",
        $enunciado,
        $resp_a,
        $resp_b,
        $resp_c,
        $resp_d,
        $resposta_correta,
        $id_disciplina,
        $id_tema,
        $imagem_path,
        $dificuldade
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: lista_perguntas.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao guardar pergunta.";
    }

    mysqli_stmt_close($stmt);
}
?>
