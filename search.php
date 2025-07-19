<?php
require 'db.php';
$q = trim($_GET['q'] ?? '');
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$articles = [];
if ($q !== '') {
  $like = "%$q%";
  $stmt = $pdo->prepare(
    "SELECT a.article_id,a.title,SUBSTRING(a.content,1,150) AS snippet,
      c.category_name,u.username
     FROM articles a
     LEFT JOIN categories c ON a.category_id=c.category_id
     LEFT JOIN users u ON a.author_id=u.user_id
     WHERE a.title LIKE ? OR a.content LIKE ?
     ORDER BY a.published_date DESC"
  );
  $stmt->execute([$like,$like]);
  $articles = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>نتائج البحث - E-News</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">🌐 E-News</div>
      <nav><ul>
        <li><a href="index.php">الرئيسية</a></li>
        <?php foreach($categories as $c): ?>
          <li><a href="category.php?id=<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></a></li>
        <?php endforeach; ?>
      </ul></nav>
      <form class="search-form" action="search.php" method="GET">
        <input type="text" name="q" placeholder="ابحث عن خبر..." value="<?= htmlspecialchars($q) ?>" />
        <button type="submit">بحث</button>
      </form>
      <div class="auth-buttons"><a href="login.php">دخول</a> | <a href="register.php">تسجيل</a></div>
    </div>
  </header>

  <div class="container">
    <main>
      <h2>نتائج البحث عن "<?= htmlspecialchars($q) ?>"</h2>
      <?php if($articles): foreach($articles as $art): ?>
        <article class="article-card">
          <h3><a href="article.php?id=<?= $art['article_id'] ?>"><?= htmlspecialchars($art['title']) ?></a></h3>
          <p><?= htmlspecialchars($art['snippet']) ?>...</p>
        </article>
      <?php endforeach; else: ?>
        <p>لم يتم العثور على نتائج.</p>
      <?php endif; ?>
    </main>

    <aside>
      <h2>الأخبار الرائجة</h2>
      <ul>
        <?php foreach($pdo->query("SELECT article_id,title FROM articles ORDER BY published_date DESC LIMIT 5")->fetchAll() as $t): ?>
          <li><a href="article.php?id=<?= $t['article_id'] ?>"><?= htmlspecialchars($t['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="ad-box">
        <h3>إعلان</h3>
        <p>مكان الإعلان هنا.</p>
      </div>
    </aside>
  </div>

  <footer>
    <div class="container">
      <p>© 2025 E-News. جميع الحقوق محفوظة.</p>
      <div class="quick-links">
        <a href="privacy.html">سياسة الخصوصية</a>
        <a href="terms.html">شروط الخدمة</a>
        <a href="sitemap.xml">خريطة الموقع</a>
      </div>
      <div class="social-icons">
        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </footer>
</body>
</html>