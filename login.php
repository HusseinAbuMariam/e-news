<?php
session_start();
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>تسجيل الدخول - Global News Network</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="container">
  <h2>تسجيل الدخول</h2>
  <?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <label>اسم المستخدم:</label><br />
    <input type="text" name="username" required /><br />
    <label>كلمة المرور:</label><br />
    <input type="password" name="password" required /><br />
    <button type="submit">دخول</button>
  </form>
  <p>لا تملك حساب؟ <a href="register.php">سجّل الآن</a></p>
</div>
</body>
</html>
