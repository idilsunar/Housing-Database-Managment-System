<?php
require_once '../user/vendor/autoload.php';

$ticket = null;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->support_tickets_db->tickets;
    
    $ticket_id = $_GET['id'] ?? '';
    
    if (!$ticket_id) {
        die("Ticket ID required");
    }
    
    // Get ticket
    $ticket = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($ticket_id)]);
    
    if (!$ticket) {
        die("Ticket not found");
    }
    
    // Handle comment submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $comment = 'admin: ' . $_POST['comment'];
        
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($ticket_id)],
            ['$push' => ['comments' => $comment]]
        );
        
        header('Location: ticket_details.php?id=' . $ticket_id);
        exit;
    }
    
    // Handle resolve
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve'])) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($ticket_id)],
            ['$set' => ['status' => false]]
        );
        
        header('Location: index.php');
        exit;
    }
    
} catch (Exception $e) {
    die("MongoDB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Ticket Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #f44336;
            padding-bottom: 10px;
        }
        .ticket-info, .comments, .add-comment, .actions {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .comment {
            padding: 15px;
            background: #f9f9f9;
            margin: 10px 0;
            border-left: 3px solid #f44336;
            border-radius: 3px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
            font-family: Arial, sans-serif;
            min-height: 100px;
        }
        button {
            background-color: #f44336;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #da190b;
        }
        .resolve-btn {
            background-color: #4CAF50;
        }
        .resolve-btn:hover {
            background-color: #45a049;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #f44336;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Admin - Ticket Details</h1>
    
    <div class="ticket-info">
        <h2><?php echo htmlspecialchars($ticket['username']); ?>'s Ticket</h2>
        <p><strong>Created:</strong> <?php echo $ticket['created_at']; ?></p>
        <p><strong>Status:</strong> <?php echo $ticket['status'] ? 'Active' : 'Resolved'; ?></p>
        <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($ticket['message'])); ?></p>
    </div>
    
    <div class="comments">
        <h3>Comments (<?php echo count($ticket['comments'] ?? []); ?>)</h3>
        <?php if (empty($ticket['comments'])): ?>
            <p style="color: #999;">No comments yet.</p>
        <?php else: ?>
            <?php foreach ($ticket['comments'] as $comment): ?>
                <div class="comment">
                    <?php echo nl2br(htmlspecialchars($comment)); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="add-comment">
        <h3>Add Admin Comment</h3>
        <form method="POST">
            <textarea name="comment" placeholder="Your admin comment..." required></textarea>
            <button type="submit">Post Comment</button>
        </form>
    </div>
    
    <?php if ($ticket['status']): ?>
    <div class="actions">
        <h3>Actions</h3>
        <form method="POST">
            <button type="submit" name="resolve" class="resolve-btn">Mark as Resolved</button>
        </form>
    </div>
    <?php endif; ?>
    
    <a href="index.php" class="back-link">← Back to admin panel</a>
</body>
</html>