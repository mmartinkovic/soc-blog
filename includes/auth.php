<?php
require_once 'config.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Neplatné prihlasovacie údaje.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Vyplňte všetky polia.";
        header("Location: login.php");
        exit();
    }
}

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Vyplňte všetky polia.";
        header("Location: register.php");
        exit();
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Heslá sa nezhodujú.";
        header("Location: register.php");
        exit();
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = "Heslo musí mať aspoň 6 znakov.";
        header("Location: register.php");
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Používateľské meno už existuje.";
            header("Location: register.php");
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            
            if ($stmt->execute([$username, $hashed_password])) {
                $_SESSION['success'] = "Registrácia úspešná. Prosím prihláste sa.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Registrácia zlyhala. Skúste to znova.";
                header("Location: register.php");
                exit();
            }
        }
    }
}
?>