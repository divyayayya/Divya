<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply for Work-from-Home</title>
</head>
<body>
<form action="process_wfh_request.php" method="post">
    <label for="dates">Select Dates for Work-from-Home:</label><br>
    <input type="date" id="date1" name="date1" required><br>
    <input type="date" id="date2" name="date2"><br>
    <!-- // addmoredates -->
    <label for="reason">Reason for Request:</label><br>
    <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br>

    <input type="submit" value="Submit Request">
</form>
</body>
</html>

<!-- not done yet either -->