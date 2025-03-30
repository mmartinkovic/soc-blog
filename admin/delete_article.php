<?php
require_once '../includes/header.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$article_id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
if ($stmt->execute([$article_id])) {
    $_SESSION['success'] = "Článok bol odstránený.";
} else {
    $_SESSION['error'] = "Nepodarilo sa odstrániť článok.";
}

header("Location: dashboard.php");
exit();
?>