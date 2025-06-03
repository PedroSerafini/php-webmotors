<?php
include('../includes/auth.php');
include('../includes/db.php');
include('../includes/header.php');
checkLogin();

if (!isAdmin()) {
    die("Acesso negado.");
}

$msg = '';

// Processa ações via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $acao = $_POST['acao'];

    if ($acao === 'aprovar') {
        mysqli_query($servidor, "UPDATE anuncios SET aprovado = 1 WHERE id = $id");
        $msg = "✅ Anúncio aprovado com sucesso!";
    } elseif ($acao === 'remover') {
        mysqli_query($servidor, "DELETE FROM anuncios WHERE id = $id");
        $msg = "🗑️ Anúncio removido com sucesso!";
    }
}

// Buscar anúncios pendentes
$sql = "SELECT a.*, u.nome FROM anuncios a JOIN usuarios u ON a.id_usuario = u.id WHERE a.aprovado = 0 ORDER BY a.id DESC";
$result = mysqli_query($servidor, $sql);
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="../index.php">Início</a>
        <a href="#" style="font-weight: bold; color: #dc3545;">Painel Admin</a>
        <a href="../logout.php">Sair</a>
    </div>

    <h2>Gerenciar Anúncios Pendentes</h2>

    <?php if ($msg): ?>
        <div class="msg-sucesso">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>Nenhum anúncio pendente para aprovação.</p>
    <?php else: ?>
        <div class="anuncios-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="anuncio-card">
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                    <img src="../uploads/<?php echo htmlspecialchars($row['imagem']); ?>" alt="Foto do anúncio">
                    <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
                    <p class="status">Usuário: <?php echo htmlspecialchars($row['nome']); ?></p>
                    <div class="botoes-acoes">
                        <form method="post" class="form-acao">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="acao" value="aprovar">
                            <button type="submit" class="btn btn-aprovar">Aprovar</button>
                        </form>
                        <form method="post" class="form-acao">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="acao" value="remover">
                            <button type="submit" class="btn btn-remover">Remover</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
