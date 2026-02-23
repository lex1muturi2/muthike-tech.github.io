<?php
require_once '../includes/config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
// ... rest of your code


// Get counts
$stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$unread_messages = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
$total_projects = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM blog_posts WHERE is_published = 1");
$published_posts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get recent messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
$recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Muthike Tech</title>
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
        
        /* Sidebar */
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
        
        /* Main Content */
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
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #dc2626;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .stat-card h3 {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Recent Messages */
        .recent-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-header h2 {
            font-size: 1.3rem;
        }
        
        .view-all {
            color: #2563eb;
            text-decoration: none;
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
        
        .unread {
            font-weight: 600;
            background: #f0f9ff;
        }
        
        .badge {
            background: #f59e0b;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        
        .btn-small {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.85rem;
        }
        
        .btn-view {
            background: #2563eb;
        }
        
        .btn-view:hover {
            background: #1e40af;
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
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="messages.php"><i class="fas fa-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge"><?php echo $unread_messages; ?></span><?php endif; ?></a>
            <a href="projects.php"><i class="fas fa-briefcase"></i> Projects</a>
            <a href="blog-posts.php"><i class="fas fa-blog"></i> Blog Posts</a>
            <a href="manage-services.php"><i class="fas fa-cog"></i> Services</a>
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
            <a href="settings.php"><i class="fas fa-wrench"></i> Settings</a>
        </div>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Unread Messages</h3>
                <div class="stat-number"><?php echo $unread_messages; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Total Projects</h3>
                <div class="stat-number"><?php echo $total_projects; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Published Posts</h3>
                <div class="stat-number"><?php echo $published_posts; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Users</h3>
                <div class="stat-number"><?php echo $total_users; ?></div>
            </div>
        </div>
        
        <div class="recent-section">
            <div class="section-header">
                <h2>Recent Messages</h2>
                <a href="messages.php" class="view-all">View All →</a>
            </div>
            
            <?php if ($recent_messages): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_messages as $msg): ?>
                    <tr class="<?php echo !$msg['is_read'] ? 'unread' : ''; ?>">
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject'] ?: 'No subject'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if (!$msg['is_read']): ?>
                                <span class="badge" style="background: #f59e0b;">New</span>
                            <?php elseif ($msg['is_replied']): ?>
                                <span class="badge" style="background: #10b981;">Replied</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view-message.php?id=<?php echo $msg['id']; ?>" class="btn-small btn-view">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; padding: 30px; color: #6b7280;">No messages yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>