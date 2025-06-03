<?php
session_start();

function isAdmin() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
}

function isUser() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'user';
}

function checkLogin() {
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }
}
?>
