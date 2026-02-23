<?php
$page_title = 'Blog';
include 'includes/config.php';

$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT $offset, $limit");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT COUNT(*) as total FROM blog_posts WHERE is_published = 1");
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total / $limit);

include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Blog</h2>
            <p>Insights, tutorials, and news from our team</p>
        </div>
        
        <?php if ($posts): ?>
        <div class="grid-3">
            <?php foreach ($posts as $post): ?>
            <div class="blog-card">
                <div class="blog-image">
                    <img src="<?php echo htmlspecialchars($post['image_url'] ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                        <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
                    </div>
                    <h3><a href="blog-post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p><?php echo htmlspecialchars($post['excerpt'] ?? substr(strip_tags($post['content']), 0, 150) . '...'); ?></p>
                    <a href="blog-post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p>No blog posts yet. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>