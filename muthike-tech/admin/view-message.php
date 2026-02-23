<?php
require_once '../includes/config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
// ... rest of your code
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header("Location: messages.php");
    exit();
}

// Get message
$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$message) {
    header("Location: messages.php");
    exit();
}

// Mark as read
if (!$message['is_read']) {
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message - Muthike Tech</title>
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
        
        .message-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .message-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .message-header h2 {
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .message-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-weight: 600;
            color: #1f2937;
        }
        
        .message-body {
            background: #f9fafb;
            padding: 20px;
            border-radius: 5px;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        
        .back-btn:hover {
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
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a>
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
                <h1>View Message</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="message-container">
            <div class="message-header">
                <h2><?php echo htmlspecialchars($message['subject'] ?: 'No Subject'); ?></h2>
                <p>Received: <?php echo date('F j, Y \a\t g:i A', strtotime($message['created_at'])); ?></p>
            </div>
            
            <div class="message-meta">
                <div class="meta-item">
                    <span class="meta-label">From:</span>
                    <span class="meta-value"><?php echo htmlspecialchars($message['name']); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Email:</span>
                    <span class="meta-value"><?php echo htmlspecialchars($message['email']); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Phone:</span>
                    <span class="meta-value"><?php echo htmlspecialchars($message['phone'] ?: 'Not provided'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Status:</span>
                    <span class="meta-value"><?php echo $message['is_read'] ? 'Read' : 'Unread'; ?></span>
                </div>
            </div>
            
            <div class="message-body">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
            
            <a href="messages.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Messages</a>
        </div>
    </div>
</body>
</html>