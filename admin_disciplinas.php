<?php
    require_once 'sessao.php';
    verificar_sessao();

    require_once 'db_connect.php';

    $mensagem="";
    $existe=false;

    $id_disciplina=$nomedisciplina="";

    include 'header.php';
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inserção de dados - Disciplinas</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #222;
            padding: 30px 20px 50px;
        }

        #inserirdisciplinas{
            width: min(700px, 95%);
            margin: 0 auto;
            padding: 28px 32px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 28px rgba(0,0,0,0.10);
            color: #222;
        }

        h2,
        h3{
            text-align: center;
            margin-bottom: 20px;
            color: #1f2d5c;
        }

        label{
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input[type="text"]{
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cfd6e4;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 4px;
            margin-bottom: 14px;
            background: white;
        }

        input[type="text"]:focus{
            outline: none;
            border-color: #1f5eff;
            box-shadow: 0 0 0 3px rgba(31,94,255,0.12);
        }

        input[type="submit"]{
            padding: 9px 18px;
            border: none;
            border-radius: 6px;
            background: #1f5eff;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: 0.15s ease;
            margin-right: 8px;
        }

        input[type="submit"]:hover {
            background: #153fb6;
        }

        .alert,
        .alert2{
            margin-top: 14px;
            padding: 10px 14px;
            border-radius: 6px;
            font-weight: bold;
        }

        .alert{
            background: #ffe6e6;
            color: #b10000;
        }

        .alert2{
            background: #e7f9eb;
            color: #0d7a28;
        }

        #listado{
            width: min(900px, 95%);
            margin: 35px auto 0;
        }

        #listado table{
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 8px 22px rgba(0,0,0,0.06);
            border-radius: 10px;
            overflow: hidden;
        }

        #listado th{
            background: #1f2d5c;
            color: white;
            padding: 12px 10px;
            font-size: 14px;
        }

        #listado td{
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #edf0f5;
        }

        #listado tr:hover{
            background: #f7faff;
        }

        #listado form{
            display: inline-block;
            margin: 2px;
        }

        #listado input[type="submit"]{
            padding: 6px 10px;
            font-size: 13px;
        }
    </style>
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

<?php include 'footer.php'; ?>
</body>