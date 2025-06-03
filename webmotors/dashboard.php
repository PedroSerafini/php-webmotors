<?php
session_start();
include('includes/db.php');
include('includes/auth.php');
include('includes/header.php');
checkLogin();

$id_usuario = $_SESSION['id'];
$sql = "SELECT * FROM anuncios WHERE id_usuario = $id_usuario ORDER BY id DESC";
$result = mysqli_query($servidor, $sql);
?>

<link rel="stylesheet" href="assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="index.php">Início</a>
        <a href="dashboard.php" style="font-weight: bold; color: #007bff;">Painel</a>
        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
            <a href="admin/approve.php" style="font-weight: bold; color: #dc3545;">Painel Admin</a>
        <?php endif; ?>
        <a href="logout.php">Sair</a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Meus Anúncios</h2>
        <a href="anuncio/create.php" class="btn-create">+ Criar Anúncio</a>
    </div>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p>Você ainda não criou anúncios.</p>
    <?php else: ?>
        <div class="anuncios-grid">
            <?php while($anuncio = mysqli_fetch_assoc($result)): ?>
                <div class="anuncio-card">
                    <img src="uploads/<?php echo htmlspecialchars($anuncio['imagem']); ?>" alt="<?php echo htmlspecialchars($anuncio['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($anuncio['titulo']); ?></h3>
                    <p><strong>Preço:</strong> R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($anuncio['descricao'])); ?></p>
                    <p><strong>Status:</strong> <?php echo $anuncio['aprovado'] ? '<span style="color:green;">Aprovado</span>' : '<span style="color:orange;">Aguardando aprovação</span>'; ?></p>
                    <p>
                        <a href="anuncio/edit.php?id=<?php echo $anuncio['id']; ?>" class="btn-link">Editar</a> |
                        <a href="anuncio/delete.php?id=<?php echo $anuncio['id']; ?>" onclick="return confirm('Confirma exclusão?')" class="btn-link btn-danger">Excluir</a>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Navbar */
    .navbar {
        background-color: #f8f9fa;
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 6px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        display: flex;
        gap: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar a {
        text-decoration: none;
        color: #555;
        font-weight: 600;
        transition: color 0.3s;
    }
    .navbar a:hover {
        color: #007bff;
    }

    /* Container */
    .container {
        max-width: 900px;
        margin: auto;
        padding: 0 15px 40px;
        font-family: Arial, sans-serif;
    }

    /* Botão criar anúncio */
    .btn-create {
        background-color: #007bff;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.3s;
        box-shadow: 0 3px 6px rgba(0,123,255,0.4);
    }
    .btn-create:hover {
        background-color: #0056b3;
        box-shadow: 0 5px 12px rgba(0,86,179,0.5);
    }

    /* Grid para anúncios */
    .anuncios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    /* Card do anúncio */
    .anuncio-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        background-color: #fff;
        transition: box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .anuncio-card:hover {
        box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }

    /* Imagem */
    .anuncio-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    /* Título */
    .anuncio-card h3 {
        font-size: 1.2rem;
        margin: 0 0 8px;
        color: #222;
    }

    /* Descrição */
    .anuncio-card p {
        font-size: 0.95rem;
        color: #555;
        flex-grow: 1;
        margin-bottom: 12px;
        white-space: pre-wrap;
    }

    /* Status */
    .anuncio-card p strong {
        font-weight: 700;
    }

    /* Links */
    .btn-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-link:hover {
        text-decoration: underline;
    }
    .btn-danger {
        color: #dc3545;
    }
    .btn-danger:hover {
        text-decoration: underline;
    }
</style>
