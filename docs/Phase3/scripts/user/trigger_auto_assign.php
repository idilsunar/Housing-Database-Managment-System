<?php
require_once 'db_connect.php';

$message = '';

// Handle button clicks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['case1'])) {
        // Case 1: Insert WITH assignee (trigger should NOT activate)
        $sql = "INSERT INTO MaintenanceRequest 
                (request_id, dorm_id, roomno, reporter_stud_id, assignee_staff_id) 
                VALUES (5001, 1, 101, 1001, 502)";
        
        if ($conn->query($sql)) {
            $check = $conn->query("SELECT assignee_staff_id FROM MaintenanceRequest WHERE request_id = 5001");
            $row = $check->fetch_assoc();
            $message = "✅ Case 1: Request inserted WITH assignee (502). Trigger did NOT activate. Assigned to: " . $row['assignee_staff_id'];
            // Cleanup
            $conn->query("DELETE FROM MaintenanceRequest WHERE request_id = 5001");
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
    
    if (isset($_POST['case2'])) {
        // Case 2: Insert WITHOUT assignee (trigger SHOULD activate and set 501)
        $sql = "INSERT INTO MaintenanceRequest 
                (request_id, dorm_id, roomno, reporter_stud_id, assignee_staff_id) 
                VALUES (5002, 1, 101, 1001, NULL)";
        
        if ($conn->query($sql)) {
            $check = $conn->query("SELECT assignee_staff_id FROM MaintenanceRequest WHERE request_id = 5002");
            $row = $check->fetch_assoc();
            $message = "✅ Case 2: Request inserted WITHOUT assignee (NULL). Trigger activated! Auto-assigned to: " . $row['assignee_staff_id'];
            // Cleanup
            $conn->query("DELETE FROM MaintenanceRequest WHERE request_id = 5002");
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger: Auto-Assign Maintenance Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .description {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .test-case {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
        }
        button {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        button:hover {
            background-color: #0b7dda;
        }
        .message {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #2196F3;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Trigger: Auto-Assign Maintenance Request</h1>
    
    <div class="description">
        <h3>How it works:</h3>
        <p>This trigger automatically assigns a default staff member (ID: 501 - Ahmet Usta, Electrician) to any maintenance request that is created without an assigned technician.</p>
        <p><strong>Trigger Name:</strong> trg_request_auto_assign</p>
        <p><strong>Event:</strong> BEFORE INSERT on MaintenanceRequest table</p>
    </div>
    
    <h3>Test Cases:</h3>
    
    <form method="POST">
        <div class="test-case">
            <h4>Case 1: Insert request WITH assignee</h4>
            <p>Inserts a maintenance request with assignee_staff_id = 502. The trigger should NOT activate.</p>
            <button type="submit" name="case1">Run Case 1</button>
        </div>
    </form>
    
    <form method="POST">
        <div class="test-case">
            <h4>Case 2: Insert request WITHOUT assignee (NULL)</h4>
            <p>Inserts a maintenance request with assignee_staff_id = NULL. The trigger SHOULD activate and auto-assign to staff ID 501.</p>
            <button type="submit" name="case2">Run Case 2</button>
        </div>
    </form>
    
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <a href="index.php" class="back-link">← Go to homepage</a>
</body>
</html>