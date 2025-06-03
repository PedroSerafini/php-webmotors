<?php
include('../includes/auth.php');
include('../includes/db.php');
include('../includes/header.php');
checkLogin();

$id_usuario = $_SESSION['id'];
$sql = "SELECT * FROM anuncios WHERE id_usuario = $id_usuario ORDER BY id DESC";
$result = mysqli_query($servidor, $sql);
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="../dashboard.php">Voltar ao Painel</a>
        <a href="../logout.php" style="float:right;">Sair</a>
    </div>

    <h2>Meus Anúncios</h2>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>Você ainda não criou nenhum anúncio.</p>
    <?php else: ?>
        <div class="anuncios-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="anuncio-card">
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <img src="../uploads/<?php echo htmlspecialchars($row['imagem']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                    <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
                    <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                    <p class="status">
                        Status: 
                        <?php if ($row['aprovado']): ?>
                            <span class="status-aprovado">Aprovado</span>
                        <?php else: ?>
                            <span class="status-pendente">Aguardando aprovação</span>
                        <?php endif; ?>
                    </p>
                    <div class="botoes-acoes">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-editar">Editar</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-remover" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
