<!DOCTYPE html>
<html>
<head>
    <title>Delete Work-from-Home Arrangement</title>
</head>
<body>
    <h1>Delete Work-from-Home Arrangement</h1>
    <form id="delete-form">
        <label for="staff_id">Staff ID:</label>
        <input type="text" id="staff_id" name="staff_id" required><br><br>

        <label for="arrangement_id">Arrangement ID:</label>
        <input type="text" id="arrangement_id" name="arrangement_id" required><br><br>

        <label for="reason">Reason for Deletion:</label><br>
        <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>

        <button type="button" onclick="submitDeletionRequest()">Submit</button>
    </form>

    <script>
        function submitDeletionRequest() {
            const staffId = document.getElementById('staff_id').value;
            const arrangementId = document.getElementById('arrangement_id').value;
            const reason = document.getElementById('reason').value;

            fetch('/delete_arrangement', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    staff_id: staffId,
                    arrangement_id: arrangementId,
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
