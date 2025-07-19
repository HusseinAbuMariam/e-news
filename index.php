<?php
require 'db.php';
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>الرئيسية - E-News</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <!-- Header -->
  <header>
    <div class="container">
      <div class="logo">🌐 E-News</div>
      <nav>
        <ul>
          <li><a href="index.php">الرئيسية</a></li>
          <?php foreach($cats as $c): ?>
            <li><a href="category.php?id=<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="about.html">معلومات عنا</a></li>
          <li><a href="contact.html">اتصل بنا</a></li>
        </ul>
      </nav>
      <form class="search-form" action="search.php" method="GET">
        <input type="text" name="q" placeholder="ابحث عن خبر..." required />
        <button type="submit">بحث</button>
      </form>
      <div class="auth-buttons"><a href="login.php">دخول</a> | <a href="register.php">تسجيل</a></div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container">
    <!-- Breaking News -->
    <?php $bn = $pdo->query("SELECT * FROM articles ORDER BY published_date DESC LIMIT 1")->fetch(); ?>
    <section class="breaking-news">
      <article class="breaking-article">
        <?php if($bn['image_url']): ?><img src="<?= $bn['image_url'] ?>" alt="<?= htmlspecialchars($bn['title']) ?>" class="breaking-img" /><?php endif; ?>
        <h2>أخبار عاجلة</h2>
        <h3><a href="article.php?id=<?= $bn['article_id'] ?>"><?= htmlspecialchars($bn['title']) ?></a></h3>
        <p><?= nl2br(substr(htmlspecialchars($bn['content']),0,200)) ?>...</p>
      </article>
    </section>

    <!-- Featured Articles -->
    <section class="featured-articles">
      <h2>مقالات مميزة</h2>
      <div class="featured-grid">
        <?php
        $fa = $pdo->query("SELECT * FROM articles ORDER BY published_date DESC LIMIT 4")->fetchAll();
        foreach($fa as $f): ?>
        <article class="article-card">
          <?php if($f['image_url']): ?><img src="<?= $f['image_url'] ?>" alt="<?= htmlspecialchars($f['title']) ?>" /><?php endif; ?>
          <div>
            <h3><a href="article.php?id=<?= $f['article_id'] ?>"><?= htmlspecialchars($f['title']) ?></a></h3>
            <p><?= substr(htmlspecialchars($f['content']),0,100) ?>...</p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Latest News -->
    <section class="latest-news">
      <h2>أحدث الأخبار</h2>
      <?php
      $lst = $pdo->query("SELECT * FROM articles ORDER BY published_date DESC LIMIT 5,5")->fetchAll();
      foreach($lst as $ln): ?>
      <article class="article-card">
        <?php if($ln['image_url']): ?><img src="<?= $ln['image_url'] ?>" alt="" /><?php endif; ?>
        <div>
          <h3><a href="article.php?id=<?= $ln['article_id'] ?>"><?= htmlspecialchars($ln['title']) ?></a></h3>
          <p><?= substr(htmlspecialchars($ln['content']),0,120) ?>...</p>
        </div>
      </article>
      <?php endforeach; ?>
    </section>

    <!-- Category-wise Sections -->
    <?php foreach($cats as $cat): ?>
      <section class="category-section">
        <h2><?= htmlspecialchars($cat['category_name']) ?></h2>
        <?php
        $cr = $pdo->prepare("SELECT * FROM articles WHERE category_id=? ORDER BY published_date DESC LIMIT 3");
        $cr->execute([$cat['category_id']]);
        $catArts = $cr->fetchAll();
        if($catArts): foreach($catArts as $ca): ?>
          <article class="article-card">
            <?php if($ca['image_url']): ?><img src="<?= $ca['image_url'] ?>" alt="<?= htmlspecialchars($ca['title']) ?>" /><?php endif; ?>
            <div>
              <h3><a href="article.php?id=<?= $ca['article_id'] ?>"><?= htmlspecialchars($ca['title']) ?></a></h3>
              <p><?= substr(htmlspecialchars($ca['content']),0,80) ?>...</p>
            </div>
          </article>
        <?php endforeach; else: ?>
          <p>لا توجد مقالات في هذه الفئة بعد.</p>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
    <!-- Aside (Trending + Ad) -->
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

  <!-- Footer -->
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