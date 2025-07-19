<?php
require 'db.php';
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
  "SELECT a.*, u.username, c.category_name
   FROM articles a
   LEFT JOIN users u ON a.author_id=u.user_id
   LEFT JOIN categories c ON a.category_id=c.category_id
   WHERE a.article_id=?"
);
$stmt->execute([$id]);
$art = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($art['title']) ?> - Global News Network</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">🌐 Global News Network</div>
      <nav><ul>
        <li><a href="index.php">الرئيسية</a></li>
        <?php foreach($categories as $c): ?>
          <li><a href="category.php?id=<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="about.html">معلومات عنا</a></li>
        <li><a href="contact.html">اتصل بنا</a></li>
      </ul></nav>
      <form class="search-form" action="search.php" method="GET">
        <input type="text" name="q" placeholder="ابحث عن خبر..." required />
        <button type="submit">بحث</button>
      </form>
      <div class="auth-buttons"><a href="login.php">دخول</a> | <a href="register.php">تسجيل</a></div>
    </div>
  </header>

  <div class="container">
    <main>
      <article class="full-article">
        <h2><?= htmlspecialchars($art['title']) ?></h2>
        <p class="article-meta">
          الفئة: <a href="category.php?id=<?= $art['category_id'] ?>"><?= htmlspecialchars($art['category_name']) ?></a>
          | بتاريخ <?= date('Y-m-d',strtotime($art['published_date'])) ?>
          <?php if($art['username']): ?> بواسطة <?= htmlspecialchars($art['username']) ?><?php endif; ?>
        </p>
        <?php if($art['image_url']): ?><img src="<?= $art['image-20150810-11062-1dh3ydx.avif'] ?>" alt="" class="article-img" /><?php endif; ?>
        <div class="article-content"><?= nl2br(htmlspecialchars($art['content'])) ?></div>
        <p><a href="category.php?id=<?= $art['category_id'] ?>">&laquo; العودة إلى <?= htmlspecialchars($art['category_name']) ?></a></p>
      </article>
    </main>

    <aside>
      <h2>الأخبار الرائجة</h2>
      <ul>
        <?php
        $trend = $pdo->query("SELECT article_id,title FROM articles ORDER BY published_date DESC LIMIT 5")->fetchAll();
        foreach($trend as $t): ?>
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
      <p>© 2025 Global News Network. جميع الحقوق محفوظة.</p>
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