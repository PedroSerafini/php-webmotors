<?php
include('includes/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID inválido.";
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT a.*, u.nome FROM anuncios a JOIN usuarios u ON a.id_usuario = u.id WHERE a.id = $id AND a.aprovado = 1 LIMIT 1";
$result = mysqli_query($servidor, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "Anúncio não encontrado ou não aprovado.";
    exit;
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <link rel="stylesheet" href="assets/style.css">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Anúncio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .anuncio {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .print-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
            width: 30%
        }
    </style>
</head>
<body>

<div class="anuncio">
    <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
    <img src="uploads/<?php echo $row['imagem']; ?>" alt="Imagem do anúncio"><br><br>
    <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
    <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
    <p><strong>Publicado por:</strong> <?php echo htmlspecialchars($row['nome']); ?></p>
</div>

<div class="print-btn">
    <button onclick="window.print()">Imprimir</button>
    <a href="index.php" >Voltar aos anúncios</a>
</div>

</body>
</html>
