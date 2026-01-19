<?php
require_once '../user/vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->support_tickets_db->tickets;
    
    // Get all ACTIVE tickets (admin sees all users)
    $tickets = $collection->find(['status' => true], ['sort' => ['created_at' => -1]]);
    
} catch (Exception $e) {
    die("MongoDB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - All Tickets</title>
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
            border-bottom: 3px solid #f44336;
            padding-bottom: 10px;
        }
        .ticket {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #f44336;
        }
        .btn {
            background-color: #f44336;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Admin Panel - All Active Tickets</h1>
    
    <?php 
    $tickets_array = iterator_to_array($tickets);
    if (empty($tickets_array)): 
    ?>
        <p>No active tickets.</p>
    <?php else: ?>
        <?php foreach ($tickets_array as $ticket): ?>
            <div class="ticket">
                <h3><?php echo htmlspecialchars($ticket['username']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($ticket['message'])); ?></p>
                <small>Created: <?php echo $ticket['created_at']; ?> | Comments: <?php echo count($ticket['comments'] ?? []); ?></small>
                <br>
                <a href="ticket_details.php?id=<?php echo $ticket['_id']; ?>" class="btn">View & Manage</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>