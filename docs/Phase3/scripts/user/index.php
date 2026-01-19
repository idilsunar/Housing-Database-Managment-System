<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Housing Management System</title>
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
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        .item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .item a {
            text-decoration: none;
            color: #2196F3;
            font-weight: bold;
        }
        .item a:hover {
            color: #0b7dda;
        }
        .description {
            color: #666;
            margin-top: 5px;
            font-size: 14px;
        }
        .responsible {
            color: #999;
            font-size: 13px;
            font-style: italic;
        }
        .support-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .support-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Housing Management System - User Portal</h1>
    
    <h2>Triggers:</h2>
    
    <div class="item">
        <a href="trigger_auto_assign.php">Trigger: Auto-Assign Maintenance Request</a>
        <div class="description">
            Automatically assigns staff member (ID: 501) to maintenance requests when no assignee is specified.
        </div>
        <div class="responsible">Responsible: [Your Name]</div>
    </div>
    
    <h2>Stored Procedures:</h2>
    
    <div class="item">
        <a href="procedure_occupancy.php">Stored Procedure: Room Occupancy Report</a>
        <div class="description">
            Generates a report showing room capacity, current occupancy, and available beds for a specified dorm.
        </div>
        <div class="responsible">Responsible: [Your Name]</div>
    </div>
    
    <br>
    <a href="tickets.php" class="support-link">Support Ticket System</a>
</body>
</html>