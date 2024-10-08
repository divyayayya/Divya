<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work From Home Request</title>
    <script>
        function toggleRequestType() {
            const singleDay = document.getElementById("single-day");
            const recurring = document.getElementById("recurring");
            document.getElementById("recurring-inputs").style.display = recurring.checked ? "block" : "none";
            document.getElementById("single-day-inputs").style.display = singleDay.checked ? "block" : "none";
        }

        function limitDaySelection() {
            const days = document.querySelectorAll('input[name="days_of_week[]"]:checked');
            if (days.length > 2) {
                alert("You can select a maximum of 2 days per week.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Work From Home Request</h2>
    <form action="process_wfh_request.php" method="POST" onsubmit="return limitDaySelection()">
        <label><input type="radio" id="single-day" name="request_type" value="single" onclick="toggleRequestType()" checked> Single Day</label>
        <label><input type="radio" id="recurring" name="request_type" value="recurring" onclick="toggleRequestType()"> Recurring</label>

        <div id="single-day-inputs">
            <label for="date">Date:</label>
            <input type="date" name="start_date" required>
        </div>

        <div id="recurring-inputs" style="display: none;">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

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
</body>
</html>
