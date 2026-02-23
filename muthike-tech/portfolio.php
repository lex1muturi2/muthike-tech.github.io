<?php
$page_title = 'Portfolio';
include 'includes/config.php';
include 'includes/header.php';

$category = $_GET['category'] ?? 'all';
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Build query
$query = "SELECT * FROM projects WHERE is_active = 1";
$count_query = "SELECT COUNT(*) as total FROM projects WHERE is_active = 1";

if ($category != 'all') {
    $query .= " AND category = :category";
    $count_query .= " AND category = :category";
}

$query .= " ORDER BY is_featured DESC, created_at DESC LIMIT $offset, $limit";

// Get projects
$stmt = $pdo->prepare($query);
if ($category != 'all') {
    $stmt->execute([':category' => $category]);
} else {
    $stmt->execute();
}
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count for pagination
$stmt = $pdo->prepare($count_query);
if ($category != 'all') {
    $stmt->execute([':category' => $category]);
} else {
    $stmt->execute();
}
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total / $limit);

// Get unique categories
$stmt = $pdo->query("SELECT DISTINCT category FROM projects WHERE is_active = 1");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Portfolio</h2>
            <p>Explore some of our recent projects</p>
        </div>
        
        <!-- Category Filter -->
        <div style="text-align: center; margin-bottom: 40px;">
            <a href="?category=all" class="btn <?php echo $category == 'all' ? 'btn-primary' : 'btn-outline'; ?>" style="margin: 0 5px;">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category=<?php echo urlencode($cat); ?>" class="btn <?php echo $category == $cat ? 'btn-primary' : 'btn-outline'; ?>" style="margin: 0 5px;"><?php echo $cat; ?></a>
            <?php endforeach; ?>
        </div>
        
        <!-- Projects Grid -->
        <?php if ($projects): ?>
        <div class="grid-3">
            <?php foreach ($projects as $project): ?>
            <div class="portfolio-card">
                <div class="portfolio-image">
                    <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                </div>
                <div class="portfolio-content">
                    <span class="category"><?php echo htmlspecialchars($project['category']); ?></span>
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                    <?php if ($project['technologies']): ?>
                        <div class="portfolio-tech">
                            <?php $techs = explode(', ', $project['technologies']); ?>
                            <?php foreach ($techs as $tech): ?>
                                <span class="tech-tag"><?php echo $tech; ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($project['project_url']): ?>
                        <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" class="card-link">Live Demo <i class="fas fa-external-link-alt"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?category=<?php echo $category; ?>&page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p>No projects found in this category.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>