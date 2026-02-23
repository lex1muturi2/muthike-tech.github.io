<?php
$page_title = 'Our Services';
include 'includes/config.php';
include 'includes/header.php';

// Get all services
$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Comprehensive web development solutions for your business</p>
        </div>
        
        <div class="grid-3">
            <?php foreach ($services as $service): ?>
            <div class="service-card" id="<?php echo strtolower(str_replace(' ', '', $service['title'])); ?>">
                <div class="service-icon">
                    <i class="fas fa-<?php echo $service['icon']; ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <?php if ($service['features']): ?>
                    <?php $features = json_decode($service['features'], true); ?>
                    <ul class="service-features">
                        <?php foreach ($features as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="contact.php" class="btn btn-outline" style="margin-top: 20px;">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Our Development Process</h2>
            <p>How we bring your ideas to life</p>
        </div>
        
        <div class="grid-4">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>1. Discovery</h3>
                <p>We discuss your requirements, goals, and vision for the project.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-pencil-ruler"></i>
                </div>
                <h3>2. Planning</h3>
                <p>We create wireframes, prototypes, and project timelines.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>3. Development</h3>
                <p>Our developers build your project with clean, efficient code.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>4. Launch & Support</h3>
                <p>We deploy your site and provide ongoing maintenance.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Technologies We Use</h2>
            <p>Modern tools for modern solutions</p>
        </div>
        
        <div class="grid-4" style="text-align: center;">
            <div>
                <i class="fab fa-html5" style="font-size: 3rem; color: #e34c26;"></i>
                <p style="margin-top: 10px;">HTML5</p>
            </div>
            <div>
                <i class="fab fa-css3-alt" style="font-size: 3rem; color: #264de4;"></i>
                <p style="margin-top: 10px;">CSS3</p>
            </div>
            <div>
                <i class="fab fa-js" style="font-size: 3rem; color: #f0db4f;"></i>
                <p style="margin-top: 10px;">JavaScript</p>
            </div>
            <div>
                <i class="fab fa-php" style="font-size: 3rem; color: #777bb4;"></i>
                <p style="margin-top: 10px;">PHP</p>
            </div>
            <div>
                <i class="fas fa-database" style="font-size: 3rem; color: #00758f;"></i>
                <p style="margin-top: 10px;">MySQL</p>
            </div>
            <div>
                <i class="fab fa-react" style="font-size: 3rem; color: #61dafb;"></i>
                <p style="margin-top: 10px;">React</p>
            </div>
            <div>
                <i class="fab fa-node" style="font-size: 3rem; color: #3c873a;"></i>
                <p style="margin-top: 10px;">Node.js</p>
            </div>
            <div>
                <i class="fab fa-laravel" style="font-size: 3rem; color: #ff2d20;"></i>
                <p style="margin-top: 10px;">Laravel</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>