<?php session_start();

    /*ver depois o facto de tipo de user que pode aceder a página ser apenas administrador
    if(tipo_user=="admin"){}*/

    /*if(!isset($_SESSION['tipo_user']) || $_SESSION['tipo_user']!="admin") {
        die("Acesso negado.");
    }*/

    require_once 'db_connect.php';

    $mensagem="";
    $existe=false;

    $id_disciplina=$nomedisciplina="";
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inserção de dados - Disciplinas</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>

<body>

<?php
    
/*"ler" disciplina*/
if(isset($_POST['ler_disciplina'])){

    $id_disciplina=$_POST['id_disciplina'];

    $qr="SELECT * FROM tb_disciplines WHERE id=?";
    $ordem=$conn->prepare($qr);
    $ordem->bind_param('i', $id_disciplina);
    $ordem->execute();

    $resultado=$ordem->get_result();

    if($resultado->num_rows>0) {
        $row=$resultado->fetch_assoc();

        $id_disciplina=$row["id"];
        $nomedisciplina=$row["nome"];
        $existe=true;
    }

    $ordem->close();
}


/*Inserir disciplina*/
else if(isset($_POST['insere_disciplina'])){

    $qr="SELECT COUNT(*) FROM tb_disciplines WHERE nome=?";
    $ordem=$conn->prepare($qr);

    $ordem->bind_param('s', $_POST["disc_nome"]);

    $nrows=0;

    $ordem->execute();
    $ordem->bind_result($nrows);
    $ordem->fetch();
    $ordem->close();

    if($nrows>0) {
        echo "<div class='alert'>ERRO: essa disciplina já existe!</div>";
    } else{

        $qr="INSERT INTO tb_disciplines(nome) VALUES(?)";
        $ordem=$conn->prepare($qr);
        $ordem->bind_param('s', $_POST["disc_nome"]);

        if($ordem->execute()) {
            echo "<div class='alert2'>Disciplina inserida!</div>";
        } else{
            echo "<div class='alert'>Erro ao inserir!</div>";
        }

        $ordem->close();
    }
}

/*alterar disciplina*/
else if(isset($_POST['alterar_disciplina'])){

    $id_disciplina=$_POST['id_disciplina'];
    $novo_nome=trim($_POST["disc_nome"]);

    $qr="SELECT COUNT(*) FROM tb_disciplines WHERE nome=? AND id <> ?"; //<> = "diferente de"
    $ordem=$conn->prepare($qr);
    $ordem->bind_param('si', $novo_nome, $id_disciplina);

    $nrows=0;
    $ordem->execute();
    $ordem->bind_result($nrows);
    $ordem->fetch();
    $ordem->close();

    if($nrows>0){
        echo "<div class='alert'>ERRO: já existe uma disciplina com esse nome!</div>";

        $nomedisciplina=$novo_nome;
        $existe=true;
    }
    else{
        $qr="UPDATE tb_disciplines SET nome = ? WHERE id = ?";
        $ordem=$conn->prepare($qr);
        $ordem->bind_param('si', $novo_nome, $id_disciplina);

        if($ordem->execute()){
            echo "<div class='alert2'>Disciplina alterada!</div>";

            $nomedisciplina=$novo_nome;
            $existe=true;
        }
        else{
            echo "<div class='alert'>Erro ao alterar!</div>";
        }
        $ordem->close();
    }
}

/*apagar disciplina*/
else if(isset($_POST['apagar_disciplina'])){

    $id_disciplina=$_POST['id_disciplina'];

    $qr="DELETE FROM tb_disciplines WHERE id=?";
    $ordem=$conn->prepare($qr);
    $ordem->bind_param('i', $id_disciplina);

    if ($ordem->execute()) {
        echo "<div class='alert2'>Disciplina apagada!</div>";

        $id_disciplina="";
        $nomedisciplina="";
        $existe=false;
    } else{
        echo "<div class='alert'>Erro ao apagar!</div>";
    }

    $ordem->close();
}

?>

<br>

<div id="inserirdisciplinas">

    <h2>Gestão de Disciplinas</h2><br><br>

    <form name="formdisciplinas" method="post" action="" enctype="multipart/form-data">

        <input type="hidden" name="id_disciplina" value="<?php echo $id_disciplina; ?>">

        <label>Nome da Disciplina:</label>
        <input type="text" autocomplete="off" maxlength="50" name="disc_nome" required value="<?php echo $nomedisciplina; ?>">

        <br><br><br>

        <?php if($existe==true) { ?>
            <input type="submit" name="alterar_disciplina" value="Alterar">
        <?php } else{ ?>
            <input type="submit" name="insere_disciplina" value="Inserir">
        <?php } ?>

    </form>

    <p><?php echo $mensagem; ?></p>

</div>

<br>

<div id="listado">
<h3>Disciplinas Registadas</h3>

<br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Ações</th>
    </tr>

    <?php
        $qr="SELECT * FROM tb_disciplines ORDER BY id";
        $listar=$conn->query($qr);

        while($row=$listar->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["nome"]; ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id_disciplina" value="<?php echo $row["id"]; ?>">
                    <input type="submit" name="ler_disciplina" value="Ler">
                </form>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="id_disciplina" value="<?php echo $row["id"]; ?>">
                    <input type="submit" name="apagar_disciplina" value="Apagar">
                </form>
            </td>
        </tr>
    <?php
        }

        $listar->free();
    ?>
</table>
</div>

</body>
