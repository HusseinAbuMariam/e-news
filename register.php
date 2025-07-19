<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$username || !$email || !$password || !$password_confirm) {
        $error = "يرجى ملء كل الحقول";
    } elseif ($password !== $password_confirm) {
        $error = "كلمة المرور وتأكيدها غير متطابقين";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "اسم المستخدم أو البريد الإلكتروني مستخدم مسبقاً";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->execute([$username, $email, $hash]);
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>تسجيل مستخدم جديد - Global News Network</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="container">
  <h2>تسجيل مستخدم جديد</h2>
  <?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <label>اسم المستخدم:</label><br />
    <input type="text" name="username" required /><br />

    <label>البريد الإلكتروني:</label><br />
    <input type="email" name="email" required /><br />

    <label>كلمة المرور:</label><br />
    <input type="password" name="password" required /><br />

    <label>تأكيد كلمة المرور:</label><br />
    <input type="password" name="password_confirm" required /><br />

    <button type="submit">تسجيل</button>
  </form>
  <p>هل لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
</div>
</body>
</html>
