<?php session_start(); ?>
<?php
require_once 'db_connect.php';

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <form action="" method="post">
        <label for="user_nome">Nome:</label>
        <input type="text" id="user_nome" name="user_nome" required><br><br>

        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" required><br><br>

        <label for="user_password">Password:</label>
        <input type="password" id="user_password" name="user_password" required><br><br>

        <label for="user_tipo">Tipo:</label>
        <select id="user_tipo" name="user_tipo" required>
            <option value="aluno">Aluno</option>
            <option value="prof">Professor</option>
            <option value="admin">Administrador</option>
        </select><br><br>

        <input type="submit" value="Register">
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $nome_user=trim($_POST["user_nome"]);
            $email_user=trim($_POST["user_email"]);
            $pass_user=$_POST["user_password"];
            $tipo_user=$_POST["user_tipo"];

            if (empty($nome_user) || empty($email_user) || empty($pass_user) || empty($tipo_user)){
                $erroReg="Preencha todos os campos.";
            }
            elseif (!in_array($tipo_user, ['admin','prof','aluno'])){
                $erroReg="Tipo de utilizador inválido.";
            }
            elseif (strlen($pass_user)<8){
                $erroReg="A palavra-passe deve ter pelo menos 8 caracteres.";
            }
            else{
                $check=$conn->prepare("SELECT id FROM tb_users WHERE email=?");
                $check->bind_param("s", $email_user);
                $check->execute();
                $check->store_result();
                if ($check->num_rows > 0){
                    $erroReg="Esse email já existe.";
                }
                else{
                    $hash=password_hash($pass_user, PASSWORD_DEFAULT);
                    $ins=$conn->prepare("INSERT INTO tb_users (nome, email, password, tipo) VALUES (?, ?, ?, ?)");
                    $ins->bind_param("ssss", $nome_user, $email_user, $hash, $tipo_user);
                    if ($ins->execute()){
                        $sucessoReg="Utilizador <strong>" . htmlspecialchars($nome_user) . "</strong> criado com sucesso.";
                    }
                    else{
                        $erroReg="Erro ao criar o utilizador. Tente novamente.";
                    }
                }
            }
        }

        if (isset($erroReg)) {
            echo "<p style='color: red;'>" . htmlspecialchars($erroReg) . "</p>";
        }

        if (isset($sucessoReg)) {
            echo "<p style='color: green;'>" . $sucessoReg . "</p>";
        }
        ?>
    </body>
    </html>
