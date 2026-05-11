<?php

function atualizarPontuacao($resposta_aluno, $resposta_correta) {
    $correta = ($resposta_aluno === $resposta_correta) ? 1 : 0;
    $pontos = 0;
    
    if ($correta) {
        $pontos = 10;
        
        if (!isset($_SESSION['pontuacao_quiz'])) {
            $_SESSION['pontuacao_quiz'] = 0;
        }
        $_SESSION['pontuacao_quiz'] += $pontos;
    }
    
    return [
        'correta' => $correta,
        'pontos' => $pontos
    ];
}

function proximaPergunta() {
    if (!isset($_SESSION['pergunta_atual'])) {
        $_SESSION['pergunta_atual'] = 0;
    }
    
    $_SESSION['pergunta_atual']++;
    
    $total_perguntas = count($_SESSION['perguntas_quiz']);
    
    if ($_SESSION['pergunta_atual'] >= $total_perguntas) {
        return false;
    }
    
    return true;
}

function getEstadoQuiz() {
    return [
        'pergunta_atual' => isset($_SESSION['pergunta_atual']) ? $_SESSION['pergunta_atual'] : 0,
        'pontuacao' => isset($_SESSION['pontuacao_quiz']) ? $_SESSION['pontuacao_quiz'] : 0,
        'total_perguntas' => isset($_SESSION['perguntas_quiz']) ? count($_SESSION['perguntas_quiz']) : 0,
        'quiz_ativo' => isset($_SESSION['quiz_ativo']) ? $_SESSION['quiz_ativo'] : false
    ];
}

?>