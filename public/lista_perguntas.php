<?php
require_once '../sessao.php';
verificar_tipo(['admin', 'prof']);
require_once '../db_connect.php';
$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'apagado') {
        $mensagem = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Pergunta apagada com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    } elseif ($_GET['msg'] === 'erro') {
        $mensagem = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Erro ao apagar a pergunta. Tenta novamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    }
}
$sql = "
    SELECT
        q.id,
        q.enunciado,
        q.respA,
        q.respB,
        q.respC,
        q.respD,
        q.correta,
        q.dificuldade,
        q.imagem,
        d.nome AS nome_disciplina,
        t.nome AS nome_tema
    FROM tb_questions AS q
    LEFT JOIN tb_disciplines AS d ON q.disciplina_id = d.id
    LEFT JOIN tb_themes      AS t ON q.tema_id       = t.id
    ORDER BY q.id DESC
";

$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Perguntas — All‑In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS do projeto (Equipa 5) -->
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">Lista de Perguntas</h1>
            <p class="text-muted mb-0">
                Sessão iniciada como <strong><?= htmlspecialchars($_SESSION['nome_user']) ?></strong>
                (<?= htmlspecialchars($_SESSION['tipo_user']) ?>)
            </p>
        </div>
        <a href="prof_perguntas.php" class="btn btn-primary">➕ Nova Pergunta</a>
    </div>

    <?= $mensagem ?>

    <?php if (mysqli_num_rows($resultado) === 0): ?>

        <div class="alert alert-warning">
            Ainda não existem perguntas na base de dados.
        </div>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Enunciado</th>
                        <th>Disciplina</th>
                        <th>Tema</th>
                        <th>Opções</th>
                        <th>Correta</th>
                        <th>Dific.</th>
                        <th>Imagem</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>

                        <td><?= htmlspecialchars($row['enunciado']) ?></td>

                        <td><?= htmlspecialchars($row['nome_disciplina'] ?? '—') ?></td>

                        <td><?= htmlspecialchars($row['nome_tema'] ?? '—') ?></td>

                        <td>
                            <small>
                                <b>A:</b> <?= htmlspecialchars($row['respA']) ?><br>
                                <b>B:</b> <?= htmlspecialchars($row['respB']) ?><br>
                                <b>C:</b> <?= htmlspecialchars($row['respC']) ?><br>
                                <b>D:</b> <?= htmlspecialchars($row['respD']) ?>
                            </small>
                        </td>

                        <td>
                            <span class="badge bg-success fs-6">
                                <?= htmlspecialchars($row['correta']) ?>
                            </span>
                        </td>

                        <td><?= $row['dificuldade'] ?>/5</td>

                        <td>
                            <?php if (!empty($row['imagem'])): ?>
                                <img
                                    src="../uploads/<?= htmlspecialchars($row['imagem']) ?>"
                                    alt="imagem da pergunta"
                                    style="max-width:80px; max-height:60px; object-fit:cover;"
                                    class="rounded"
                                >
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a
                                href="../php/apagar_pergunta.php?id=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tens a certeza que queres apagar esta pergunta?\nEsta ação não pode ser revertida.');"
                            >
                                Apagar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

<?php
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
