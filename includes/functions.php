<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getArticles($pdo, $order = 'DESC') {
    $valid_orders = ['ASC', 'DESC'];
    $order = in_array($order, $valid_orders) ? $order : 'DESC';
    
    $stmt = $pdo->prepare("SELECT articles.*, users.username 
                          FROM articles 
                          JOIN users ON articles.user_id = users.id 
                          ORDER BY created_at $order");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArticleById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT articles.*, users.username 
                          FROM articles 
                          JOIN users ON articles.user_id = users.id 
                          WHERE articles.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCommentsByArticleId($pdo, $article_id) {
    $stmt = $pdo->prepare("SELECT comments.*, users.username 
                          FROM comments 
                          JOIN users ON comments.user_id = users.id 
                          WHERE article_id = ? 
                          ORDER BY created_at DESC");
    $stmt->execute([$article_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($pdo, $content, $user_id, $article_id) {
    $stmt = $pdo->prepare("INSERT INTO comments (content, user_id, article_id) VALUES (?, ?, ?)");
    return $stmt->execute([$content, $user_id, $article_id]);
}

function deleteComment($pdo, $comment_id) {
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    return $stmt->execute([$comment_id]);
}

// Funkcia na pridávanie bold, kurzívy a odkazov do článku
function parseMarkdown($text) {
    $text = htmlspecialchars($text);
    $patterns = array(
        '/\*\*(.*?)\*\*/' => '<strong>$1</strong>',  // **tučné**
        '/\*(.*?)\*/'     => '<em>$1</em>',          // *kurzíva*
        '/\[(.*?)\]\((.*?)\)/' => '<a href="$2">$1</a>', // [text](url)
        '/\n/'           => '<br>'                   // Nový riadok
    );
    foreach ($patterns as $pattern => $replacement) {
        $text = preg_replace($pattern, $replacement, $text);
    }
    return $text;
}
?>

