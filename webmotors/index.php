<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Buscar apenas os anúncios aprovados
$sql = "SELECT a.*, u.nome FROM anuncios a JOIN usuarios u ON a.id_usuario = u.id WHERE a.aprovado = 1 ORDER BY a.id DESC";
$result = mysqli_query($servidor, $sql);
?>

<link rel="stylesheet" href="assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="index.php">Início</a>

        <?php if (isset($_SESSION['id'])): ?>
    <a href="dashboard.php">Painel</a>
    <?php if ($_SESSION['tipo'] === 'admin'): ?>
        <a href="admin/approve.php" style="font-weight: bold; color: #dc3545;">Painel Admin</a>
    <?php endif; ?>
    <a href="logout.php">Sair</a>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Cadastro</a>
<?php endif; ?>

    </div>

    <h1>Anúncios Aprovados</h1>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>Nenhum anúncio aprovado disponível no momento.</p>
    <?php endif; ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="anuncio">
            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
            <img src="uploads/<?php echo $row['imagem']; ?>" width="300"><br><br>
            <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
            <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
            <p class="status">Publicado por: <?php echo htmlspecialchars($row['nome']); ?></p>
        </div>
    <?php endwhile; ?>
</div>

