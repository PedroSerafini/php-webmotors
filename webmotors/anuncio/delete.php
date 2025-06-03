<?php
include('../includes/auth.php');
include('../includes/db.php');
include('../includes/header.php');
checkLogin();

$id = $_GET['id'];
$sql = "DELETE FROM anuncios WHERE id = $id AND id_usuario = {$_SESSION['id']}";
mysqli_query($servidor, $sql);

header("Location: view.php");
