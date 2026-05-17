<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
<?php
    require_once './db_connect.php';
    include 'header.php';

    $query = "SELECT u.nome, SUM(a.pontuacao) AS total_pontos
              FROM tb_users u
              JOIN tb_attempts a ON u.id = a.user_id
              WHERE u.tipo = 'aluno'
              GROUP BY u.id, u.nome
              ORDER BY total_pontos DESC
              LIMIT 10";

    $resultado = mysqli_query($conn, $query);
?>


<div class="rk-wrap">
    <h2 class="rk-title">
        🏆 Ranking global — top 10
    </h2>
    <table class="rk-table">
        <thead>
            <tr>
                <th style="width:60px">#</th>
                <th>Nome</th>
                <th class="right">Pontuação</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $posicao = 1;
            while ($linha = mysqli_fetch_assoc($resultado)):
                if ($posicao == 1)     $badge = '<span class="pos-badge gold">1</span>';
                elseif ($posicao == 2) $badge = '<span class="pos-badge silver">2</span>';
                elseif ($posicao == 3) $badge = '<span class="pos-badge bronze">3</span>';
                else                   $badge = '<span class="pos-badge other">'.$posicao.'</span>';
        ?>
            <tr>
                <td><?= $badge ?></td>
                <td><?= htmlspecialchars($linha['nome']) ?></td>
                <td class="pts"><?= $linha['total_pontos'] ?><span>pts</span></td>
            </tr>
        <?php $posicao++; endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>