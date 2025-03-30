<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';
?>

<div class="auth-form">
    <h2>Registrácia</h2>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Používateľské meno</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Heslo</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Potvrdenie hesla</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="register" class="form-submit">Registrovať sa</button>
    </form>
    <div class="form-footer">
        Máte účet? <a href="login.php">Prihláste sa</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>