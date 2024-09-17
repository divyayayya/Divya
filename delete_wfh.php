<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Work-from-Home Arrangements</title>
</head>
<body>
    <h1>Delete Work-from-Home Arrangements</h1>

    <!-- Assuming user is logged in and can see their work-from-home schedule -->
    <form action="process_deletion.php" method="post">
        <label for="arrangement_id">Select Work-from-Home Arrangement to Delete:</label><br>
        <select id="arrangement_id" name="arrangement_id" required>
            <!-- Options would be populated dynamically from the database -->
            <!-- Example static options -->
            <option value="1">Arrangement 1 - 2024-09-01 to 2024-09-05</option>
            <option value="2">Arrangement 2 - 2024-09-10 to 2024-09-12</option>
        </select><br><br>

        <label for="reason">Reason for Deleting:</label><br>
        <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Submit Deletion Request">
    </form>
</body>
</html>

<!-- backend not done yet -->