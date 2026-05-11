<?php
session_start();
require_once "db_connection.php";

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['user'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Dashboard</title></head>
    <body>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
        <a href="login.php?action=logout">Logout</a>
    </body>
    </html>
    <?php
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $stmt = $conn->prepare('SELECT id, username, password, type FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->bind_param('ss', $login, $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashed_password, $type);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION['nome_user'] = $username;
            $_SESSION['id_user'] = $id;
            $_SESSION['tipo_user'] = $type;
            $stmt->close();
            $conn->close();
            header('Location: login.php');
            exit;
        }
    }

    $stmt->close();
    $error = 'Invalid username/email or password.';
}

$conn->close();
?>

<!-- LOGIN FORM -->
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Username or Email: <input type="text" name="login" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
