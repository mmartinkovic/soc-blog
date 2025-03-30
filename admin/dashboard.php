<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

$stmt = $pdo->query("SELECT COUNT(*) as total_articles FROM articles");
$total_articles = $stmt->fetch(PDO::FETCH_ASSOC)['total_articles'];

$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $pdo->query("SELECT COUNT(*) as total_comments FROM comments");
$total_comments = $stmt->fetch(PDO::FETCH_ASSOC)['total_comments'];

$articles = getArticles($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Blog Systen</title>
</head>
<body>
<div class="container admin-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <div class="admin-actions">
            <a href="add_article.php" class="btn btn-primary">Pridať nový článok</a>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-label">Celkom článkov</div>
            <div class="stat-value"><?php echo $total_articles; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Celkom používateľov</div>
            <div class="stat-value"><?php echo $total_users; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Celkom komentárov</div>
            <div class="stat-value"><?php echo $total_comments; ?></div>
        </div>
    </div>
    <h2>Najnovšie články</h2>
    <div class="article-list">
        <?php if (empty($articles)): ?>
            <div class="no-articles">
                <p>Žiadne články neboli nájdené.</p>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($articles, 0, 6) as $article): ?>
                <div class="article-card">
                    <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                    <div class="article-meta">
                        Napísal <?php echo htmlspecialchars($article['username']); ?> dňa 
                        <?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?>
                        <?php if ($article['created_at'] != $article['updated_at']): ?>
                            (Upravené <?php echo date('d.m.Y H:i', strtotime($article['updated_at'])); ?>)
                        <?php endif; ?>
                    </div>
                    <div class="article-excerpt">
                        <?php echo substr(htmlspecialchars($article['content']), 0, 200); ?>...
                    </div>
                    <div class="article-actions">
                        <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn">Upraviť</a>
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Naozaj chcete odstrániť tento článok?')">Odstrániť</a>
                        <a href="../articles/article.php?id=<?php echo $article['id']; ?>" class="btn">Zobraziť</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
</body>
</html>
