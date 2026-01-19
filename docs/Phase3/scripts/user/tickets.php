<?php
require_once 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->support_tickets_db->tickets;
    
    // Get filter from query string
    $username_filter = isset($_GET['username']) ? $_GET['username'] : '';
    
    // Build query
    $query = ['status' => true]; // Only active tickets
    if ($username_filter) {
        $query['username'] = $username_filter;
    }
    
    // Get tickets
    $tickets = $collection->find($query, ['sort' => ['created_at' => -1]]);
    
    // Get unique usernames for filter
    $usernames = $collection->distinct('username');
    
} catch (Exception $e) {
    die("MongoDB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .controls {
            background: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .controls select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .ticket {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #2196F3;
        }
        .ticket h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .ticket .meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .ticket .message {
            color: #444;
            line-height: 1.6;
        }
        .ticket .comments-count {
            color: #999;
            font-size: 13px;
            margin-top: 10px;
        }
        .no-tickets {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 5px;
            color: #999;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2196F3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Support Tickets</h1>
    
    <div class="controls">
        <form method="GET" style="display: flex; gap: 10px; flex: 1;">
            <select name="username" onchange="this.form.submit()">
                <option value="">All Users</option>
                <?php foreach ($usernames as $user): ?>
                    <option value="<?php echo htmlspecialchars($user); ?>" 
                            <?php echo $username_filter === $user ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="create_ticket.php" class="btn">Create New Ticket</a>
    </div>
    
    <?php 
    $tickets_array = iterator_to_array($tickets);
    if (empty($tickets_array)): 
    ?>
        <div class="no-tickets">No active tickets found.</div>
    <?php else: ?>
        <?php foreach ($tickets_array as $ticket): ?>
            <div class="ticket">
                <h3><?php echo htmlspecialchars($ticket['username']); ?>'s Ticket</h3>
                <div class="meta">
                    Created: <?php echo htmlspecialchars($ticket['created_at']); ?>
                </div>
                <div class="message">
                    <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                </div>
                <div class="comments-count">
                    <?php echo count($ticket['comments'] ?? []); ?> comments
                </div>
                <a href="ticket_details.php?id=<?php echo $ticket['_id']; ?>" class="btn" style="margin-top: 10px; font-size: 14px;">View Details</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <a href="index.php" class="back-link">← Back to homepage</a>
</body>
</html>