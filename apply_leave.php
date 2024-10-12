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
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
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

        /* Form Styling */
        form {
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="date"], 
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        /* Radio and Checkbox Styling */
        input[type="radio"], input[type="checkbox"] {
            margin-right: px;
        }

        /* Button Styling */
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

        button {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #333;
        }

        /* Form Sections */
        #single-day-fields, #recurring-fields {
            display: none;
        }

        #single-day-fields.active, #recurring-fields.active {
            display: block;
        }

        /* Checkbox and Radio Button Alignment */
        .checkbox-group, .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .navbar {
                padding: 0 15px;
            }

            form {
                padding: 15px;
            }
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
            <option value='full_day'>Full Day</option>
        </select><br><br>

        <label for="reason">Reason for leave:</label><br>
        <textarea name="reason" required></textarea><br><br>
        
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
