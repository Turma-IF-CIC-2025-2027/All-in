<?php
session_start();
require_once 'db_connect.php';
require_once 'atualizar_pontuacao.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_SESSION['quiz_ativo']) || $_SESSION['quiz_ativo'] !== true) {
    header("Location: ../public/escolha_quiz.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/fazer_quiz.php");
    exit();
}

$resposta_aluno   = isset($_POST['resposta_aluno'])    ? strtoupper(trim($_POST['resposta_aluno']))  : null;
$id_pergunta_post = isset($_POST['id_pergunta_atual']) ? intval($_POST['id_pergunta_atual'])          : null;

$opcoes_validas = ['A', 'B', 'C', 'D'];
if (!in_array($resposta_aluno, $opcoes_validas) || $id_pergunta_post === null) {
    header("Location: ../public/fazer_quiz.php");
    exit();
}

$idx_atual       = $_SESSION['pergunta_atual'];
$perguntas       = $_SESSION['perguntas_quiz'];
$pergunta_sessao = $perguntas[$idx_atual];

if ((int)$pergunta_sessao['id'] !== $id_pergunta_post) {
    header("Location: ../public/fazer_quiz.php");
    exit();
}

$resposta_correta = $pergunta_sessao['correta'];
$resultado = atualizarPontuacao($resposta_aluno, $resposta_correta);

$_SESSION['respostas_dadas'][] = [
    'question_id'   => $id_pergunta_post,
    'resposta_dada' => $resposta_aluno,
    'correta'       => $resultado['correta']
];

$tem_proxima = proximaPergunta();

if (!$tem_proxima) {
    $_SESSION['quiz_ativo'] = false;
    header("Location: ../public/resultado_quiz.php");
    exit();
}

header("Location: ../public/fazer_quiz.php");
exit();
?>
