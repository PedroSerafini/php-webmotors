<?php
$servidor = mysqli_connect('localhost', 'root','');
$db = mysqli_select_db($servidor, 'webmotors2');

if (!$servidor || !$db) {
    die("Erro na conexão com o banco de dados.");
}
?>
