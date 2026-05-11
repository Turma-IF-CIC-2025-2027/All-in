<?php
session_start();
require_once '../php/db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$nome_user = $_SESSION['nome_user'];

if (!isset($_SESSION['quiz_ativo'])) {
    if (!isset($_GET['disciplina']) || !isset($_GET['dificuldade'])) {
        header("Location: escolha_quiz.php");
        exit();
    }
    
    $id_disciplina = intval($_GET['disciplina']);
    $sel_dificuldade = intval($_GET['dificuldade']);
    
    $sql = "SELECT id, enunciado, respA, respB, respC, respD, correta, imagem 
            FROM tb_questions 
            WHERE disciplina_id = ? AND dificuldade = ? 
            ORDER BY RAND() 
            LIMIT 10";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_disciplina, $sel_dificuldade);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $_SESSION['perguntas_quiz'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['perguntas_quiz'][] = $row;
    }
    
    if (empty($_SESSION['perguntas_quiz'])) {
        echo "<script>alert('Não existem perguntas para esta combinação!'); window.location.href='escolha_quiz.php';</script>";
        exit();
    }
    
    $_SESSION['quiz_ativo'] = true;
    $_SESSION['pergunta_atual'] = 0;
    $_SESSION['pontuacao_quiz'] = 0;
    $_SESSION['respostas_dadas'] = [];
    $_SESSION['tempo_inicio'] = time();
    mysqli_stmt_close($stmt);
}

$pergunta_atual = $_SESSION['pergunta_atual'];
$total_perguntas = count($_SESSION['perguntas_quiz']);

if ($pergunta_atual >= $total_perguntas) {
    header("Location: resultado_quiz.php");
    exit();
}

$pergunta = $_SESSION['perguntas_quiz'][$pergunta_atual];
$id_pergunta = $pergunta['id'];
$enunciado = $pergunta['enunciado'];
$resp_a = $pergunta['respA'];
$resp_b = $pergunta['respB'];
$resp_c = $pergunta['respC'];
$resp_d = $pergunta['respD'];
$imagem_path = $pergunta['imagem'];

include 'header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Progresso do Quiz</h5>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?php echo (($pergunta_atual + 1) / $total_perguntas) * 100; ?>%">
                            Pergunta <?php echo ($pergunta_atual + 1); ?> de <?php echo $total_perguntas; ?>
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong>Pontuação Atual:</strong> <?php echo $_SESSION['pontuacao_quiz']; ?> pontos
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pergunta <?php echo ($pergunta_atual + 1); ?></h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-4"><?php echo htmlspecialchars($enunciado); ?></h5>
                    
                    <?php if (!empty($imagem_path) && file_exists("../uploads/" . $imagem_path)): ?>
                        <div class="text-center mb-4">
                            <img src="../uploads/<?php echo htmlspecialchars($imagem_path); ?>" 
                                 alt="Imagem da Pergunta" 
                                 class="img-fluid rounded" 
                                 style="max-height: 300px;">
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="../php/processa_quiz.php">
                        <input type="hidden" name="id_pergunta_atual" value="<?php echo $id_pergunta; ?>">
                        
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action">
                                <input type="radio" name="resposta_aluno" value="A" required>
                                <strong>A)</strong> <?php echo htmlspecialchars($resp_a); ?>
                            </label>
                            
                            <label class="list-group-item list-group-item-action">
                                <input type="radio" name="resposta_aluno" value="B" required>
                                <strong>B)</strong> <?php echo htmlspecialchars($resp_b); ?>
                            </label>
                            
                            <label class="list-group-item list-group-item-action">
                                <input type="radio" name="resposta_aluno" value="C" required>
                                <strong>C)</strong> <?php echo htmlspecialchars($resp_c); ?>
                            </label>
                            
                            <label class="list-group-item list-group-item-action">
                                <input type="radio" name="resposta_aluno" value="D" required>
                                <strong>D)</strong> <?php echo htmlspecialchars($resp_d); ?>
                            </label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <?php echo ($pergunta_atual < $total_perguntas - 1) ? 'Próxima Pergunta' : 'Finalizar Quiz'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>