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
    </script>
</head>
<body>
    <h2>Work From Home Request</h2>
    <form action="process_wfh_request.php" method="POST">
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
            <label for="frequency">Frequency:</label>
            <select name="frequency" required>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
