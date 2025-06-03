<?php
include('includes/db.php');
include('includes/header.php');

$resultado = mysqli_query($servidor, "SELECT * FROM anuncios WHERE aprovado = 1 ORDER BY id DESC");
?>

<link rel="stylesheet" href="assets/style.css">

<div class="container">
    <h2>Lista de Anúncios</h2>

    <?php while ($anuncio = mysqli_fetch_assoc($resultado)): ?>
        <div class="anuncio-card">
            <h3><?php echo htmlspecialchars($anuncio['titulo']); ?></h3>

            <?php if (!empty($anuncio['imagem'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($anuncio['imagem']); ?>" alt="Imagem do anúncio" style="max-width: 300px;">
            <?php endif; ?>

            <p><?php echo nl2br(htmlspecialchars($anuncio['descricao'])); ?></p>
            <p><strong>Preço:</strong> R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></p>
        </div>
    <?php endwhile; ?>
</div>
