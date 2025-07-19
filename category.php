<?php
require 'db.php';
// Fetch all categories for nav
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
// Get category ID and name
$catId = intval($_GET['id'] ?? 0);
$catName = $pdo
    ->prepare("SELECT category_name FROM categories WHERE category_id = ?")
    ->execute([$catId])
    ? $pdo->prepare("SELECT category_name FROM categories WHERE category_id = ?")->fetchColumn()
    : 'Unknown';
// Pagination setup
$total = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE category_id = ?");
$total->execute([$catId]);
$totalCount = $total->fetchColumn();
$limit = 5;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;
$totalPages = ceil($totalCount / $limit);
// Fetch articles for this category
$stmt = $pdo->prepare(
    "SELECT * FROM articles WHERE category_id = ? ORDER BY published_date DESC LIMIT ? OFFSET ?"
);
$stmt->bindValue(1, $catId, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($catName) ?> - Global News Network</title>
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
      <h2><?= htmlspecialchars($catName) ?></h2>
      <?php if($articles): foreach($articles as $art): ?>
        <article class="article-card">
          <?php if($art['image_url']): ?>
            <img src="<?= $art['image_url'] ?>" alt="<?= htmlspecialchars($art['title']) ?>" />
          <?php endif; ?>
          <div>
            <h3><a href="article.php?id=<?= $art['article_id'] ?>"><?= htmlspecialchars($art['title']) ?></a></h3>
            <p><?= substr(htmlspecialchars($art['content']),0,100) ?>...</p>
          </div>
        </article>
      <?php endforeach; else: ?>
        <p>لا توجد مقالات في هذه الفئة بعد.</p>
      <?php endif; ?>

      <div class="pagination">
        <?php if($page > 1): ?>
          <a href="category.php?id=<?= $catId ?>&page=<?= $page-1 ?>">السابق</a>
        <?php endif; ?>
        <span>صفحة <?= $page ?> من <?= $totalPages ?></span>
        <?php if($page < $totalPages): ?>
          <a href="category.php?id=<?= $catId ?>&page=<?= $page+1 ?>">التالي</a>
        <?php endif; ?>
      </div>
    </main>

    <aside>
      <h2>الأخبار الرائجة</h2>
      <ul>
        <?php
        $trend = $pdo->query(
          "SELECT article_id, title FROM articles ORDER BY published_date DESC LIMIT 5"
        )->fetchAll();
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