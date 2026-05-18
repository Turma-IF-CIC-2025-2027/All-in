<?php
include 'sessao.php';
require_once 'db_connect.php';
verificar_sessao();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gestão de Temas</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="public/css/main.css">
</head>
<body>
<?php include 'header.php'; ?>
<div>
<?php
if(isset($_POST['inserirTema'])){
    if(empty($_POST['disciplina']) || empty($_POST['tema'])){
        echo "<div class='alert alert-danger'>
        <i class='bi bi-exclamation-circle'></i>
        Preencha todos os campos corretamente!
        </div>";
    }
    else{
        $id_disciplina = $_POST['disciplina'];
        $tema_nome = $_POST['tema'];
        $query = "INSERT INTO tb_themes (disciplina_id, nome) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $id_disciplina, $tema_nome);
        $stmt->execute();
        $stmt->close();
        echo "<div class='alert alert-success'>
        <i class='bi bi-check-circle'></i>
        Tema inserido com sucesso!
        </div>";
    }
}
if(isset($_POST['editarTema'])){
    $id_tema = (int) $_POST['id_tema'];
    $tema_nome = trim($_POST['nome_tema']);
    if($tema_nome === ''){
        echo "<div class='alert alert-danger'>
        <i class='bi bi-exclamation-circle'></i>
        O nome do tema não pode estar vazio!
        </div>";
    } else {
        $query = "UPDATE tb_themes SET nome = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $tema_nome, $id_tema);
        $stmt->execute();
        $stmt->close();
        echo "<div class='alert alert-success'>
        <i class='bi bi-check-circle'></i>
        Tema atualizado com sucesso!
        </div>";
    }
}
if(isset($_POST['eliminarTema'])){
    $id_tema = (int) $_POST['id_tema'];
    $query = "DELETE FROM tb_themes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_tema);
    $stmt->execute();
    $stmt->close();
    echo "<div class='alert alert-success'>
    <i class='bi bi-check-circle'></i>
    Tema eliminado com sucesso!
    </div>";
}
?>
<form method="post">
<select name="disciplina" id="disciplina">
<option value="">Selecione uma disciplina</option>
<?php
$query = "SELECT id, nome FROM tb_disciplinas";
$result = $conn->query($query);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
}
?>
</select>
<label for="tema">Tema:</label>
<input type="text" name="tema" id="tema" placeholder="Tema">
<input type="submit" name="inserirTema" value="Inserir">
</form>
</div>
<div>
<form method="post">
<select name="disciplina_list" id="disciplina_list">
<option value="">Selecione uma disciplina</option>
<?php
$query = "SELECT id, nome FROM tb_disciplinas";
$result = $conn->query($query);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
}
?>
</select>
<label for="tema">Tema:</label>
<input type="submit" name="ListarTema" value="Inserir">
</form>
<div>
<?php
$id_disciplina = null;
if(isset($_POST['ListarTema'])){
    $id_disciplina = $_POST['disciplina_list'];
}
else if(isset($_POST['editarTema']) || isset($_POST['eliminarTema'])){
    if(isset($_POST['disciplina_id'])){
        $id_disciplina = $_POST['disciplina_id'];
    }
}
else if(isset($_POST['inserirTema'])){
    if(isset($_POST['disciplina'])){
        $id_disciplina = $_POST['disciplina'];
    }
}
if($id_disciplina !== null && $id_disciplina !== ''){
    $id_disciplina = (int) $id_disciplina;
    $query = "SELECT * FROM tb_themes WHERE disciplina_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_disciplina);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<h2>Temas da Disciplina</h2>";
    echo "<table class='table'>";
    echo "<tr><th>Nome Tema</th><th>Guardar</th><th>Eliminar</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $nome_escaped = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
        $id_tema = (int) $row['id'];
        echo "<tr>
        <td>
        <form method='post' class='d-flex gap-2'>
        <input type='hidden' name='id_tema' value='{$id_tema}'>
        <input type='hidden' name='disciplina_id' value='{$id_disciplina}'>
        <input type='text' name='nome_tema' value='{$nome_escaped}' class='form-control'>
        </td>
        <td>
        <button type='submit' name='editarTema' class='btn btn-primary'>
        <i class='bi bi-floppy'></i>Guardar
        </button>
        </form>
        </td>
        <td>
        <form method='post' onsubmit='return confirm(\"Eliminar este tema?\");'>
        <input type='hidden' name='id_tema' value='{$id_tema}'>
        <input type='hidden' name='disciplina_id' value='{$id_disciplina}'>
        <button type='submit' name='eliminarTema' class='btn btn-danger'>
        <i class='bi bi-trash'></i>Eliminar
        </button>
        </form>
        </td>
        </tr>";
    }
    echo "</table>";
    $stmt->close();
}
?>
</div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
