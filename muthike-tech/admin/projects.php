<?php
require_once '../includes/config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
// ... rest of your code

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // First get the image to delete if it's a local file
    $stmt = $pdo->prepare("SELECT image_url FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: projects.php?msg=deleted");
    exit();
}

// Handle toggle featured
if (isset($_GET['feature'])) {
    $id = intval($_GET['feature']);
    $stmt = $pdo->prepare("UPDATE projects SET is_featured = NOT is_featured WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: projects.php?msg=toggled");
    exit();
}

// Get all projects
$stmt = $pdo->query("SELECT * FROM projects ORDER BY is_featured DESC, created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Muthike Tech</title>
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
        
        /* Sidebar Styles */
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
        
        /* Main Content Styles */
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
        
        /* Action Buttons */
        .add-btn {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .add-btn:hover {
            background: #059669;
        }
        
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 12px;
            background: #f9fafb;
            color: #4b5563;
            font-weight: 600;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .project-image {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .badge {
            background: #f59e0b;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            display: inline-block;
        }
        
        .badge-featured {
            background: #10b981;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit {
            background: #2563eb;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.85rem;
        }
        
        .btn-edit:hover {
            background: #1e40af;
        }
        
        .btn-feature {
            background: #8b5cf6;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.85rem;
        }
        
        .btn-feature:hover {
            background: #7c3aed;
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.85rem;
        }
        
        .btn-delete:hover {
            background: #dc2626;
        }
        
        .msg {
            padding: 10px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .no-projects {
            text-align: center;
            padding: 40px;
            color: #6b7280;
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
                <h1>Manage Projects</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'deleted'): ?>
                <div class="msg">Project deleted successfully!</div>
            <?php elseif ($_GET['msg'] == 'toggled'): ?>
                <div class="msg">Featured status updated!</div>
            <?php endif; ?>
        <?php endif; ?>
        
        <a href="project-form.php" class="add-btn"><i class="fas fa-plus"></i> Add New Project</a>
        
        <div class="table-container">
            <?php if (count($projects) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Client</th>
                        <th>Technologies</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>" class="project-image" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        </td>
                        <td><strong><?php echo htmlspecialchars($project['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($project['category']); ?></td>
                        <td><?php echo htmlspecialchars($project['client']); ?></td>
                        <td>
                            <?php 
                            $techs = explode(', ', $project['technologies']);
                            foreach ($techs as $tech): 
                            ?>
                                <span style="background: #e5e7eb; padding: 2px 5px; border-radius: 3px; font-size: 0.75rem; margin-right: 3px;"><?php echo $tech; ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if ($project['is_featured']): ?>
                                <span class="badge badge-featured">Featured</span>
                            <?php else: ?>
                                <span style="color: #9ca3af;">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="project-form.php?id=<?php echo $project['id']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="?feature=<?php echo $project['id']; ?>" class="btn-feature" title="Toggle Featured"><i class="fas fa-star"></i> Feature</a>
                            <a href="?delete=<?php echo $project['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')" title="Delete"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="no-projects">
                    <i class="fas fa-briefcase" style="font-size: 48px; opacity: 0.5; margin-bottom: 20px;"></i>
                    <h3>No Projects Yet</h3>
                    <p>Click the "Add New Project" button to create your first project.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>