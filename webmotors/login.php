<link rel="stylesheet" href="assets/style.css">

<?php
session_start();
include('includes/db.php');
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($servidor, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['tipo'] = $row['tipo'];
            echo $_SESSION['tipo'];
            header("Location: dashboard.php");
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
}
?>

<form method="post">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
</form>
