<?php
session_start();

require_once '../php/db_connect.php';

if (
    !isset($_SESSION['id_user']) ||
    ($_SESSION['tipo_user'] != 'prof' &&
     $_SESSION['tipo_user'] != 'admin')
) {
    header("Location: ../public/login.php");
    exit();
}

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

    if ((isset($_FILES['perg_imagem'])) && ($_FILES['perg_imagem']['error'] === 0)) {
        $f = $_FILES['perg_imagem'];

        if ($f['size'] > 2097152) die("Ficheiro muito grande! Máximo 2MB.");

        $extensao = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if ($extensao != "jpg" && $extensao != "png") die("Formato inválido. Use JPG ou PNG.");

        $novo_nome = time() . "_" . $f['name'];
        $destino = "../uploads/" . $novo_nome;

        if (move_uploaded_file($f['tmp_name'], $destino)) $imagem_path = $destino; 
        else die("Erro ao mover o ficheiro para ../uploads/ .");
    }
    else $imagem_path = null;

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
        header("Location: ../public/lista_perguntas.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao guardar pergunta.";
    }

    mysqli_stmt_close($stmt);
}
?>