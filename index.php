<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

$order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';

$default_per_page = 10;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : $default_per_page;

$total_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$total_pages = ceil($total_articles / $per_page);

$current_page = min($current_page, $total_pages);

$offset = ($current_page - 1) * $per_page;
$stmt = $pdo->prepare("SELECT a.*, u.username 
                      FROM articles a 
                      JOIN users u ON a.user_id = u.id 
                      ORDER BY a.created_at $order 
                      LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="filter-section">
    <h3 class="filter-title">Zoradiť články:</h3>
    <form method="get" action="index.php">
        <div class="filter-options">
            <label>
                <input type="radio" name="order" value="desc" <?= $order === 'DESC' ? 'checked' : '' ?>>
                Od najnovších
            </label>
            <label>
                <input type="radio" name="order" value="asc" <?= $order === 'ASC' ? 'checked' : '' ?>>
                Od najstarších
            </label>
            <button type="submit" class="btn-filter">Zoradiť</button>
        </div>
        <input type="hidden" name="per_page" value="<?= $per_page ?>">
    </form>
    
    <div class="posts-controls">
        <form method="get" class="per-page-form">
            <label for="per_page">Počet článkov na stránku:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()">
                <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
            </select>
            <input type="hidden" name="order" value="<?= $order === 'ASC' ? 'asc' : 'desc' ?>">
        </form>
    </div>
</div>

<div class="article-list">
    <?php if (empty($articles)): ?>
        <p>Žiadne články neboli nájdené.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
                <div class="article-meta">
                    Napísal/a <?= htmlspecialchars($article['username']) ?> dňa 
                    <?= date('d.m.Y H:i', strtotime($article['created_at'])) ?>
                    <?php if ($article['created_at'] != $article['updated_at']): ?>
                        (Upravené <?= date('d.m.Y H:i', strtotime($article['updated_at'])) ?>)
                    <?php endif; ?>
                </div>
                <div class="article-excerpt">
                    <?= substr(htmlspecialchars($article['content']), 0, 200) ?>...
                </div>
                <a href="articles/article.php?id=<?= $article['id'] ?>" class="read-more">Čítať viac</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php if($current_page > 1): ?>
        <a href="?page=1&per_page=<?= $per_page ?>&order=<?= $order === 'ASC' ? 'asc' : 'desc' ?>" class="first-page">« Prvá</a>
        <a href="?page=<?= $current_page-1 ?>&per_page=<?= $per_page ?>&order=<?= $order === 'ASC' ? 'asc' : 'desc' ?>" class="prev-page">‹ Predch.</a>
    <?php endif; ?>

    <?php for($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
        <a href="?page=<?= $i ?>&per_page=<?= $per_page ?>&order=<?= $order === 'ASC' ? 'asc' : 'desc' ?>" 
           class="<?= $i == $current_page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if($current_page < $total_pages): ?>
        <a href="?page=<?= $current_page+1 ?>&per_page=<?= $per_page ?>&order=<?= $order === 'ASC' ? 'asc' : 'desc' ?>" class="next-page">Ďalšie ›</a>
        <a href="?page=<?= $total_pages ?>&per_page=<?= $per_page ?>&order=<?= $order === 'ASC' ? 'asc' : 'desc' ?>" class="last-page">Posledná »</a>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>