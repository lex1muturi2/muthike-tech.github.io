<?php
$page_title = 'Home';
include 'includes/config.php';
include 'includes/header.php';

// Get all active services
$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get featured projects
$stmt = $pdo->query("SELECT * FROM projects WHERE is_featured = 1 AND is_active = 1 ORDER BY created_at DESC LIMIT 3");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get latest blog posts
$stmt = $pdo->query("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT 3");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>We Build Powerful Web Solutions</h1>
            <p>Professional front-end, back-end, and full-stack development services to help your business grow.</p>
            <div class="hero-buttons">
                <a href="services.php" class="btn btn-primary">Our Services</a>
                <a href="contact.php" class="btn btn-secondary">Get in Touch</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section - NOW WITH ALL 5 SERVICES -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>What We Do</h2>
            <p>Comprehensive web solutions for your business</p>
        </div>
        
        <div class="services-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            <?php 
            $count = 0;
            foreach ($services as $service): 
                $count++;
            ?>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-<?php echo $service['icon']; ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <?php if ($service['features']): ?>
                    <?php $features = json_decode($service['features'], true); ?>
                    <ul class="service-features">
                        <?php foreach (array_slice($features, 0, 4) as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php 
            // Add a clearfix after every 3 items
            if ($count % 3 == 0 && $count < count($services)): 
                echo '</div><div class="services-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px;">';
            endif;
            endforeach; 
            ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section section-bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose Us</h2>
            <p>We deliver quality solutions that drive results</p>
        </div>
        
        <div class="features-grid">
            <div class="features-content">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Modern Technology</h3>
                        <p>We use the latest frameworks and best practices to build fast, secure applications.</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Clean Code</h3>
                        <p>Our code is well-structured, documented, and follows industry standards.</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Ongoing Support</h3>
                        <p>We don't just build and leave - we provide continuous support and maintenance.</p>
                    </div>
                </div>
            </div>
            
            <div class="feature-image">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=800" alt="Development team">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Projects Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">30+</div>
                <div class="stat-label">Happy Clients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">5+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Website Management Plans -->
<section class="section section-bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Website Management Plans</h2>
            <p>Affordable maintenance packages for your peace of mind</p>
        </div>
        
        <div class="grid-3">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Basic Care</h3>
                <p style="font-size: 2rem; color: var(--primary-color);">$150<span style="font-size: 1rem;">/mo</span></p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Monthly content updates (2 hours)</li>
                    <li><i class="fas fa-check"></i> Security monitoring</li>
                    <li><i class="fas fa-check"></i> Weekly backups</li>
                    <li><i class="fas fa-check"></i> Email support</li>
                </ul>
                <a href="contact.php" class="btn btn-primary" style="margin-top: 20px;">Get Started</a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Professional</h3>
                <p style="font-size: 2rem; color: var(--primary-color);">$350<span style="font-size: 1rem;">/mo</span></p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Weekly content updates (5 hours)</li>
                    <li><i class="fas fa-check"></i> Priority security patching</li>
                    <li><i class="fas fa-check"></i> Daily backups</li>
                    <li><i class="fas fa-check"></i> Performance optimization</li>
                    <li><i class="fas fa-check"></i> Priority support (24h response)</li>
                </ul>
                <a href="contact.php" class="btn btn-primary" style="margin-top: 20px;">Get Started</a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h3>Enterprise</h3>
                <p style="font-size: 2rem; color: var(--primary-color);">$750<span style="font-size: 1rem;">/mo</span></p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Unlimited content updates</li>
                    <li><i class="fas fa-check"></i> Advanced security suite</li>
                    <li><i class="fas fa-check"></i> Real-time backups</li>
                    <li><i class="fas fa-check"></i> Speed optimization</li>
                    <li><i class="fas fa-check"></i> 24/7 priority support</li>
                    <li><i class="fas fa-check"></i> Monthly performance report</li>
                </ul>
                <a href="contact.php" class="btn btn-primary" style="margin-top: 20px;">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Graphic Design Portfolio -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Design Work</h2>
            <p>Creative designs that tell your brand story</p>
        </div>
        
        <div class="grid-4">
            <div class="portfolio-card">
                <div class="portfolio-image">
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?w=400" alt="Logo Design">
                </div>
                <div class="portfolio-content">
                    <h3>Logo Design</h3>
                    <p>Unique brand identities</p>
                </div>
            </div>
            
            <div class="portfolio-card">
                <div class="portfolio-image">
                    <img src="https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=400" alt="Branding">
                </div>
                <div class="portfolio-content">
                    <h3>Brand Identity</h3>
                    <p>Complete brand packages</p>
                </div>
            </div>
            
            <div class="portfolio-card">
                <div class="portfolio-image">
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?w=400" alt="Social Media">
                </div>
                <div class="portfolio-content">
                    <h3>Social Media Graphics</h3>
                    <p>Engaging social content</p>
                </div>
            </div>
            
            <div class="portfolio-card">
                <div class="portfolio-image">
                    <img src="https://images.unsplash.com/photo-1611926653672-1f1b91a9d7f2?w=400" alt="Print Design">
                </div>
                <div class="portfolio-content">
                    <h3>Print Materials</h3>
                    <p>Business cards, flyers, brochures</p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="contact.php" class="btn btn-primary">Request a Quote</a>
        </div>
    </div>
</section>

<?php if ($projects): ?>
<!-- Featured Projects -->
<section class="section section-bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Featured Projects</h2>
            <p>Some of our recent work</p>
        </div>
        
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
                    <div class="portfolio-tech">
                        <?php $techs = explode(', ', $project['technologies']); ?>
                        <?php foreach ($techs as $tech): ?>
                            <span class="tech-tag"><?php echo $tech; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($posts): ?>
<!-- Latest Blog Posts -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Latest from Our Blog</h2>
            <p>Insights and updates from our team</p>
        </div>
        
        <div class="grid-3">
            <?php foreach ($posts as $post): ?>
            <div class="blog-card">
                <div class="blog-image">
                    <img src="<?php echo htmlspecialchars($post['image_url'] ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                    <h3><a href="blog-post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <a href="blog-post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>