<?php
$page_title = 'Blog Post';
include 'includes/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Update view count
$pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?")->execute([$id]);

// Get post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ? AND is_published = 1");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog.php");
    exit();
}

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <article style="max-width: 800px; margin: 0 auto;">
            <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 20px;"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div style="display: flex; gap: 20px; color: #6b7280; margin-bottom: 30px;">
                <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?></span>
                <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
            </div>
            
            <?php if ($post['image_url']): ?>
            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; border-radius: 10px; margin-bottom: 30px;">
            <?php endif; ?>
            
            <div style="line-height: 1.8; font-size: 1.1rem;">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
            
            <div style="margin-top: 40px;">
                <a href="blog.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Blog</a>
            </div>
        </article>
    </div>
</section>

<?php include 'includes/footer.php'; ?>