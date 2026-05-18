<?php
require_once 'db_connect.php';
require_once 'session.php';

// Proteção de página: só alunos autenticados podem gravar resultados
verificar_tipo('aluno');

// Recolher variáveis de sessão
if (!isset($_SESSION['pontuacao_final'], $_SESSION['tempo_inicio'])) {
    header('Location: escolha_quiz.php');
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$pontuacao_final = (int) $_SESSION['pontuacao_final'];

// Calcular o tempo gasto em segundos
$tempo_fim = time();
$tempo_inicio = (int) $_SESSION['tempo_inicio'];
$tempo = max(0, $tempo_fim - $tempo_inicio);

// Inserir na tb_attempts com Prepared Statement
$sql  = "INSERT INTO tb_attempts (user_id, data, pontuacao, tempo) VALUES (?, NOW(), ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Erro ao preparar a query: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, 'iii', $id_user, $pontuacao_final, $tempo);
$executou = mysqli_stmt_execute($stmt);
if (!$executou) {
    die("Erro ao gravar o resultado: " . mysqli_stmt_error($stmt));
}

// Guardar o id da tentativa na sessão (para uso futuro, ex: tb_attempt_answers)
$id_tentativa = mysqli_insert_id($conn);
$_SESSION['id_tentativa'] = $id_tentativa;
mysqli_stmt_close($stmt);

// Limpar variáveis de sessão do quiz (mas manter login)
unset($_SESSION['pontuacao_final']);
unset($_SESSION['tempo_inicio']);

// Redirecionar para o histórico ou ranking
header('Location: rankings.php');
exit;
