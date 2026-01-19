<?php
require_once 'db_connect.php';

$results = [];
$dorm_name = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dorm_id'])) {
    $dorm_id = intval($_POST['dorm_id']);
    
    // Get dorm name
    $dorm_query = $conn->query("SELECT dorm_name FROM Dorm WHERE dorm_id = $dorm_id");
    if ($dorm_query && $dorm_query->num_rows > 0) {
        $dorm_name = $dorm_query->fetch_assoc()['dorm_name'];
        
        // Call stored procedure
        $result = $conn->query("CALL sp_room_occupancy_report($dorm_id)");
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            $result->free();
            // Clear multi-query result
            while ($conn->more_results()) {
                $conn->next_result();
                if ($res = $conn->store_result()) {
                    $res->free();
                }
            }
        } else {
            $error = "Error calling procedure: " . $conn->error;
        }
    } else {
        $error = "Dorm ID not found!";
    }
}

// Get all dorms for dropdown
$dorms = $conn->query("SELECT dorm_id, dorm_name FROM Dorm ORDER BY dorm_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored Procedure: Room Occupancy Report</title>
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
        .description {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-container {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
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
    <h1>Stored Procedure: Room Occupancy Report</h1>
    
    <div class="description">
        <h3>How it works:</h3>
        <p>This stored procedure generates a comprehensive occupancy report for a specified dorm, showing:</p>
        <ul>
            <li>Room number and capacity</li>
            <li>Current occupancy (number of students)</li>
            <li>Available beds (free spaces)</li>
        </ul>
        <p><strong>Procedure Name:</strong> sp_room_occupancy_report</p>
        <p><strong>Parameter:</strong> p_dorm_id (INT)</p>
    </div>
    
    <div class="form-container">
        <form method="POST">
            <label for="dorm_id">Select Dorm:</label>
            <select name="dorm_id" id="dorm_id" required>
                <option value="">-- Choose a dorm --</option>
                <?php while ($dorm = $dorms->fetch_assoc()): ?>
                    <option value="<?php echo $dorm['dorm_id']; ?>">
                        <?php echo $dorm['dorm_id'] . ' - ' . $dorm['dorm_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Generate Report</button>
        </form>
    </div>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($results)): ?>
        <div class="results">
            <h3>Occupancy Report for: <?php echo htmlspecialchars($dorm_name); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Dorm ID</th>
                        <th>Room No</th>
                        <th>Capacity</th>
                        <th>Current Occupancy</th>
                        <th>Free Beds</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo $row['dorm_id']; ?></td>
                            <td><?php echo $row['roomno']; ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td><?php echo $row['current_occupancy']; ?></td>
                            <td><?php echo $row['free_beds']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="index.php" class="back-link">← Back to homepage</a>
</body>
</html>