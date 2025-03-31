<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

$order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';
$articles = getArticles($pdo, $order);
?>

<div class="filter-section">
    <h3 class="filter-title">Zoradiť články:</h3>
    <form method="get" action="index.php">
        <div class="filter-options">
            <label>
                <input type="radio" name="order" value="desc" <?php echo $order === 'DESC' ? 'checked' : ''; ?>>
                Od najnovších
            </label>
            <label>
                <input type="radio" name="order" value="asc" <?php echo $order === 'ASC' ? 'checked' : ''; ?>>
                Od najstarších
            </label>
            <button type="submit" class="btn-filter">Zoradiť</button>
        </div>
    </form>
</div>

<div class="article-list">
    <?php if (empty($articles)): ?>
        <p>Žiadne články neboli nájdené.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <h2 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h2>
                <div class="article-meta">
                    Napísal/a <?php echo htmlspecialchars($article['username']); ?> dňa 
                    <?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?>
                    <?php if ($article['created_at'] != $article['updated_at']): ?>
                    (Upravené <?php echo date('d.m.Y H:i', strtotime($article['updated_at'])); ?>)
                    <?php endif; ?>
                </div>
                <div class="article-excerpt">
                    <?php echo substr(htmlspecialchars($article['content']), 0, 200); ?>...
                </div>
                <a href="articles/article.php?id=<?php echo $article['id']; ?>" class="read-more">Čítať viac</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>