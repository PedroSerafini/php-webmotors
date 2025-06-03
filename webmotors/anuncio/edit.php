<?php
include('../includes/auth.php');
include('../includes/db.php');
include('../includes/header.php');
checkLogin();

$id = intval($_GET['id']);
$id_usuario = $_SESSION['id'];

$sql = "SELECT * FROM anuncios WHERE id = $id AND id_usuario = $id_usuario";
$result = mysqli_query($servidor, $sql);
$anuncio = mysqli_fetch_assoc($result);

if (!$anuncio) {
    die("Anúncio não encontrado ou acesso negado.");
}

$msgErro = '';
$msgSucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($servidor, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($servidor, $_POST['descricao']);
    $preco = floatval($_POST['preco']);

    if (!empty($_FILES['imagem']['name'])) {
        $imagem = basename($_FILES['imagem']['name']);
        $tmp = $_FILES['imagem']['tmp_name'];
        $uploadDir = "../uploads/";

        // Validar extensão (jpg, png, jpeg, gif)
        $ext = strtolower(pathinfo($imagem, PATHINFO_EXTENSION));
        $validExt = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $validExt)) {
            $msgErro = "Formato de imagem inválido. Use jpg, jpeg, png ou gif.";
        } else {
            if (move_uploaded_file($tmp, $uploadDir . $imagem)) {
                $sqlUpdate = "UPDATE anuncios SET titulo='$titulo', descricao='$descricao', imagem='$imagem', preco=$preco, aprovado=0 WHERE id=$id";
            } else {
                $msgErro = "Erro ao enviar a imagem.";
            }
        }
    } else {
        $sqlUpdate = "UPDATE anuncios SET titulo='$titulo', descricao='$descricao', preco=$preco, aprovado=0 WHERE id=$id";
    }

    if (!$msgErro && isset($sqlUpdate)) {
        if (mysqli_query($servidor, $sqlUpdate)) {
            $msgSucesso = "Anúncio atualizado com sucesso! Aguarde aprovação.";
        } else {
            $msgErro = "Erro no banco: " . mysqli_error($servidor);
        }
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container">
    <div class="navbar">
        <a href="view.php">Voltar aos Meus Anúncios</a>
        <a href="../dashboard.php" style="margin-left: 20px;">Painel</a>
        <a href="../logout.php" style="float:right;">Sair</a>
    </div>

    <h2>Editar Anúncio</h2>

    <?php if ($msgErro): ?>
        <div class="alert alert-erro"><?php echo htmlspecialchars($msgErro); ?></div>
    <?php endif; ?>
    <?php if ($msgSucesso): ?>
        <div class="alert alert-sucesso"><?php echo htmlspecialchars($msgSucesso); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-editar">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($anuncio['titulo']); ?>" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="5" required><?php echo htmlspecialchars($anuncio['descricao']); ?></textarea>

        <label>Imagem Atual</label><br>
        <img src="../uploads/<?php echo htmlspecialchars($anuncio['imagem']); ?>" alt="Imagem atual" class="img-atual"><br>

        <label for="imagem">Trocar Imagem (opcional)</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">

        <label for="preco">Preço</label>
        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo htmlspecialchars($anuncio['preco']); ?>" required>

        <button type="submit" class="btn btn-salvar">Salvar Alterações</button>
    </form>
</div>
