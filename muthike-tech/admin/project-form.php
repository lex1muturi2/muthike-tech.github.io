<?php
require_once '../includes/config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
// ... rest of your code

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$project = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $client = $_POST['client'];
    $project_url = $_POST['project_url'];
    $technologies = $_POST['technologies'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle image upload if file is provided
    $image_url = $project ? $project['image_url'] : 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800';
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['image_file']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $image_url = 'uploads/' . $file_name;
        }
    }
    
    try {
        if ($id) {
            // Update existing project
            $sql = "UPDATE projects SET title=?, description=?, category=?, client=?, project_url=?, image_url=?, technologies=?, is_featured=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $description, $category, $client, $project_url, $image_url, $technologies, $is_featured, $id]);
            $message = "Project updated successfully!";
        } else {
            // Insert new project
            $sql = "INSERT INTO projects (title, description, category, client, project_url, image_url, technologies, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $description, $category, $client, $project_url, $image_url, $technologies, $is_featured]);
            $message = "Project added successfully!";
            
            // Get the new ID for redirect
            $id = $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Project - Muthike Tech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            display: flex;
        }
        
        .sidebar {
            width: 260px;
            background: #1f2937;
            color: white;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #374151;
        }
        
        .sidebar-header h2 {
            font-size: 1.3rem;
        }
        
        .sidebar-header p {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #374151;
            color: white;
        }
        
        .sidebar-menu a i {
            width: 20px;
        }
        
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 20px 30px;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .page-title h1 {
            font-size: 1.8rem;
            color: #1f2937;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info span {
            color: #6b7280;
        }
        
        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            background: #dc2626;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            max-width: 800px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input {
            width: auto;
        }
        
        .btn-save {
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-save:hover {
            background: #1e40af;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
        }
        
        .message {
            padding: 10px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .current-image {
            margin: 10px 0;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
        }
        
        .current-image img {
            max-width: 200px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .file-info {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Muthike Tech</h2>
            <p>Admin Panel</p>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
            <a href="projects.php" class="active"><i class="fas fa-briefcase"></i> Projects</a>
            <a href="blog-posts.php"><i class="fas fa-blog"></i> Blog Posts</a>
            <a href="manage-services.php"><i class="fas fa-cog"></i> Services</a>
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
            <a href="settings.php"><i class="fas fa-wrench"></i> Settings</a>
        </div>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h1><?php echo $id ? 'Edit' : 'Add'; ?> Project</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" value="<?php echo $project ? htmlspecialchars($project['title']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required><?php echo $project ? htmlspecialchars($project['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Front-End" <?php echo ($project && $project['category'] == 'Front-End') ? 'selected' : ''; ?>>Front-End Development</option>
                        <option value="Back-End" <?php echo ($project && $project['category'] == 'Back-End') ? 'selected' : ''; ?>>Back-End Development</option>
                        <option value="Full-Stack" <?php echo ($project && $project['category'] == 'Full-Stack') ? 'selected' : ''; ?>>Full-Stack Development</option>
                        <option value="Mobile App" <?php echo ($project && $project['category'] == 'Mobile App') ? 'selected' : ''; ?>>Mobile App</option>
                        <option value="E-Commerce" <?php echo ($project && $project['category'] == 'E-Commerce') ? 'selected' : ''; ?>>E-Commerce</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" name="client" value="<?php echo $project ? htmlspecialchars($project['client']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Project URL (Optional)</label>
                    <input type="url" name="project_url" value="<?php echo $project ? htmlspecialchars($project['project_url']) : ''; ?>" placeholder="https://example.com">
                </div>
                
                <div class="form-group">
                    <label>Technologies (comma separated)</label>
                    <input type="text" name="technologies" value="<?php echo $project ? htmlspecialchars($project['technologies']) : ''; ?>" placeholder="PHP, MySQL, JavaScript, React">
                </div>
                
                <div class="form-group">
                    <label>Project Image</label>
                    <input type="file" name="image_file" accept="image/*">
                    <div class="file-info">Leave empty to keep current image. Max size: 5MB</div>
                </div>
                
                <?php if ($project && $project['image_url']): ?>
                <div class="current-image">
                    <label>Current Image:</label><br>
                    <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="Current project image">
                </div>
                <?php endif; ?>
                
                <div class="form-group checkbox-group">
                    <input type="checkbox" name="is_featured" id="is_featured" <?php echo ($project && $project['is_featured']) ? 'checked' : ''; ?>>
                    <label for="is_featured">Feature this project on homepage</label>
                </div>
                
                <div>
                    <button type="submit" class="btn-save"><?php echo $id ? 'Update' : 'Save'; ?> Project</button>
                    <a href="projects.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>