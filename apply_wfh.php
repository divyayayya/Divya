<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work From Home Request</title>
</head>
<body>
    <h2>Work From Home Request</h2>
    <form action="process_wfh_request.php" method="POST" onsubmit="return limitDaySelection()">
        <label for="request_type">Request Type:</label>
        <select name="request_type" id="request_type">
            <option value="single_day">Single Day</option>
            <option value="recurring">Recurring</option>
        </select>
        <br>
 
        <!-- for single day application -->
        <div id="single-day-fields">
            <label for="start_date">Date:</label>
            <input type="date" name="start_date" min=<?php echo(date("Y-m-d"))?> required>
        </div>

        <!-- for recurring application only if recurring is chosen -->
        <div id="recurring-fields" style="display:none;">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" min=<?php echo(date("Y-m-d"))?> required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" min=<?php echo(date("Y-m-d"))?> required>

            <label>Select days of the week (maximum 2):</label><br>
            <input type="checkbox" name="days_of_week[]" value="Monday"> Monday<br>
            <input type="checkbox" name="days_of_week[]" value="Tuesday"> Tuesday<br>
            <input type="checkbox" name="days_of_week[]" value="Wednesday"> Wednesday<br>
            <input type="checkbox" name="days_of_week[]" value="Thursday"> Thursday<br>
            <input type="checkbox" name="days_of_week[]" value="Friday"> Friday<br>
        </div>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Submit Request</button>
    </form>

    <script>
        document.getElementById('request_type').addEventListener('change', function () {
            const requestType = this.value;
            const singleDayFields = document.getElementById('single-day-fields');
            const recurringFields = document.getElementById('recurring-fields');

            if (requestType === 'single_day') {
                singleDayFields.style.display = 'block';
                recurringFields.style.display = 'none';
            } else {
                singleDayFields.style.display = 'none';
                recurringFields.style.display = 'block';
            }
        });
    </script>

</body>
</html>
