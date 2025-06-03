<?php
$servidor = mysqli_connect('localhost', 'root', 'Phs123456789@');
$db = mysqli_select_db($servidor, 'webmotors');

if (!$servidor || !$db) {
    die("Erro na conexão com o banco de dados.");
}
?>
