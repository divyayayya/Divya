<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work From Home Request</title>
    <style>
        /* Predefined styles to hide or show sections */
        #single-day-fields {
            width: 500px;
        }

        #recurring-fields {
            width: 500px;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>

<h2>Work From Home Request</h2>

<p>Click on the radio button to switch between Single Day and Recurring requests:</p>

<!-- Request Type Selection Using Radio Buttons -->
<input type='radio' name="request_type" onclick="showSingleDay()" checked> Single Day
<input type='radio' name="request_type" onclick="showRecurring()"> Recurring

<!-- Single Day Application Fields -->
<div id="single-day-fields">
    <h3>Single Day Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <label for="single_start_date">Date:</label>
        <input type="date" name="single_start_date" 
               min=<?php echo(date("Y-m-d"))?> required><br>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea><br>

        <button type="submit">Submit Single Day Request</button>
    </form>
</div>

<!-- Recurring Application Fields -->
<div id="recurring-fields">
    <h3>Recurring Application</h3>
    <form action="process_wfh_request.php" method="POST">
        <label for="recurring_start_date">Start Date:</label>
        <input type="date" name="recurring_start_date" 
               min=<?php echo(date("Y-m-d"))?> required><br>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" min=<?php echo(date("Y-m-d"))?> required><br>

        <label>Select days of the week (maximum 2):</label><br>
        <input type="checkbox" name="days_of_week[]" value="Monday"> Monday<br>
        <input type="checkbox" name="days_of_week[]" value="Tuesday"> Tuesday<br>
        <input type="checkbox" name="days_of_week[]" value="Wednesday"> Wednesday<br>
        <input type="checkbox" name="days_of_week[]" value="Thursday"> Thursday<br>
        <input type="checkbox" name="days_of_week[]" value="Friday"> Friday<br>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea><br>

        <button type="submit">Submit Recurring Request</button>
    </form>
</div>

<script>
    // Function to show the Single Day fields and hide Recurring fields
    function showSingleDay() {
        document.getElementById("single-day-fields").style.display = "block";
        document.getElementById("recurring-fields").style.display = "none";
    }

    // Function to show the Recurring fields and hide Single Day fields
    function showRecurring() {
        document.getElementById("single-day-fields").style.display = "none";
        document.getElementById("recurring-fields").style.display = "block";
    }
</script>

</body>
</html>
