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
        /* General Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
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
</div>

<h2>Work From Home Request</h2>

<!-- Request Type Selection Using Radio Buttons -->
<div style="text-align:center; margin-bottom: 20px;">
    <input type='radio' name="request_type" id="single_day_radio" onclick="showSingleDay()" checked> Single Day
    <input type='radio' name="request_type" id="recurring_radio" onclick="showRecurring()"> Recurring
</div>

<!-- Single Day Application Fields -->
<div id="single-day-fields" class="active">
    <h3>Single Day Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <!-- Hidden field to store request type -->
        <input type="hidden" id="request_type_hidden" name="request_type" value="single_day">

        <label for="single_start_date">Date:</label>
        <input type="date" name="single_start_date" min=<?php echo(date("Y-m-d"))?> required>

        <!-- Time selection -->
        <label>Select Time:</label>
        <div class="radio-group">
            <label><input type="radio" name="time" value="AM" required> AM</label>
            <label><input type="radio" name="time" value="PM" required> PM</label>
            <label><input type="radio" name="time" value="Full Day" required> Full Day</label>
        </div>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Submit Single Day Request</button>
    </form>
</div>

<!-- Recurring Application Fields -->
<div id="recurring-fields">
    <h3>Recurring Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <!-- Hidden field to store request type -->
        <input type="hidden" id="request_type_hidden" name="request_type" value="recurring">

        <label for="recurring_start_date">Start Date:</label>
        <input type="date" name="recurring_start_date" min=<?php echo(date("Y-m-d"))?> required>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" min=<?php echo(date("Y-m-d"))?> required>

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
    // Function to show the Single Day fields and hide Recurring fields
    function showSingleDay() {
        document.getElementById("single-day-fields").classList.add("active");
        document.getElementById("recurring-fields").classList.remove("active");

        // Update hidden field to reflect single day request
        document.getElementById("request_type_hidden").value = "single_day";
    }

    // Function to show the Recurring fields and hide Single Day fields
    function showRecurring() {
        document.getElementById("single-day-fields").classList.remove("active");
        document.getElementById("recurring-fields").classList.add("active");

        // Update hidden field to reflect recurring request
        document.getElementById("request_type_hidden").value = "recurring";
    }
</script>

</body>
</html>
