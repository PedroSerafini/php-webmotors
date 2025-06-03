<link rel="stylesheet" href="assets/style.css">

<?php
include('includes/db.php');
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    if (mysqli_query($servidor, $sql)) {
        header("Location: login.php");
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($servidor);
    }
}
?>

<form method="post">
    <h2>Cadastro</h2>
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Cadastrar</button>
</form>
