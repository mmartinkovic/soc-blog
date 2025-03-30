<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';

if (isset($_SESSION['error'])) {
    echo '<div class="alert error">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>

<div class="auth-form">
    <h2>Prihlásiť sa</h2>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Používateľské meno</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Heslo</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="form-submit">Prihlásiť sa</button>
    </form>
    <div class="form-footer">
        Nemáte účet? <a href="register.php">Zaregistrujte sa</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>