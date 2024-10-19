<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work From Home Request</title>
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

<!-- Request Type Selection Using Radio Buttons -->
<div style="text-align:center;">
    <input type='radio' name="request_type" id="single_day_radio" onclick="showSingleDay()" checked> Single Day
    <input type='radio' name="request_type" id="recurring_radio" onclick="showRecurring()"> Recurring
</div>

<!-- Single Day Application Fields -->
<div id="single-day-fields" class="active">
    <h3>Single Day Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <input type="hidden" name="request_type" value="single_day">

        <label for="single_start_date">Date:</label>
        <input type="date" name="single_start_date" min="<?php echo date("Y-m-d"); ?>" required>

        <label for="time">Select Time:</label><br>
        <select name="time" id="time" required>
            <option value='AM'>AM</option>
            <option value='PM'>PM</option>
            <option value='Full day'>Full Day</option>
        </select><br><br>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Submit Single Day Request</button>
    </form>
</div>

<!-- Recurring Application Fields -->
<div id="recurring-fields">
    <h3>Recurring Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <input type="hidden" name="request_type" value="recurring">

        <label for="recurring_start_date">Start Date:</label>
        <input type="date" name="recurring_start_date" min="<?php echo date("Y-m-d"); ?>" required>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" min="<?php echo date("Y-m-d"); ?>" required>

        <label>Select days of the week (maximum 2):</label>
        <div class="checkbox-group">
            <label><input type="checkbox" name="days_of_week[]" value="Monday"> Monday</label>
            <label><input type="checkbox" name="days_of_week[]" value="Tuesday"> Tuesday</label>
            <label><input type="checkbox" name="days_of_week[]" value="Wednesday"> Wednesday</label>
            <label><input type="checkbox" name="days_of_week[]" value="Thursday"> Thursday</label>
            <label><input type="checkbox" name="days_of_week[]" value="Friday"> Friday</label>
        </div>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Submit Recurring Request</button>
    </form>
</div>

<script>
    function showSingleDay() {
        document.getElementById("single-day-fields").classList.add("active");
        document.getElementById("recurring-fields").classList.remove("active");
    }

    function showRecurring() {
        document.getElementById("single-day-fields").classList.remove("active");
        document.getElementById("recurring-fields").classList.add("active");
    }
</script>

</body>
</html>
