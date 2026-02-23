<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db_username = 'root';
$db_password = '';
$database = 'muthike_tech';

try {
    // First connect without database
    $pdo = new PDO("mysql:host=$host", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists with proper charset
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Now connect with database
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ============================================
    // CREATE ALL TABLES
    // ============================================
    
    // Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        role ENUM('admin', 'editor') DEFAULT 'editor',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Contact messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(200),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_read BOOLEAN DEFAULT 0,
        is_replied BOOLEAN DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Projects table
    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        client VARCHAR(100),
        project_url VARCHAR(500),
        image_url VARCHAR(500),
        technologies TEXT,
        completion_date DATE,
        is_featured BOOLEAN DEFAULT 0,
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Blog posts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(200) UNIQUE NOT NULL,
        content TEXT,
        excerpt TEXT,
        image_url VARCHAR(500),
        author VARCHAR(100),
        views INT DEFAULT 0,
        is_published BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Services table
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        icon VARCHAR(50),
        features TEXT,
        is_active BOOLEAN DEFAULT 1,
        display_order INT DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type VARCHAR(50) DEFAULT 'text'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // ============================================
    // INSERT DEFAULT DATA
    // ============================================
    
    // Insert default admin if not exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row['count'] == 0) {
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password_hash, email, role) VALUES 
                    ('admin', '$password_hash', 'admin@muthiketech.com', 'admin')");
    }
    
    // ============================================
    // FORCE DELETE EXISTING SERVICES AND ADD ALL 5
    // ============================================
    
    // First, clear all existing services
    $pdo->exec("DELETE FROM services");
    
    // Insert all 5 services with proper data
    $pdo->exec("INSERT INTO services (title, description, icon, features, display_order, is_active) VALUES 
        ('Front-End Development', 
         'Modern, responsive websites built with the latest technologies including HTML5, CSS3, JavaScript, and React.',
         'code', 
         '[\"HTML5/CSS3\", \"JavaScript/React\", \"Responsive Design\", \"UI/UX Implementation\", \"Performance Optimization\"]', 
         1, 1),
         
        ('Back-End Development', 
         'Powerful server-side applications and APIs using PHP, Node.js, and database technologies.',
         'server', 
         '[\"PHP/Laravel\", \"Node.js\", \"Database Design\", \"RESTful APIs\", \"Security Implementation\"]', 
         2, 1),
         
        ('Full-Stack Solutions', 
         'Complete web applications from concept to deployment, handling both front-end and back-end development.',
         'layers', 
         '[\"Custom Development\", \"API Integration\", \"Cloud Deployment\", \"DevOps\", \"Maintenance\"]', 
         3, 1),
         
        ('Website Management', 
         'Ongoing maintenance, security updates, content management, and 24/7 monitoring to keep your site running smoothly.',
         'globe', 
         '[\"Monthly Content Updates\", \"Security Monitoring\", \"Regular Backups\", \"Performance Optimization\", \"24/7 Uptime Monitoring\"]', 
         4, 1),
         
        ('Graphic Design', 
         'Professional branding, logo design, social media graphics, and marketing materials that make your business stand out.',
         'paint-brush', 
         '[\"Logo Design\", \"Brand Identity\", \"Social Media Graphics\", \"Business Cards & Flyers\", \"Banner Ads\"]', 
         5, 1)");
    
    // ============================================
    // INSERT SAMPLE PROJECTS
    // ============================================
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row['count'] == 0) {
        $pdo->exec("INSERT INTO projects (title, description, category, client, image_url, technologies, is_featured) VALUES 
            ('Transfast Logistics', 
             'Complete fleet management system with real-time tracking, driver management, and route optimization.',
             'Back-End', 
             'Transfast Logistics', 
             'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=800', 
             'PHP, MySQL, JavaScript', 
             1),
             
            ('City Hospital Website', 
             'Modern responsive website with online appointment system, patient portal, and medical information.',
             'Front-End', 
             'City Hospital', 
             'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800', 
             'React, Node.js, MongoDB', 
             1),
             
            ('E-Commerce Platform', 
             'Full-featured online store with payment integration, inventory management, and customer accounts.',
             'Full-Stack', 
             'ShopKenya', 
             'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800', 
             'Laravel, Vue.js, MySQL', 
             1)");
    }
    
    // ============================================
    // INSERT SAMPLE BLOG POSTS
    // ============================================
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blog_posts");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row['count'] == 0) {
        $pdo->exec("INSERT INTO blog_posts (title, slug, content, excerpt, author, is_published) VALUES 
            ('Why Your Business Needs Professional Web Development', 
             'why-business-needs-professional-web-development',
             'In today digital age, your website is often the first interaction potential customers have with your business. A professional, well-designed website builds trust, showcases your expertise, and converts visitors into customers. Here are the key reasons why investing in professional web development is crucial for your success...',
             'Learn why a professional website is essential for your business growth and online presence.',
             'James Muthike',
             1),
             
            ('The Importance of Website Maintenance', 
             'importance-of-website-maintenance',
             'Many business owners think that once a website is launched, the work is done. However, websites require ongoing maintenance to remain secure, fast, and functional. Regular updates, security patches, and content refreshes are essential to protect your investment and keep your visitors engaged...',
             'Discover why regular website maintenance is crucial for security, performance, and user experience.',
             'Sarah Wanjiku',
             1),
             
            ('How to Choose the Right Color Scheme for Your Brand', 
             'how-to-choose-right-color-scheme-for-brand',
             'Colors evoke emotions and communicate messages without words. Choosing the right color scheme for your brand is a critical design decision that affects how customers perceive your business. This guide will help you understand color psychology and select the perfect palette for your brand identity...',
             'A guide to understanding color psychology and selecting the perfect palette for your brand.',
             'Grace Muthoni',
             1)");
    }
    
    // ============================================
    // INSERT SETTINGS
    // ============================================
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row['count'] == 0) {
        $pdo->exec("INSERT INTO settings (setting_key, setting_value, setting_type) VALUES 
            ('site_name', 'Muthike Tech Solutions', 'text'),
            ('site_email', 'info@muthiketech.com', 'email'),
            ('site_phone', '+254 700 000 000', 'text'),
            ('site_address', 'Nairobi, Kenya', 'text'),
            ('social_facebook', 'https://facebook.com/muthiketech', 'url'),
            ('social_twitter', 'https://twitter.com/muthiketech', 'url'),
            ('social_linkedin', 'https://linkedin.com/company/muthiketech', 'url')");
    }
    
} catch (PDOException $e) {
    die("<div style='padding: 20px; background: #fee; color: #c33; border-left: 4px solid #c33; margin: 20px; font-family: Arial;'>
            <strong>❌ Database Error:</strong> " . $e->getMessage() . "<br><br>
            <small>Please make sure MySQL is running in XAMPP.</small>
         </div>");
}

// Make $pdo available globally
$GLOBALS['pdo'] = $pdo;
?>