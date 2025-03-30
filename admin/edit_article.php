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
$article = getArticleById($pdo, $article_id);

if (!$article) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Vyplňte všetky polia.";
        header("Location: edit_article.php?id=$article_id");
        exit();
    } else {
        $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
        if ($stmt->execute([$title, $content, $article_id])) {
            $_SESSION['success'] = "Článok bol úspešne aktualizovaný.";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Nepodarilo sa aktualizovať článok.";
            header("Location: edit_article.php?id=$article_id");
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
    <link rel="stylesheet" href="/css/style.css">
    <title>Blog System</title>
</head>
<body>
    <div class="article-form">
        <div class="edit-form-header">
            <h2>Upraviť článok</h2>
            <div class="last-updated">
                Naposledy upravené: <?php echo date('d.m.Y H:i', strtotime($article['updated_at'])); ?>
            </div>
        </div>
        <form method="post">
            <div class="form-group">
                <label for="title">Názov článku</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Obsah článku</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
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
                <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
            </div>
        </form>
    </div>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
