<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$article_id = $_GET['id'];
$article = getArticleById($pdo, $article_id);
$comments = getCommentsByArticleId($pdo, $article_id);

if (!$article) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Pre pridanie komentára sa musíte prihlásiť.";
        header("Location: ../login.php");
        exit();
    }
    
    $content = trim($_POST['content']);
    if (!empty($content)) {
        if (addComment($pdo, $content, $_SESSION['user_id'], $article_id)) {
            $_SESSION['success'] = "Komentár bol pridaný.";
            header("Location: article.php?id=$article_id");
            exit();
        } else {
            $_SESSION['error'] = "Nepodarilo sa pridať komentár.";
        }
    } else {
        $_SESSION['error'] = "Komentár nemôže byť prázdny.";
    }
}

if (isset($_GET['delete_comment'])) {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
    
    $comment_id = $_GET['delete_comment'];
    deleteComment($pdo, $comment_id);
    $_SESSION['success'] = "Komentár bol odstránený.";
    header("Location: article.php?id=$article_id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="article-full">
        <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="article-meta">
            Napísal/a <?php echo htmlspecialchars($article['username']); ?> dňa 
            <?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?>
            <?php if ($article['created_at'] != $article['updated_at']): ?>
                (Upravené <?php echo date('d.m.Y H:i', strtotime($article['updated_at'])); ?>)
            <?php endif; ?>
        </div>
        <div class="article-content">
            <?php echo parseMarkdown($article['content']); ?>
        </div>
    </div>

    <div class="comments-section">
        <h3 class="comments-title">Komentáre (<?php echo count($comments); ?>)</h3>
        <?php if (isLoggedIn()): ?>
            <form class="comment-form" method="post" action="article.php?id=<?php echo $article_id; ?>">
                <textarea name="content" placeholder="Napíšte svoj komentár..." required></textarea>
                <button type="submit" name="add_comment">Odoslať komentár</button>
            </form>
        <?php else: ?>
            <div class="comment-login-required">
                <p>Pre pridanie komentára sa musíte <a href="../login.php">prihlásiť</a>.</p>
            </div>
        <?php endif; ?>
    
        <ul class="comment-list">
            <?php foreach ($comments as $comment): ?>
                <li class="comment-item">
                    <div class="comment-meta">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                        <span><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    <?php if (isAdmin() || (isLoggedIn() && $_SESSION['user_id'] == $comment['user_id'])): ?>
                        <div class="comment-actions">
                            <a href="article.php?delete_comment=<?php echo $comment['id']; ?>&id=<?php echo $article_id; ?>" 
                            onclick="return confirm('Naozaj chcete odstrániť tento komentár?')">Odstrániť</a>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
