<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Receber os filtros, se houver
$nomeFiltro = isset($_GET['nome']) ? mysqli_real_escape_string($servidor, $_GET['nome']) : '';
$precoMin = isset($_GET['preco_min']) ? floatval(str_replace(',', '.', $_GET['preco_min'])) : '';
$precoMax = isset($_GET['preco_max']) ? floatval(str_replace(',', '.', $_GET['preco_max'])) : '';

$sql = "SELECT a.*, u.nome FROM anuncios a JOIN usuarios u ON a.id_usuario = u.id WHERE a.aprovado = 1";

if (!empty($nomeFiltro)) {
    $sql .= " AND a.titulo LIKE '%$nomeFiltro%'";
}

if (isset($_GET['preco_min']) && $_GET['preco_min'] !== '' && $_GET['preco_min'] != 0) {
    $precoMin = floatval(str_replace(',', '.', $_GET['preco_min']));
    $sql .= " AND a.preco >= $precoMin";
}

if (isset($_GET['preco_max']) && $_GET['preco_max'] !== '' && $_GET['preco_max'] != 0) {
    $precoMax = floatval(str_replace(',', '.', $_GET['preco_max']));
    $sql .= " AND a.preco <= $precoMax";
}

$sql .= " ORDER BY a.id DESC";
$result = mysqli_query($servidor, $sql);

?>

<link rel="stylesheet" href="assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="index.php">Início</a>

        <?php if (isset($_SESSION['id'])): ?>
    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'user'): ?>
        <a href="dashboard.php">Painel</a>
    <?php endif; ?>
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

    <form method="GET" class="filter">
        <input type="text" name="nome" placeholder="Buscar por título" value="<?php echo htmlspecialchars($nomeFiltro); ?>">

        <input type="number" step="0.01" name="preco_min" placeholder="Preço mínimo" value="<?php echo isset($precoMin) ? htmlspecialchars($precoMin) : ''; ?>">

        <input type="number" step="0.01" name="preco_max" placeholder="Preço máximo" value="<?php echo isset($precoMax) ? htmlspecialchars($precoMax) : ''; ?>">

        <button type="submit">Filtrar</button>
    </form>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>Nenhum anúncio aprovado disponível no momento.</p>
    <?php endif; ?>

    <div class="grid-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <a href="imprimir.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
            <div class="anuncio">
                <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                <img src="uploads/<?php echo $row['imagem']; ?>" width="100%" style="max-width: 300px;"><br><br>
                <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
                <p class="status">Publicado por: <?php echo htmlspecialchars($row['nome']); ?></p>
            </div>
        </a>
    <?php endwhile; ?>
    </div>
</div>

