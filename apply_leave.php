<?php
    require_once "model/common.php";
    
    // Check if the user is logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch user details
    $userID = $_SESSION['userID'];

    // Fetch available leave days from the database (example logic)
    $dao = new RequestDAO();  // Assuming you have a DAO that retrieves leave data
    // $leaveDays = $dao->getAvailableLeaveDays($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <style>

body { 
            font-family: 'Segoe UI'; 
            background-color: #f0f0f0; 
            color: #333; 
            margin: 0; 
            padding: 0; 
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        h2, h3 {
            color: #333; 
            text-align: center; 
        }

        /* Navbar Styling */
        .navbar {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            height: 80px;
            border-bottom: 1px solid #444;
        }

        .navbar a img {
            height: 60px;
        }

        form { 
            max-width: 600px; 
            margin: 30px auto; 
            background-color: #fff; 
            padding: 30px; 
            border-radius: 8px; 
        }

        label, input, textarea, button { 
            display: block; 
            width: 100%; 
            margin-top: 10px; 
        }

        input[type="date"], textarea { 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }

        textarea { 
            resize: vertical; 
            height: 100px; 
        }

        button { 
            padding: 12px; 
            background-color: #000; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }

        button:hover { 
            background-color: #333; 
        }

        .backbutton a { 
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
            text-decoration: none;
        }

        .backbutton a:hover {
            background-color: #666; /* Change background on hover */
            color: #fff; /* Change text color on hover */
        }


        .radio-group, .checkbox-group { 
            display: flex; 
            gap: 10px; 
            margin-top: 10px; 
        }

        #single-day-fields, #recurring-fields { 
            display: none; 
        }

        #single-day-fields.active, #recurring-fields.active { 
            display: block; 
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="home.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->
        <div class="backbutton">
            <a href="my_requests.php">Back</a>
        </div>
    </div>

    <h1>Apply for Leave</h1>

    <!-- <h3>Number of Available Leaves</h3>
    <table border="1">
        <tr>
            <th>Category</th>
            <th>Number of Days Left</th>
        </tr>
        <?php foreach ($leaveDays as $category => $daysLeft): ?>
            <tr>
                <td><?php echo htmlspecialchars($category); ?></td>
                <td><?php echo htmlspecialchars($daysLeft); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> -->

    <br>

    <form action="process_leave_request.php" method="POST">
        <input type="hidden" name="userID" value="<?php echo $userID; ?>">
        
        <label for="leave_date">Select Date(s):</label><br>
        <input type="date" name="leave_date" required><br><br>
        
        <label for="time">Select Time:</label><br>
        <select name="time" id="time" required>
            <option value='AM'>AM</option>
            <option value='PM'>PM</option>
            <option value='Full day'>Full Day</option>
        </select><br><br>

        <label for="reason">Reason for leave:</label><br>
        <textarea name="reason" required></textarea><br><br>
        
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>

