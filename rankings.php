<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    require_once './db_connect.php';
    include 'header.php';

    $conn = mysqli_connect($bd_host, $bd_user, $bd_password, $bd_database);
    if (!$conn) die("Erro na ligação: " . mysqli_connect_error());

    $query = "SELECT u.nome, SUM(a.pontuacao) AS total_pontos
              FROM tb_users u
              JOIN tb_attempts a ON u.id = a.user_id
              WHERE u.tipo = 'aluno'
              GROUP BY u.id, u.nome
              ORDER BY total_pontos DESC
              LIMIT 10";

    $resultado = mysqli_query($conn, $query);
?>

<style>
    .rk-wrap  { padding: 2rem; width: 100%; box-sizing: border-box; }
    .rk-title { font-size: 22px; font-weight: 500; margin: 0 0 1.5rem; display: flex; align-items: center; gap: 10px; }
    .rk-table { width: 100%; border-collapse: collapse; }
    .rk-table thead tr  { border-bottom: 2px solid #dee2e6; }
    .rk-table th  { font-size: 13px; font-weight: 500; color: #6c757d; padding: 10px 16px; text-align: left; text-transform: uppercase; letter-spacing: 0.06em; }
    .rk-table th.right { text-align: right; }
    .rk-table td  { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; font-size: 15px; vertical-align: middle; }
    .rk-table tr:last-child td { border-bottom: none; }
    .rk-table tbody tr:hover td { background: #f8f9fa; }
    .pos-badge { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; font-weight: 500; font-size: 15px; }
    .gold   { background: #C9A84C; color: #412402; }
    .silver { background: #A8A8A8; color: #2C2C2A; }
    .bronze { background: #A0522D; color: #FAEEDA; }
    .other  { background: #f0f0f0; color: #6c757d; font-size: 14px; }
    .pts    { text-align: right; font-weight: 500; }
    .pts span { font-size: 13px; font-weight: 400; color: #6c757d; margin-left: 3px; }
</style>

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