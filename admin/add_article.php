<?php
require_once '../includes/header.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Vyplňte všetky polia.";
        header("Location: add_article.php");
        exit();
    } else {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $content, $_SESSION['user_id']])) {
            $_SESSION['success'] = "Článok bol úspešne pridaný.";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Nepodarilo sa pridať článok.";
            header("Location: add_article.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="article-form">
        <h2>Pridať nový článok</h2>
        <form method="post">
            <div class="form-group">
                <label for="title">Názov článku</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Obsah článku</label>
                <textarea id="content" name="content" required></textarea>
                <div class="formatting-help">
                    <small>
                        <strong>Formátovanie:</strong><br>
                        **tučné** → <strong>tučné</strong><br>
                        *kurzíva* → <em>kurzíva</em><br>
                        [text](https://priklad.sk) → <a href="#">text</a><br>
                        Prázdny riadok → nový odstavec
                    </small>
                </div>
            </div>
            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">Zrušiť</a>
                <button type="submit" class="btn btn-primary">Uložiť článok</button>
            </div>
        </form>
    </div>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>