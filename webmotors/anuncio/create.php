<?php
include('../includes/db.php');
include('../includes/auth.php');
include('../includes/header.php');
checkLogin();

if (!isUser()) {
    die("Acesso negado. Apenas usuários podem criar anúncios.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($servidor, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($servidor, $_POST['descricao']);

    $preco = floatval($_POST['preco']);
    $id_usuario = $_SESSION['id'];

    // Upload da imagem
    $imagem_nome = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $imagem_nome = uniqid() . "." . $ext;
        $pasta = "../uploads/";

        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }

        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagem_nome);
    }
    // Inserir somente título e descrição
    $sql = "INSERT INTO anuncios (id_usuario, titulo, descricao, preco, imagem, aprovado) VALUES ($id_usuario, '$titulo', '$descricao', $preco, '$imagem_nome', 0)";

    if (mysqli_query($servidor, $sql)) {
        header('Location: ../dashboard.php?msg=Anúncio criado e aguardando aprovação!');
        exit;
    } else {
        $msg = 'Erro ao salvar anúncio no banco: ' . mysqli_error($servidor);
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container">
    <h2>Criar Novo Anúncio</h2>

    <?php if ($msg): ?>
        <div style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form action="create.php" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="preco">Preço (R$):</label>
        <input type="number" step="0.01" id="preco" name="preco" required>

        <label for="imagem">Imagem do veículo:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="5" required></textarea>

        <button type="submit">Criar Anúncio</button>
    </form>

    <p><a href="../dashboard.php">Voltar para o Painel</a></p>
</div>
